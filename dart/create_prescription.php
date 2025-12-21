<?php
require_once 'config.php';
requireLogin();
$doctor_id = $_SESSION['user_id'];
$success = $error = '';
$is_template = isset($_GET['template']) && $_GET['template'] == '1';
$from_template = isset($_GET['from_template']) ? (int)$_GET['from_template'] : 0;
// Timezone set করো - Bangladesh এর জন্য
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
            }
        }
    }
}

// Load all templates - নতুন দুটি যোগ করা হয়েছে
$template_types = [
    'frequency' => 'frequency_templates',
    'duration' => 'duration_templates',
    'instruction' => 'instruction_templates',
    'diagnosis' => 'diagnosis_templates',
    'chief_complaint' => 'chief_complaint_templates',
    'medical_history' => 'medical_history_templates',          // নতুন
    'examination_findings' => 'examination_findings_templates', // নতুন
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $is_template ? null : (isset($_POST['patient_id']) ? (int)$_POST['patient_id'] : null);
    $title = $is_template ? trim($_POST['title']) : null;
    $drugs = isset($_POST['drugs']) ? $_POST['drugs'] : [];

    $form_data = [
        'bp' => trim($_POST['bp'] ?? ''),
        'pulse' => trim($_POST['pulse'] ?? ''),
        'temperature' => trim($_POST['temperature'] ?? ''),
        'spo2' => trim($_POST['spo2'] ?? ''),
        'chief_complaints' => trim($_POST['chief_complaints'] ?? ''),
        'medical_history' => trim($_POST['medical_history'] ?? ''),
        'examination_findings' => trim($_POST['examination_findings'] ?? ''),
        'diagnosis' => trim($_POST['diagnosis'] ?? ''),
        'investigation' => trim($_POST['investigation'] ?? ''),
        'advice' => trim($_POST['advice'] ?? ''),
        'next_visit' => trim($_POST['next_visit'] ?? ''),
        'drugs' => $drugs
    ];

    $error = '';
    if (!$is_template && empty($patient_id)) {
        $error = "Please select a patient";
    } elseif ($is_template && empty($title)) {
        $error = "Template title is required";
    } elseif (empty($form_data['chief_complaints'])) {
        $error = "Chief complaints are required";
    } elseif (empty($form_data['diagnosis'])) {
        $error = "Diagnosis is required";
    }

    if (empty($error)) {
        mysqli_begin_transaction($conn);
        try {
            $sql = "INSERT INTO prescriptions (
                        patient_id, doctor_id, title, bp, pulse, temperature, spo2, 
                        chief_complaints, medical_history, examination_findings, 
                        diagnosis, investigation, advice, next_visit, is_template
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "iissssssssssssi", 
                    $patient_id, $_SESSION['user_id'], $title,
                    $form_data['bp'], $form_data['pulse'], $form_data['temperature'], $form_data['spo2'],
                    $form_data['chief_complaints'], $form_data['medical_history'], $form_data['examination_findings'],
                    $form_data['diagnosis'], $form_data['investigation'], $form_data['advice'], $form_data['next_visit'],
                    $is_template
                );
                if (!mysqli_stmt_execute($stmt)) throw new Exception("Error saving prescription: " . mysqli_error($conn));
                
                $prescription_id = mysqli_insert_id($conn);
                
                if (!empty($drugs)) {
                    $drug_sql = "INSERT INTO prescription_drugs (
                                prescription_id, drug_id, drug_name, frequency, duration, instructions
                            ) VALUES (?, ?, ?, ?, ?, ?)";
                    if ($drug_stmt = mysqli_prepare($conn, $drug_sql)) {
                        foreach ($drugs as $drug) {
                            $drug_id = !empty($drug['drug_id']) && is_numeric($drug['drug_id']) ? (int)$drug['drug_id'] : null;
                            $drug_name = !empty($drug['drug_name']) ? trim($drug['drug_name']) : null;
                            if (!$drug_id && !$drug_name) continue;
                            mysqli_stmt_bind_param($drug_stmt, "isssss", 
                                $prescription_id, $drug_id, $drug_name, $drug['frequency'], $drug['duration'], $drug['instructions']
                            );
                            if (!mysqli_stmt_execute($drug_stmt)) throw new Exception("Error saving drug: " . mysqli_error($conn));
                        }
                    }
                }
                
                mysqli_commit($conn);
                header("Location: " . ($is_template ? "manage_templates.php?type=prescription&success=1" : "view_prescription.php?id=" . $prescription_id));
                exit;
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
}

// Fetch patients
$patients_result = !$is_template ? mysqli_query($conn, "SELECT id, patient_uid, name, age, sex FROM patients WHERE doctor_id = '$doctor_id' ORDER BY name") : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?php echo $is_template ? "Create Template" : "Create Prescription"; ?> - Prescription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .prescription-container { max-width: 1200px; margin: 20px auto; padding: 0 15px; }
        .card-section { margin-bottom: 15px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--background-color); }
        .card-header { padding: 10px 15px; border-bottom: 1px solid var(--border-color); cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: var(--background-color); }
        .card-header h5 { margin: 0; font-size: 1.1rem; font-weight: 500; }
        .drug-row { border: 1px solid var(--border-color); background-color: var(--background-color); padding: 15px; border-radius: 8px; margin-bottom: 15px; transition: all 0.3s ease; }
        .drug-row:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .input-group-text { background: transparent; border-right: none; padding-right: 0; }
        .form-control, .form-select { border-left: none; padding-left: 0; font-size: 1.05rem; height: 2.75rem; }
        .form-control:focus { border-color: var(--border-color); box-shadow: none; }
        .input-group:focus-within { box-shadow: 0 0 0 3px rgba(52,152,219,0.25); border-radius: 6px; }
        textarea.form-control { height: 6rem; font-size: 1.05rem; }
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single { height: 2.75rem; border: 1px solid var(--border-color); }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 2.75rem; font-size: 1.05rem; }
        .drug-info { font-size: 0.9rem; color: #6c757d; margin-top: 5px; }
        .select2-results__options { max-height: 200px; overflow-y: auto; }
        .modal-body select { max-height: 200px; overflow-y: auto; width: 100%; }

        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .prescription-container { padding: 0 10px; }
            .drug-row { padding: 10px; }
            .input-group { flex-wrap: wrap; }
            .select2-container { width: 100% !important; min-width: 100%; }
            .select2-selection__rendered { padding-right: 30px; font-size: 0.95rem; }
            .select2-container--default .select2-selection--single { height: 2.5rem; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 2.5rem; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 2.5rem; }
            .btn { font-size: 0.9rem; padding: 0.5rem 0.75rem; }
            .form-control, .form-select { font-size: 0.95rem; height: 2.5rem; }
            .input-group .btn { flex-shrink: 0; }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="prescription-container">
        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-between align-items-center mb-3">
                    <div class="col-auto">
                        <h2 class="mb-1"><?php echo $is_template ? "Create Prescription Template" : "Create New Prescription"; ?></h2>
                        <p class="text-muted mb-0">Enter prescription details below</p>
                    </div>
                    <div class="col-auto">
                        <a href="prescriptions.php" class="btn btn-outline-primary">
                            <i class='bx bx-arrow-back me-1'></i> Back
                        </a>
                    </div>
                </div>

                <?php if ($error) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class='bx bx-error-circle me-2'></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                <?php if ($success) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class='bx bx-check-circle me-2'></i> <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($is_template ? "?template=1" : "")); ?>">
                    <div class="card card-section">
                        <div class="card-header">
                            <h5><i class='bx bx-user me-2'></i><?php echo $is_template ? "Template Details" : "Patient Details"; ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if ($is_template) { ?>
                                <div class="mb-3">
                                    <label class="form-label">Template Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>" autofocus>
                                </div>
                            <?php } else { ?>
                                <div class="mb-3">
                                    <label class="form-label">Select Patient <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="patient_id" class="form-select select2-patient" required>
                                            <option value="">Select Patient</option>
                                            <?php while ($row = mysqli_fetch_assoc($patients_result)) { ?>
                                                <option value="<?php echo $row['id']; ?>">
                                                    <?php echo htmlspecialchars("{$row['name']} ({$row['patient_uid']}) - {$row['age']}y/{$row['sex']}"); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <!-- ADD THIS AFTER YOUR PATIENT SELECT DROPDOWN -->
<div class="col-md-2">
    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#scanPatientModal">
        Scan QR
    </button>
</div>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                                            Add New
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Load Previous Prescription</label>
                                    <select class="form-control select2-prescription" name="load_prescription">
                                        <option value="">Search by patient name or date...</option>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                  
                    <div class="card card-section">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#complaintsCollapse">
                            <h5><i class='bx bx-file me-2'></i>Complaints & History</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="card-body collapse show" id="complaintsCollapse">
                            <div class="mb-3">
                                <label class="form-label">Chief Complaints <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select select2-template" id="chiefComplaintTemplates">
                                        <option value="">Select Template</option>
                                        <?php foreach ($templates['chief_complaint'] as $template) { ?>
                                            <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="insertChiefComplaintTemplate">Insert</button>
                                </div>
                                   <textarea name="chief_complaints" id="chief_complaints" class="form-control" rows="3" placeholder="Chief Complaints..."><?php echo htmlspecialchars($form_data['chief_complaints'] ?? ($template_data['chief_complaints'] ?? '')); ?></textarea>
                                  <div id="suggestions-chief_complaints" class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" style="max-height: 200px; overflow-y: auto; z-index: 1000; display: none;"></div>
                            </div>
                         
                            <div class="mb-3">
                <label class="form-label">Medical History</label>
                <div class="mb-2">
                    <select id="medicalHistoryTemplates" class="form-select form-select-sm select2-template">
                        <option value="">Select Template...</option>
                        <?php foreach ($templates['medical_history'] as $tmpl): ?>
                            <option value="<?php echo htmlspecialchars($tmpl['content']); ?>">
                                <?php echo htmlspecialchars($tmpl['name']); ?> <?php if ($tmpl['is_default']): ?>(Default)<?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="insertMedicalHistoryTemplate">
                        <i class="bx bx-copy"></i> Insert Template
                    </button>
                </div>
                <textarea name="medical_history" id="medical_history" class="form-control" rows="5"><?php echo htmlspecialchars($form_data['medical_history']); ?></textarea>
                                <div id="suggestions-medical_history" class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" style="max-height: 200px; overflow-y: auto; z-index: 1000; display: none;"></div>
            </div>

            <!-- Examination Findings - নতুন যোগ করা -->
            <div class="mb-3">
                <label class="form-label">Examination Findings</label>
                <div class="mb-2">
                    <select id="examinationFindingsTemplates" class="form-select form-select-sm select2-template">
                        <option value="">Select Template...</option>
                        <?php foreach ($templates['examination_findings'] as $tmpl): ?>
                            <option value="<?php echo htmlspecialchars($tmpl['content']); ?>">
                                <?php echo htmlspecialchars($tmpl['name']); ?> <?php if ($tmpl['is_default']): ?>(Default)<?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="insertExaminationFindingsTemplate">
                        <i class="bx bx-copy"></i> Insert Template
                    </button>
                </div>
                <textarea name="examination_findings" class="form-control" rows="5"><?php echo htmlspecialchars($form_data['examination_findings']); ?></textarea>
            </div>
                        </div>
                    </div>
  <div class="card card-section">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#vitalsCollapse">
                            <h5><i class='bx bx-heart me-2'></i>Vital Signs</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="card-body collapse show" id="vitalsCollapse">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">BP</label>
                                    <input type="text" name="bp" class="form-control" value="<?php echo htmlspecialchars($form_data['bp'] ?? ($template_data['bp'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Pulse</label>
                                    <input type="text" name="pulse" class="form-control" value="<?php echo htmlspecialchars($form_data['pulse'] ?? ($template_data['pulse'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Temperature</label>
                                    <input type="text" name="temperature" class="form-control" value="<?php echo htmlspecialchars($form_data['temperature'] ?? ($template_data['temperature'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">SpO2</label>
                                    <input type="text" name="spo2" class="form-control" value="<?php echo htmlspecialchars($form_data['spo2'] ?? ($template_data['spo2'] ?? '')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-section">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#diagnosisCollapse">
                            <h5><i class='bx bx-diagnose me-2'></i>Diagnosis & Investigation</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="card-body collapse show" id="diagnosisCollapse">
                            <div class="mb-3">
                                <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select select2-template" id="diagnosisTemplates">
                                        <option value="">Select Template</option>
                                        <?php foreach ($templates['diagnosis'] as $template) { ?>
                                            <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="insertDiagnosisTemplate">Insert</button>
                                </div>
                                <textarea class="form-control mt-2" name="diagnosis" rows="3" required><?php echo htmlspecialchars($form_data['diagnosis'] ?? ($template_data['diagnosis'] ?? '')); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Investigations</label>
                                <div class="input-group">
                                    <select class="form-select select2-template" id="investigationTemplates">
                                        <option value="">Select Template</option>
                                        <?php foreach ($templates['investigation'] as $template) { ?>
                                            <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="insertInvestigationTemplate">Insert</button>
                                </div>
                                <textarea class="form-control mt-2" name="investigation" rows="3"><?php echo htmlspecialchars($form_data['investigation'] ?? ($template_data['investigation'] ?? '')); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card card-section">
                        <div class="card-header">
                            <h5><i class='bx bx-bookmark me-2'></i>Treatment Templates</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Treatment Template</label>
                                <div class="input-group">
                                    <select class="form-select select2-template" id="treatmentTemplates">
                                        <option value="">Select Template</option>
                                        <?php
                                        $sql = "SELECT id, name FROM treatment_templates WHERE (doctor_id = ? OR is_default = 1)";
                                        if ($stmt = mysqli_prepare($conn, $sql)) {
                                            mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" id="insertTreatmentTemplate">Insert</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-section">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#drugsCollapse">
                            <h5><i class='bx bx-capsule me-2'></i>Prescribed Medicines</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="card-body collapse show" id="drugsCollapse">
                            <div id="drugsList">
                                <?php if (!empty($template_drugs)) {
                                    foreach ($template_drugs as $index => $drug) { ?>
                                        <div class="drug-row">
                                            <div class="row g-3">
                                                <div class="col-md-12 mb-2">
                                                    <label class="form-label">Drug <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-control select2-drug" name="drugs[<?php echo $index; ?>][drug_id]">
                                                            <?php if ($drug['drug_id']) { ?>
                                                                <option value="<?php echo $drug['drug_id']; ?>" 
                                                                        selected 
                                                                        data-manufacturer="<?php echo htmlspecialchars($drug['manufacturer']); ?>"
                                                                        data-price="<?php echo htmlspecialchars($drug['price']); ?>"
                                                                        data-drug-class="<?php echo htmlspecialchars($drug['drug_class']); ?>">
                                                                    <?php echo htmlspecialchars("{$drug['brand_name']} ({$drug['generic_name']}) {$drug['strength']}"); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="text" class="form-control drug-name-input" name="drugs[<?php echo $index; ?>][drug_name]" value="<?php echo htmlspecialchars($drug['drug_name'] ?? ''); ?>" placeholder="Enter drug name if not found">
                                                        <button type="button" class="btn btn-outline-primary change-brand-btn">Change Brand</button>
                                                        <button type="button" class="btn btn-outline-info select-manufacturer-btn">Select Manufacturer</button>
                                                    </div>
                                                    <?php if ($drug['drug_id']) { ?>
                                                        <div class="drug-info">
                                                            Manufacturer: <?php echo htmlspecialchars($drug['manufacturer'] ?: 'N/A'); ?><br>
                                                            Price: <?php echo htmlspecialchars($drug['price'] ?: 'N/A'); ?><br>
                                                            Drug Class: <?php echo htmlspecialchars($drug['drug_class'] ?: 'N/A'); ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="drug-info">
                                                            Manufacturer: N/A<br>
                                                            Price: N/A<br>
                                                            Drug Class: N/A
                                                        </div>
                                                    <?php } ?>
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
                                                        <input type="text" class="form-control" name="drugs[<?php echo $index; ?>][frequency]" value="<?php echo htmlspecialchars($drug['frequency']); ?>" required>
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
                                                        <input type="text" class="form-control" name="drugs[<?php echo $index; ?>][duration]" value="<?php echo htmlspecialchars($drug['duration']); ?>" required>
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
                                                        <input type="text" class="form-control" name="drugs[<?php echo $index; ?>][instructions]" value="<?php echo htmlspecialchars($drug['instructions']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-drug">Remove</button>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="addDrug">Add Medicine</button>
                        </div>
                    </div>

                    <div class="card card-section">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#adviceCollapse">
                            <h5><i class='bx bx-message-square-detail me-2'></i>Advice & Follow-up</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="card-body collapse show" id="adviceCollapse">
                            <div class="mb-3">
                                <label class="form-label">Advice</label>
                                <div class="input-group">
                                    <select class="form-select select2-template" id="adviceTemplates">
                                        <option value="">Select Template</option>
                                        <?php foreach ($templates['advice'] as $template) { ?>
                                            <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' (Default)' : '')); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="insertAdviceTemplate">Insert</button>
                                </div>
                                <textarea class="form-control mt-2" name="advice" rows="3"><?php echo htmlspecialchars($form_data['advice'] ?? ($template_data['advice'] ?? '')); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Next Visit</label>
                                <?php $today = date('Y-m-d'); ?>
<input type="date" name="next_visit" class="form-control" min="<?php echo $today; ?>" value="<?php echo htmlspecialchars($form_data['next_visit'] ?? $today); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="prescriptions.php" class="btn btn-light"><i class='bx bx-x me-1'></i>Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class='bx bx-save me-1'></i>Save <?php echo $is_template ? "Template" : "Prescription"; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
</script>
                    
    
</body>
</html>