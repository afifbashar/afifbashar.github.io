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

// Handle form submission (unchanged logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (existing POST handling code remains unchanged)
    // (omitted for brevity - same as previous version)
}

// Fetch patients
$patients_result = !$is_template ? mysqli_query($conn, "SELECT id, patient_uid, name, age, sex FROM patients WHERE doctor_id = '$doctor_id' ORDER BY name") : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_template ? "Create Template" : "Create Prescription"; ?> - Prescription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --success: #06d6a0;
            --danger: #ef476f;
            --warning: #ffd166;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --border: #dee2e6;
        }
        body { background: #f1f3f5; font-family: 'Segoe UI', sans-serif; }
        .prescription-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .card-section {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            cursor: pointer;
            user-select: none;
        }
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            color: var(--primary);
            font-size: 1.15rem;
        }
        .card-header i.bx-chevron-down {
            transition: transform 0.3s ease;
        }
        .card-header[aria-expanded="true"] i.bx-chevron-down {
            transform: rotate(180deg);
        }
        .drug-row {
            background: #f9fbfd;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }
        .drug-row:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid var(--border);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(67,97,238,0.2);
        }
        textarea.form-control { min-height: 100px; }
        .btn {
            border-radius: 8px;
            padding: 9px 16px;
            font-weight: 500;
        }
        .btn i { font-size: 1.1rem; }
        .btn-primary { background: var(--primary); border: none; }
        .btn-primary:hover { background: #3a56d4; }
        .btn-success { background: var(--success); border: none; }
        .btn-outline-primary:hover { background: var(--primary); color: white; }
        .drug-info {
            font-size: 0.88rem;
            color: #6c757d;
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--border);
            margin-top: 8px;
        }
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border);
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1050;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            display: none;
        }
        .suggestion-item {
            padding: 10px 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .suggestion-item:hover { background: #f0f4ff; }
        .edd-calculator {
            background: #f0f8ff;
            padding: 16px;
            border-radius: 10px;
            border: 1px dashed var(--primary);
            margin-top: 16px;
        }
        @media (max-width: 992px) {
            .prescription-container { margin: 15px; padding: 20px; border-radius: 12px; }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="prescription-container">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="mb-1"><?php echo $is_template ? "Create Prescription Template" : "Create New Prescription"; ?></h2>
                <p class="text-muted mb-0">Complete patient information and treatment details</p>
            </div>
            <div class="col-auto">
                <a href="prescriptions.php" class="btn btn-outline-secondary">
                    <i class='bx bx-arrow-back'></i> Back
                </a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class='bx bx-error-circle'></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . ($is_template ? "?template=1" : "")) ?>">
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <!-- Patient / Template Details -->
                    <div class="card card-section mb-4">
                        <div class="card-header">
                            <h5><i class='bx bx-user'></i> <?= $is_template ? "Template Details" : "Patient Details" ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if ($is_template): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Template Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Select Patient <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="patient_id" class="form-select select2-patient" required>
                                            <option value="">Choose patient...</option>
                                            <?php while ($row = mysqli_fetch_assoc($patients_result)): ?>
                                                <option value="<?= $row['id'] ?>">
                                                    <?= htmlspecialchars("{$row['name']} ({$row['patient_uid']}) - {$row['age']}y/{$row['sex']}") ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scanPatientModal">
                                            <i class='bx bx-qr-scan'></i>
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Load Previous Prescription</label>
                                    <select class="form-select select2-prescription">
                                        <option value="">Search by name or date...</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Vital Signs -->
                    <div class="card card-section mb-4">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#vitalsCollapse">
                            <h5><i class='bx bx-heart'></i> Vital Signs</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="collapse show" id="vitalsCollapse">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6"><label>BP</label><input type="text" name="bp" class="form-control" value="<?= htmlspecialchars($form_data['bp'] ?? ($template_data['bp'] ?? '')) ?>"></div>
                                    <div class="col-md-6"><label>Pulse</label><input type="text" name="pulse" class="form-control" value="<?= htmlspecialchars($form_data['pulse'] ?? ($template_data['pulse'] ?? '')) ?>"></div>
                                    <div class="col-md-6"><label>Temperature</label><input type="text" name="temperature" class="form-control" value="<?= htmlspecialchars($form_data['temperature'] ?? ($template_data['temperature'] ?? '')) ?>"></div>
                                    <div class="col-md-6"><label>SpO2</label><input type="text" name="spo2" class="form-control" value="<?= htmlspecialchars($form_data['spo2'] ?? ($template_data['spo2'] ?? '')) ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaints & History -->
                    <div class="card card-section mb-4">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#complaintsCollapse">
                            <h5><i class='bx bx-file'></i> Complaints & History</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="collapse show" id="complaintsCollapse">
                            <div class="card-body">
                                <!-- Chief Complaints -->
                                <div class="mb-3 position-relative">
                                    <label class="form-label fw-bold">Chief Complaints <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                        <select class="form-select select2-template" id="chiefComplaintTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['chief_complaint'] as $t): ?>
                                                <option value="<?= htmlspecialchars($t['content']) ?>"><?= htmlspecialchars($t['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="insertChiefComplaintTemplate"><i class='bx bx-paste'></i></button>
                                    </div>
                                    <textarea name="chief_complaints" id="chief_complaints" class="form-control" rows="3"><?= htmlspecialchars($form_data['chief_complaints'] ?? ($template_data['chief_complaints'] ?? '')) ?></textarea>
                                    <div id="suggestions-chief_complaints" class="suggestions-dropdown"></div>
                                </div>

                                <!-- Medical History -->
                                <div class="mb-3 position-relative">
                                    <label class="form-label">Medical History</label>
                                    <div class="input-group mb-2">
                                        <select id="medicalHistoryTemplates" class="form-select select2-template">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['medical_history'] as $t): ?>
                                                <option value="<?= htmlspecialchars($t['content']) ?>"><?= htmlspecialchars($t['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="insertMedicalHistoryTemplate"><i class='bx bx-paste'></i></button>
                                    </div>
                                    <textarea name="medical_history" id="medical_history" class="form-control" rows="3"><?= htmlspecialchars($form_data['medical_history'] ?? ($template_data['medical_history'] ?? '')) ?></textarea>
                                    <div id="suggestions-medical_history" class="suggestions-dropdown"></div>
                                </div>

                                <!-- Examination Findings + EDD Calculator -->
                                <div class="mb-3 position-relative">
                                    <label class="form-label">Examination Findings</label>
                                    <div class="input-group mb-2">
                                        <select id="examinationFindingsTemplates" class="form-select select2-template">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['examination_findings'] as $t): ?>
                                                <option value="<?= htmlspecialchars($t['content']) ?>"><?= htmlspecialchars($t['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="insertExaminationFindingsTemplate"><i class='bx bx-paste'></i></button>
                                    </div>
                                    <textarea name="examination_findings" id="examination_findings" class="form-control" rows="4"><?= htmlspecialchars($form_data['examination_findings'] ?? ($template_data['examination_findings'] ?? '')) ?></textarea>
                                    <div id="suggestions-examination_findings" class="suggestions-dropdown"></div>

                                    <!-- EDD Calculator -->
                                    <div class="edd-calculator mt-3">
                                        <h6 class="mb-3"><i class='bx bx-calendar-event'></i> EDD Calculator</h6>
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-8">
                                                <label class="form-label">LMP Date</label>
                                                <input type="date" id="lmp_date" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" id="calculate_edd" class="btn btn-primary w-100">
                                                    <i class='bx bx-calculator'></i> Calculate & Insert EDD
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Diagnosis & Investigation -->
                    <div class="card card-section mb-4">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#diagnosisCollapse">
                            <h5><i class='bx bx-diagnose'></i> Diagnosis & Investigation</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="collapse show" id="diagnosisCollapse">
                            <div class="card-body">
                                <div class="mb-3 position-relative">
                                    <label class="form-label fw-bold">Diagnosis <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                        <select class="form-select select2-template" id="diagnosisTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['diagnosis'] as $t): ?>
                                                <option value="<?= htmlspecialchars($t['content']) ?>"><?= htmlspecialchars($t['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="insertDiagnosisTemplate"><i class='bx bx-paste'></i></button>
                                    </div>
                                    <textarea name="diagnosis" id="diagnosis" class="form-control" rows="3" required><?= htmlspecialchars($form_data['diagnosis'] ?? ($template_data['diagnosis'] ?? '')) ?></textarea>
                                    <div id="suggestions-diagnosis" class="suggestions-dropdown"></div>
                                </div>
                                <div class="mb-3 position-relative">
                                    <label class="form-label">Investigations</label>
                                    <div class="input-group mb-2">
                                        <select class="form-select select2-template" id="investigationTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['investigation'] as $t): ?>
                                                <option value="<?= htmlspecialchars($t['content']) ?>"><?= htmlspecialchars($t['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="insertInvestigationTemplate"><i class='bx bx-paste'></i></button>
                                    </div>
                                    <textarea name="investigation" id="investigation" class="form-control" rows="3"><?= htmlspecialchars($form_data['investigation'] ?? ($template_data['investigation'] ?? '')) ?></textarea>
                                    <div id="suggestions-investigation" class="suggestions-dropdown"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Template -->
                    <div class="card card-section mb-4">
                        <div class="card-header">
                            <h5><i class='bx bx-bookmark'></i> Treatment Template</h5>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <select class="form-select select2-template" id="treatmentTemplates">
                                    <option value="">Choose a treatment template...</option>
                                    <?php
                                    $sql = "SELECT id, name FROM treatment_templates WHERE doctor_id = ? OR is_default = 1";
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
                                <button type="button" class="btn btn-outline-primary" id="insertTreatmentTemplate"><i class='bx bx-paste'></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Advice & Follow-up -->
                    <div class="card card-section mb-4">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#adviceCollapse">
                            <h5><i class='bx bx-message-square-detail'></i> Advice & Follow-up</h5>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                        <div class="collapse show" id="adviceCollapse">
                            <div class="card-body">
                                <div class="mb-3 position-relative">
                                    <label class="form-label">Advice</label>
                                    <div class="input-group mb-2">
                                        <select class="form-select select2-template" id="adviceTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['advice'] as $t): ?>
                                                <option value="<?= htmlspecialchars($t['content']) ?>"><?= htmlspecialchars($t['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="insertAdviceTemplate"><i class='bx bx-paste'></i></button>
                                    </div>
                                    <textarea name="advice" id="advice" class="form-control" rows="3"><?= htmlspecialchars($form_data['advice'] ?? ($template_data['advice'] ?? '')) ?></textarea>
                                    <div id="suggestions-advice" class="suggestions-dropdown"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Next Visit</label>
                                    <input type="date" name="next_visit" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicines Section (Full Width) -->
            <div class="card card-section">
                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#drugsCollapse">
                    <h5><i class='bx bx-capsule'></i> Prescribed Medicines</h5>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="collapse show" id="drugsCollapse">
                    <div class="card-body">
                        <div id="drugsList">
                            <?php if (!empty($template_drugs)) {
                                foreach ($template_drugs as $index => $drug) {
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

    <!-- Modals and Templates (unchanged - Add Patient, QR Scanner, Drug Row Template) -->
    <!-- (Include all modals and <template id="drugRowTemplate"> from previous version) -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select2 initialization
            $('.select2-patient, .select2-template, .select2-prescription, .select2-drug').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // EDD Calculator
            $('#calculate_edd').on('click', function() {
                const lmp = $('#lmp_date').val();
                if (!lmp) return alert('Please select LMP date');
                const edd = new Date(new Date(lmp).getTime() + 280 * 24 * 60 * 60 * 1000);
                const eddStr = edd.toLocaleDateString('en-GB');
                const current = $('#examination_findings').val();
                $('#examination_findings').val(current + (current ? "\n" : "") + "EDD: " + eddStr);
            });

            // Autosuggestion setup (including frequency, duration, instructions)
            function setupAutosuggest(inputId, dropdownId, fieldType = null) {
                const input = document.getElementById(inputId);
                const dropdown = document.getElementById(dropdownId);
                if (!input || !dropdown) return;

                input.addEventListener('input', function() {
                    const q = this.value.trim();
                    if (q.length < 2) { dropdown.style.display = 'none'; return; }

                    fetch(`ajax_suggestions.php?field=${fieldType || inputId}&q=${encodeURIComponent(q)}`)
                        .then(r => r.json())
                        .then(data => {
                            dropdown.innerHTML = '';
                            if (data.length) {
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'suggestion-item';
                                    div.textContent = item;
                                    div.onclick = () => { input.value = item; dropdown.style.display = 'none'; };
                                    dropdown.appendChild(div);
                                });
                                dropdown.style.display = 'block';
                            } else dropdown.style.display = 'none';
                        });
                });

                document.addEventListener('click', e => {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = 'none';
                });
            }

            // Initialize all autosuggestions
            ['chief_complaints', 'medical_history', 'examination_findings', 'diagnosis', 'investigation', 'advice'].forEach(f => 
                setupAutosuggest(f, `suggestions-${f}`, f)
            );

            // Drug row autosuggestions will be set dynamically on add

            // Add Drug with autosuggest
            let drugCounter = $('#drugsList .drug-row').length;
            $('#addDrug').on('click', function() {
                const html = $('#drugRowTemplate').html().replace(/INDEX/g, drugCounter);
                $('#drugsList').append(html);
                $(`#drugsList .drug-row:last .select2-drug`).select2({ theme: 'bootstrap-5', width: '100%' });
                // Setup autosuggest for new row
                setupAutosuggest('frequency-' + drugCounter, 'suggestions-frequency-' + drugCounter, 'frequency');
                setupAutosuggest('duration-' + drugCounter, 'suggestions-duration-' + drugCounter, 'duration');
                setupAutosuggest('instructions-' + drugCounter, 'suggestions-instructions-' + drugCounter, 'instruction');
                drugCounter++;
            });

            // Initial drug row if empty
            if (!$('#drugsList').children().length) $('#addDrug').click();

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
