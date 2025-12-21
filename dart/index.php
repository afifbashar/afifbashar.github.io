<?php
require_once 'config.php';
requireLogin();
$doctor_id = $_SESSION['user_id'];
$success = $error = '';
$is_template = isset($_GET['template']) && $_GET['template'] == '1';
$from_template = isset($_GET['from_template']) ? (int)$_GET['from_template'] : 0;

date_default_timezone_set('Asia/Dhaka');

// Initialize form data
$form_data = [
    'bp' => '', 'pulse' => '', 'temperature' => '', 'spo2' => '',
    'chief_complaints' => '', 'medical_history' => '', 'examination_findings' => '',
    'diagnosis' => '', 'investigation' => '', 'advice' => '', 'next_visit' => '',
    'drugs' => []
];

// Load template data if creating from a template
$template_data = null;
if ($from_template) {
    $sql = "SELECT * FROM prescriptions WHERE id = ? AND is_template = 1 AND doctor_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $from_template, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $template_data = mysqli_fetch_assoc($result);
        
        if ($template_data) {
            $form_data = array_merge($form_data, $template_data);
            $sql = "SELECT pd.*, d.brand_name, d.generic_name, d.strength, d.manufacturer, d.drug_class, d.price 
                   FROM prescription_drugs pd 
                   LEFT JOIN drugs d ON pd.drug_id = d.id 
                   WHERE pd.prescription_id = ?";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $from_template);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $template_drugs = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $template_drugs[] = $row;
                }
                $form_data['drugs'] = $template_drugs;
            }
        }
    }
}

// Load all templates
$template_types = [
    'frequency' => 'frequency_templates',
    'duration' => 'duration_templates',
    'instruction' => 'instruction_templates',
    'diagnosis' => 'diagnosis_templates',
    'chief_complaint' => 'chief_complaint_templates',
    'medical_history' => 'medical_history_templates',
    'examination_findings' => 'examination_findings_templates',
    'advice' => 'advice_templates',
    'investigation' => 'investigation_templates'
];

$templates = [];
foreach ($template_types as $type => $table) {
    $templates[$type] = [];
    $sql = "SELECT * FROM $table WHERE doctor_id = ? OR is_default = 1 ORDER BY is_default DESC, name";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $templates[$type][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_template ? 'Create Template' : 'Create Prescription'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="assets/tinymce/tinymce.min.js"></script>
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .form-label { font-weight: 600; color: #4361ee; }
        .btn-primary { background: #4361ee; border: none; }
        .suggestion-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 12px 12px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            display: none;
        }
        .suggestion-item {
            padding: 10px 15px;
            cursor: pointer;
        }
        .suggestion-item:hover { background: #f0f0f0; }
        .drug-row { background: #f1f3f5; border-radius: 12px; padding: 15px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><?php echo $is_template ? 'Create Template' : 'Create Prescription'; ?></h3>
            </div>
            <div class="card-body">
                <form method="POST" id="prescriptionForm">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <!-- Patient Selection -->
                            <div class="mb-4">
                                <label class="form-label">Select Patient</label>
                                <div class="input-group">
                                    <select name="patient_id" class="form-select select2" <?php echo $is_template ? 'disabled' : 'required'; ?>>
                                        <option value="">Search patient...</option>
                                        <?php
                                        $sql = "SELECT id, name, patient_uid, age, sex FROM patients WHERE doctor_id = ? ORDER BY name";
                                        if ($stmt = mysqli_prepare($conn, $sql)) {
                                            mysqli_stmt_bind_param($stmt, "i", $doctor_id);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while ($p = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$p['id']}'>{$p['name']} ({$p['patient_uid']}) - {$p['age']}y/{$p['sex']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                                        Quick Add
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#scanPatientModal">
                                        Scan QR
                                    </button>
                                </div>
                            </div>

                            <!-- Vitals -->
                            <div class="mb-4">
                                <label class="form-label">Vitals</label>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <input type="text" name="bp" class="form-control" placeholder="BP" value="<?php echo htmlspecialchars($form_data['bp']); ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="pulse" class="form-control" placeholder="Pulse" value="<?php echo htmlspecialchars($form_data['pulse']); ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="temperature" class="form-control" placeholder="Temperature" value="<?php echo htmlspecialchars($form_data['temperature']); ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="spo2" class="form-control" placeholder="SpO2" value="<?php echo htmlspecialchars($form_data['spo2']); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Clinical Fields -->
                            <div class="mb-4 position-relative">
                                <label class="form-label">Chief Complaints</label>
                                <textarea name="chief_complaints" id="chief_complaints" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['chief_complaints']); ?></textarea>
                                <div id="suggestions-chief_complaints" class="suggestion-box"></div>
                            </div>
                            <div class="mb-4 position-relative">
                                <label class="form-label">Medical History</label>
                                <textarea name="medical_history" id="medical_history" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['medical_history']); ?></textarea>
                                <div id="suggestions-medical_history" class="suggestion-box"></div>
                            </div>
                            <div class="mb-4 position-relative">
                                <label class="form-label">Examination Findings</label>
                                <textarea name="examination_findings" id="examination_findings" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['examination_findings']); ?></textarea>
                                <div id="suggestions-examination_findings" class="suggestion-box"></div>
                            </div>
                            <div class="mb-4 position-relative">
                                <label class="form-label">Diagnosis</label>
                                <textarea name="diagnosis" id="diagnosis" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['diagnosis']); ?></textarea>
                                <div id="suggestions-diagnosis" class="suggestion-box"></div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6">
                            <!-- Drugs -->
                            <div class="mb-4">
                                <label class="form-label">Medicines</label>
                                <div id="drugsList">
                                    <?php foreach ($form_data['drugs'] as $index => $drug): ?>
                                        <!-- তোমার আগের drug row code -->
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="btn btn-outline-primary mt-2" id="addDrugRow">
                                    Add Medicine
                                </button>
                            </div>

                            <!-- Investigation & Advice -->
                            <div class="mb-4 position-relative">
                                <label class="form-label">Investigation</label>
                                <textarea name="investigation" id="investigation" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['investigation']); ?></textarea>
                                <div id="suggestions-investigation" class="suggestion-box"></div>
                            </div>
                            <div class="mb-4 position-relative">
                                <label class="form-label">Advice</label>
                                <textarea name="advice" id="advice" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['advice']); ?></textarea>
                                <div id="suggestions-advice" class="suggestion-box"></div>
                            </div>

                            <!-- Next Visit -->
                            <div class="mb-4">
                                <label class="form-label">Next Visit</label>
                                <input type="date" name="next_visit" class="form-control" value="<?php echo $form_data['next_visit']; ?>">
                            </div>

                            <?php if (!$is_template): ?>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="save_as_template">
                                    <label class="form-check-label">
                                        Save as Template
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Submit -->
  <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="prescriptions.php" class="btn btn-light"><i class='bx bx-x me-1'></i>Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class='bx bx-save me-1'></i>Save <?php echo $is_template ? "Template" : "Prescription"; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- তোমার আগের মডালগুলো + JS (Select2, QR Scan, Drug Add, Auto-Suggestion, Print Preview) রাখো --

    <!-- Modals and Scripts -->

        <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quickAddPatientForm">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sex</label>
                                <select name="sex" class="form-control">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <!-- Hidden input to pass doctor_id -->
                        <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePatient">Save Patient</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Drug Row Template -->
    <template id="drugRowTemplate">
        <div class="drug-row">
            <div class="row g-3">
                <div class="col-md-12 mb-2">
                    <label class="form-label">Drug <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control select2-drug" name="drugs[INDEX][drug_id]"></select>
                        <input type="text" class="form-control drug-name-input" name="drugs[INDEX][drug_name]" placeholder="Enter drug name if not found">
                        <button type="button" class="btn btn-outline-primary change-brand-btn">Change Brand</button>
                        <button type="button" class="btn btn-outline-info select-manufacturer-btn">Select Manufacturer</button>
                    </div>
                    <div class="drug-info">
                        Manufacturer: N/A<br>
                        Price: N/A<br>
                        Drug Class: N/A
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Frequency <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-select frequency-template">
                            <option value="">Select Template</option>
                            <?php foreach ($templates['frequency'] as $template) { ?>
                                <option value="<?php echo htmlspecialchars($template['frequency']); ?>">
                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="text" class="form-control" name="drugs[INDEX][frequency]" required>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Duration <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-select duration-template">
                            <option value="">Select Template</option>
                            <?php foreach ($templates['duration'] as $template) { ?>
                                <option value="<?php echo htmlspecialchars($template['duration']); ?>">
                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="text" class="form-control" name="drugs[INDEX][duration]" required>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Instructions</label>
                    <div class="input-group">
                        <select class="form-select instruction-template">
                            <option value="">Select Template</option>
                            <?php foreach ($templates['instruction'] as $template) { ?>
                                <option value="<?php echo htmlspecialchars($template['instruction']); ?>">
                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="text" class="form-control" name="drugs[INDEX][instructions]">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-2 remove-drug">Remove</button>
        </div>
    </template>
<!-- QR Scanner Modal for Patient Selection -->
<!-- QR Scanner Modal - ক্যামেরা ১০০% খুলবে -->
<div class="modal fade" id="scanPatientModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    Scan Patient QR Code
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark text-center p-0">
                <div id="qr-reader-patient" style="width:100%; height:500px;"></div>
                <div class="p-3 bg-black text-white">
                    <p class="mb-0">Point camera at patient's QR code</p>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-patient').select2({ 
                theme: 'bootstrap-5', 
                width: '100%', 
                placeholder: 'Select a patient',
                dropdownCssClass: 'select2-scrollable'
            });
            $('.select2-template').select2({ 
                theme: 'bootstrap-5', 
                width: '100%', 
                placeholder: 'Select a template',
                dropdownCssClass: 'select2-scrollable'
            });

            // Initialize Prescription Search Select2
            $('.select2-prescription').select2({
                theme: 'bootstrap-5',
                width: '100%',
                ajax: {
                    url: 'search_prescriptions.php',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term || '',
                            doctor_id: <?php echo $_SESSION['user_id']; ?>
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(p => ({
                                id: p.id,
                                text: `${p.patient_name} (${p.date})`
                            }))
                        };
                    },
                    cache: true
                },
                placeholder: 'Search by patient name or date...',
                allowClear: true
            }).on('select2:select', function(e) {
                const prescriptionId = e.params.data.id;
                $.ajax({
                    url: 'get_prescription_details.php',
                    dataType: 'json',
                    data: { id: prescriptionId },
                    success: function(response) {
                        if (response.success) {
                            // Fill Vitals
                            $('input[name="bp"]').val(response.prescription.bp || '');
                            $('input[name="pulse"]').val(response.prescription.pulse || '');
                            $('input[name="temperature"]').val(response.prescription.temperature || '');
                            $('input[name="spo2"]').val(response.prescription.spo2 || '');

                            // Fill Complaints & History
                            $('textarea[name="chief_complaints"]').val(response.prescription.chief_complaints || '');
                            $('textarea[name="medical_history"]').val(response.prescription.medical_history || '');
                            $('textarea[name="examination_findings"]').val(response.prescription.examination_findings || '');

                            // Fill Diagnosis & Investigation
                            $('textarea[name="diagnosis"]').val(response.prescription.diagnosis || '');
                            $('textarea[name="investigation"]').val(response.prescription.investigation || '');

                            // Fill Drugs
                            $('#drugsList').empty();
                            if (response.drugs && response.drugs.length > 0) {
                                response.drugs.forEach((drug, index) => {
                                    $('#addDrug').click();
                                    const $row = $('#drugsList .drug-row:last');
                                    if (drug.drug_id) {
                                        $row.find('.select2-drug').append(new Option(
                                            `${drug.brand_name} (${drug.generic_name}) ${drug.strength}`,
                                            drug.drug_id,
                                            true,
                                            true
                                        )).trigger('change');
                                        $row.find('.drug-info').html(
                                            'Manufacturer: ' + (drug.manufacturer || 'N/A') + '<br>' +
                                            'Price: ' + (drug.price || 'N/A') + '<br>' +
                                            'Drug Class: ' + (drug.drug_class || 'N/A')
                                        );
                                        $row.data('generic_name', drug.generic_name);
                                        $row.data('strength', drug.strength);
                                        $row.data('current_brand_index', 0);
                                        $row.find('.drug-name-input').val('').hide();
                                    } else {
                                        $row.find('.select2-drug').val(null).trigger('change');
                                        $row.find('.drug-name-input').val(drug.drug_name || '').show();
                                    }
                                    $row.find('input[name$="[frequency]"]').val(drug.frequency || '');
                                    $row.find('input[name$="[duration]"]').val(drug.duration || '');
                                    $row.find('input[name$="[instructions]"]').val(drug.instructions || '');
                                });
                            }

                            // Fill Advice (but not next_visit)
                            $('textarea[name="advice"]').val(response.prescription.advice || '');
                            $('input[name="next_visit"]').val(''); // Reset next visit
                            $('select[name="patient_id"]').val('').trigger('change'); // Reset patient
                        } else {
                            alert('Error loading prescription details: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function() {
                        alert('Failed to load prescription details.');
                    }
                });
            });

            function initializeDrugSelect($select) {
                $select.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    ajax: {
                        url: 'search_drugs.php',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term || '',
                                page: params.page || 1,
                                limit: 50
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 50) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search for a drug...',
                    allowClear: true,
                    tags: true,
                    dropdownCssClass: 'select2-scrollable',
                    minimumInputLength: 1
                }).on('select2:select', function(e) {
                    const data = e.params.data;
                    const $row = $(this).closest('.drug-row');
                    if (data.id && data.id !== data.text) {
                        $row.find('.drug-info').html(
                            'Manufacturer: ' + (data.manufacturer || 'N/A') + '<br>' +
                            'Price: ' + (data.price || 'N/A') + '<br>' +
                            'Drug Class: ' + (data.drug_class || 'N/A')
                        );
                        $row.data('generic_name', data.generic_name);
                        $row.data('strength', data.strength);
                        $row.data('current_brand_index', 0);
                        $row.find('.drug-name-input').val('').hide();
                    } else {
                        $row.find('.drug-info').html(
                            'Manufacturer: N/A<br>' +
                            'Price: N/A<br>' +
                            'Drug Class: N/A'
                        );
                        $row.data('generic_name', '');
                        $row.data('strength', '');
                        $row.data('current_brand_index', -1);
                        $row.find('.drug-name-input').val(data.text).show();
                    }
                }).on('select2:clear', function() {
                    const $row = $(this).closest('.drug-row');
                    $row.find('.drug-info').html(
                        'Manufacturer: N/A<br>' +
                        'Price: N/A<br>' +
                        'Drug Class: N/A'
                    );
                    $row.data('generic_name', '');
                    $row.data('strength', '');
                    $row.data('current_brand_index', -1);
                    $row.find('.drug-name-input').val('').show();
                });
            }

            $('.select2-drug').each(function() { initializeDrugSelect($(this)); });

            $('#addDrug').click(function() {
                const index = $('#drugsList .drug-row').length;
                const template = $('#drugRowTemplate').html().replace(/INDEX/g, index);
                $('#drugsList').append(template);
                initializeDrugSelect($('#drugsList .drug-row:last .select2-drug'));
                $('#drugsList .drug-row:last .drug-name-input').show();
            });

            $(document).on('click', '.remove-drug', function() {
                $(this).closest('.drug-row').remove();
            });

            $(document).on('click', '.change-brand-btn', function() {
                const $row = $(this).closest('.drug-row');
                const $select = $row.find('.select2-drug');
                const generic_name = $row.data('generic_name');
                const strength = $row.data('strength');
                let currentIndex = $row.data('current_brand_index') || 0;

                if (!generic_name || !strength) {
                    alert('Please select a drug from the database first.');
                    return;
                }

                $.ajax({
                    url: 'search_drugs.php',
                    dataType: 'json',
                    data: { 
                        generic_name: generic_name, 
                        strength: strength, 
                        all: true
                    },
                    success: function(data) {
                        if (data.results && data.results.length > 1) {
                            currentIndex = (currentIndex + 1) % data.results.length;
                            const newDrug = data.results[currentIndex];
                            $select.empty().append(new Option(newDrug.text, newDrug.id, true, true)).trigger('change');
                            $row.find('.drug-info').html(
                                'Manufacturer: ' + (newDrug.manufacturer || 'N/A') + '<br>' +
                                'Price: ' + (newDrug.price || 'N/A') + '<br>' +
                                'Drug Class: ' + (newDrug.drug_class || 'N/A')
                            );
                            $row.data('current_brand_index', currentIndex);
                            $row.find('.drug-name-input').val('').hide();
                        } else {
                            alert('No additional brands available.');
                        }
                    }
                });
            });

            $(document).on('click', '.select-manufacturer-btn', function() {
                const $row = $(this).closest('.drug-row');
                const $select = $row.find('.select2-drug');
                const generic_name = $row.data('generic_name');
                const strength = $row.data('strength');

                if (!generic_name || !strength) {
                    alert('Please select a drug from the database first.');
                    return;
                }

                $.ajax({
                    url: 'search_drugs.php',
                    dataType: 'json',
                    data: { 
                        generic_name: generic_name, 
                        strength: strength, 
                        all: true
                    },
                    success: function(data) {
                        if (data.results && data.results.length > 0) {
                            const $modal = $('<div class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Select Manufacturer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><select class="form-select manufacturer-select select2-manufacturer"></select></div><div class="modal-footer"><button type="button" class="btn btn-primary apply-manufacturer">Apply</button></div></div></div></div>');
                            const $selectManufacturer = $modal.find('.manufacturer-select');
                            
                            data.results.forEach(drug => {
                                $selectManufacturer.append(new Option(
                                    `${drug.brand_name} (${drug.manufacturer || 'N/A'}) - ${drug.strength}`,
                                    drug.id
                                ));
                            });
       $selectManufacturer.select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                dropdownParent: $modal.find('.modal-body'),
                                dropdownCssClass: 'select2-scrollable'
                            });

                            $modal.find('.apply-manufacturer').click(function() {
                                const selectedId = $selectManufacturer.val();
                                const selectedDrug = data.results.find(d => d.id == selectedId);
                                if (selectedDrug) {
                                    $select.empty().append(new Option(selectedDrug.text, selectedDrug.id, true, true)).trigger('change');
                                    $row.find('.drug-info').html(
                                        'Manufacturer: ' + (selectedDrug.manufacturer || 'N/A') + '<br>' +
                                        'Price: ' + (selectedDrug.price || 'N/A') + '<br>' +
                                        'Drug Class: ' + (selectedDrug.drug_class || 'N/A')
                                    );
                                    $row.data('current_brand_index', data.results.indexOf(selectedDrug));
                                    $row.find('.drug-name-input').val('').hide();
                                    $modal.modal('hide');
                                }
                            });

                            $modal.modal('show');
                            $('body').append($modal);
                        } else {
                            alert('No manufacturers found for this generic name and strength.');
                        }
                    }
                });
            });

            function insertTemplate(templateSelect, targetTextarea) {
                const value = $(templateSelect).val();
                if (value) {
                    const current = $(targetTextarea).val();
                    $(targetTextarea).val(current ? `${current}\n${value}` : value);
                    $(templateSelect).val('').trigger('change');
                }
            }

            $('#insertChiefComplaintTemplate').click(function() { insertTemplate('#chiefComplaintTemplates', 'textarea[name="chief_complaints"]'); });
            $('#insertMedicalHistoryTemplate').click(function() { insertTemplate('#medicalHistoryTemplates', 'textarea[name="medical_history"]'); });
            $('#insertExaminationFindingsTemplate').click(function() { insertTemplate('#examinationFindingsTemplates', 'textarea[name="examination_findings"]'); });
            $('#insertDiagnosisTemplate').click(function() { insertTemplate('#diagnosisTemplates', 'textarea[name="diagnosis"]'); });
            $('#insertInvestigationTemplate').click(function() { insertTemplate('#investigationTemplates', 'textarea[name="investigation"]'); });
            $('#insertAdviceTemplate').click(function() { insertTemplate('#adviceTemplates', 'textarea[name="advice"]'); });

            $(document).on('change', '.frequency-template', function() {
                const value = $(this).val();
                if (value) $(this).closest('.input-group').find('input[name$="[frequency]"]').val(value);
            });
            $(document).on('change', '.duration-template', function() {
                const value = $(this).val();
                if (value) $(this).closest('.input-group').find('input[name$="[duration]"]').val(value);
            });
            $(document).on('change', '.instruction-template', function() {
                const value = $(this).val();
                if (value) $(this).closest('.input-group').find('input[name$="[instructions]"]').val(value);
            });

            $('#insertTreatmentTemplate').click(function() {
                const templateId = $('#treatmentTemplates').val();
                if (!templateId) return;

                $.getJSON('./get_treatment_template.php', { id: templateId, type: 'prescription' }, function(response) {
                    if (response.success) {
                        $('#drugsList').empty();
                        if (response.drugs && response.drugs.length > 0) {
                            response.drugs.forEach((drug, index) => {
                                $('#addDrug').click();
                                const $row = $('#drugsList .drug-row:last');
                                const $select = $row.find('.select2-drug');
                                if (drug.drug_id) {
                                    $select.append(new Option(
                                        `${drug.brand_name} (${drug.generic_name}) ${drug.strength}`,
                                        drug.drug_id,
                                        true,
                                        true
                                    )).trigger('change');
                                    $row.find('.drug-info').html(
                                        'Manufacturer: ' + (drug.manufacturer || 'N/A') + '<br>' +
                                        'Price: ' + (drug.price || 'N/A') + '<br>' +
                                        'Drug Class: ' + (drug.drug_class || 'N/A')
                                    );
                                    $row.data('generic_name', drug.generic_name);
                                    $row.data('strength', drug.strength);
                                    $row.data('current_brand_index', 0);
                                    $row.find('.drug-name-input').val('').hide();
                                } else {
                                    $select.val(null).trigger('change');
                                    $row.find('.drug-name-input').val(drug.drug_name).show();
                                }
                                $row.find('input[name$="[frequency]"]').val(drug.frequency || '');
                                $row.find('input[name$="[duration]"]').val(drug.duration || '');
                                $row.find('input[name$="[instructions]"]').val(drug.instructions || '');
                            });
                        }
                        ['chief_complaints', 'medical_history', 'examination_findings', 'diagnosis', 'investigation', 'advice'].forEach(field => {
                            if (response.template[field]) {
                                $(`textarea[name="${field}"]`).val(response.template[field]);
                            }
                        });
                    } else {
                        alert('Failed to load template: ' + (response.message || 'Unknown error'));
                    }
                });
            });

            if ($('#drugsList').children().length === 0) $('#addDrug').click();

            $('#savePatient').click(function() {
                const formData = $('#quickAddPatientForm').serialize();
                if (!$('#quickAddPatientForm input[name="name"]').val().trim()) {
                    alert('Patient name is required');
                    return;
                }

                $.ajax({
                    url: 'ajax_add_patient.php',
                    type: 'POST',
                    data: formData, // This now includes doctor_id
                    beforeSend: function() {
                        $('#savePatient').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
                    },
                    success: function(response) {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;
                        if (data.success) {
                            const newOption = new Option(
                                `${data.patient.name} (${data.patient.patient_uid}) - ${data.patient.age}y/${data.patient.sex}`,
                                data.patient.id,
                                true,
                                true
                            );
                            $('select[name="patient_id"]').append(newOption).trigger('change');
                            $('#addPatientModal').modal('hide');
                            $('#quickAddPatientForm')[0].reset();
                        } else {
                            alert(data.message || 'Error adding patient');
                        }
                    },
                    complete: function() {
                        $('#savePatient').prop('disabled', false).html('Save Patient');
                    },
                    error: function(xhr, status, error) {
                        alert('Error adding patient: ' + (xhr.responseText || 'Unknown error'));
                    }
                });
            });
        });
    </script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// QR Scanner - ক্যামেরা খুলবেই (মোবাইল + ডেস্কটপ)
let scannerActive = false;

document.getElementById('scanPatientModal').addEventListener('shown.bs.modal', function () {
    if (scannerActive) return;

    const html5QrCode = new Html5Qrcode("qr-reader-patient");

    const config = {
        fps: 10,
        qrbox: { width: 300, height: 300 },
        aspectRatio: 1,
        formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
    };

    // মোবাইলে ব্যাক ক্যামেরা, ডেস্কটপে ফ্রন্ট/ব্যাক যেটা পাবে
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        (decodedText) => {
            const cleanId = decodedText.trim().replace(/[^A-Za-z0-9]/g, '');

            fetch(`ajax_search_patient.php?q=${encodeURIComponent(cleanId)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.patients && data.patients.length > 0) {
                        const p = data.patients[0];
                        const select = document.querySelector('select[name="patient_id"]');

                        // Remove old selected
                        select.querySelectorAll('option').forEach(opt => opt.selected = false);

                        let found = false;
                        select.querySelectorAll('option').forEach(opt => {
                            if (opt.value == p.id) {
                                opt.selected = true;
                                found = true;
                            }
                        });

                        if (!found) {
                            const newOpt = new Option(
                                `${p.name} (${p.patient_uid}) - ${p.age}y/${p.sex}`,
                                p.id, true, true
                            );
                            select.appendChild(newOpt);
                        }

                        $(select).trigger('change'); // Select2 update
                        bootstrap.Modal.getInstance(this).hide();
                        alert('Patient Selected: ' + p.name);
                    } else {
                        alert('Patient not found: ' + cleanId);
                    }
                })
                .catch(() => alert('Scan failed. Try again.'));

            // Stop after successful scan
            setTimeout(() => {
                html5QrCode.stop();
                scannerActive = false;
            }, 1000);
        },
        (error) => {
            // Ignore scan errors
        }
    ).then(() => {
        scannerActive = true;
    }).catch(err => {
        alert("Camera Error: " + err + "\n\nOn mobile: Allow camera permission\nOn desktop: Use mobile device or HTTPS");
    });
});

// Stop scanner when modal closes
document.getElementById('scanPatientModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('qr-reader-patient').innerHTML = '';
    scannerActive = false;
});
</script>
<script>
function setupAutosuggest(fieldId, suggestionDivId) {
    const textarea = document.getElementById(fieldId);
    const suggestionsDiv = document.getElementById(suggestionDivId);

    textarea.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsDiv.innerHTML = '';
            suggestionsDiv.style.display = 'none';
            return;
        }

        fetch(`ajax_suggestions.php?field=${fieldId}&q=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.json();
            })
            .then(data => {
                console.log('Suggestions for ' + fieldId + ':', data); // কনসোলে দেখো কী আসছে

                suggestionsDiv.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(text => {
                        const div = document.createElement('div');
                        div.className = 'p-3 border-bottom';
                        div.style.cursor = 'pointer';
                        div.style.backgroundColor = '#fff';
                        div.onmouseover = () => div.style.backgroundColor = '#f0f0f0';
                        div.onmouseout = () => div.style.backgroundColor = '#fff';
                        div.textContent = text;
                        div.onclick = () => {
                            textarea.value = text;
                            suggestionsDiv.style.display = 'none';
                            textarea.focus();
                        };
                        suggestionsDiv.appendChild(div);
                    });
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.innerHTML = '<div class="p-3 text-muted">কোনো সাজেশন পাওয়া যায়নি</div>';
                    suggestionsDiv.style.display = 'block';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                suggestionsDiv.innerHTML = '<div class="p-3 text-danger">লোড করতে সমস্যা হয়েছে</div>';
                suggestionsDiv.style.display = 'block';
            });
    });

    // Hide when clicking outside
    document.addEventListener('click', function(e) {
        if (!textarea.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.style.display = 'none';
        }
    });

    // Hide on Escape key
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            suggestionsDiv.style.display = 'none';
        }
    });
}

// Initialize all fields
setupAutosuggest('chief_complaints', 'suggestions-chief_complaints');
setupAutosuggest('medical_history', 'suggestions-medical_history');
setupAutosuggest('examination_findings', 'suggestions-examination_findings');
setupAutosuggest('diagnosis', 'suggestions-diagnosis');
setupAutosuggest('investigation', 'suggestions-investigation');
setupAutosuggest('advice', 'suggestions-advice');
</script>PS

                            
</body>
</html>
