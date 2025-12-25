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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $is_template ? "Create Template" : "Create Prescription"; ?> - Prescription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
            color: #212529;
            line-height: 1.5;
            font-size: 14px;
        }

        .prescription-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .page-header {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #6c757d;
            font-size: 13px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        /* Alerts */
        .alert-modern {
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger-modern {
            background: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }

        /* Cards */
        .card-modern {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .card-header-modern {
            background: #f8f9fa;
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            cursor: pointer;
        }

        .card-header-modern h5 {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin: 0;
        }

        .card-body-modern {
            padding: 15px;
        }

        /* Forms */
        .form-group-modern {
            margin-bottom: 15px;
        }

        .form-label-modern {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 13px;
            color: #495057;
        }

        .form-control-modern {
            width: 100%;
            padding: 8px 12px;
            font-size: 14px;
            line-height: 1.5;
            color: #495057;
            background: white;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }

        textarea.form-control-modern {
            min-height: 80px;
            resize: vertical;
        }

        /* Buttons */
        .btn-modern {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary-modern {
            color: white;
            background: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary-modern:hover {
            background: #0b5ed7;
        }

        .btn-secondary-modern {
            color: white;
            background: #6c757d;
            border-color: #6c757d;
        }

        .btn-success-modern {
            color: white;
            background: #198754;
            border-color: #198754;
        }

        .btn-outline-modern {
            color: #0d6efd;
            background: transparent;
            border-color: #0d6efd;
        }

        .btn-sm-modern {
            padding: 5px 10px;
            font-size: 13px;
        }

        /* Layout */
        .layout-columns {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        @media (min-width: 992px) {
            .layout-columns {
                grid-template-columns: 300px 1fr;
            }
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        @media (min-width: 576px) {
            .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        @media (min-width: 768px) {
            .grid-3 {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        @media (min-width: 768px) {
            .grid-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Drug Row */
        .drug-row-modern {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .drug-row-number {
            position: absolute;
            top: -10px;
            left: 10px;
            background: #0d6efd;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .remove-drug {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        .drug-info-modern {
            background: #e7f1ff;
            padding: 8px;
            margin-top: 10px;
            font-size: 12px;
            border-radius: 4px;
        }

        /* Sticky Footer */
        .sticky-action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 15px;
            z-index: 1000;
        }

        .sticky-action-bar .actions {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Suggestions Dropdown */
        .suggestions-dropdown {
            position: absolute;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0 0 4px 4px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            width: 100%;
            display: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 13px;
            border-bottom: 1px solid #f8f9fa;
        }

        .suggestion-item:hover,
        .suggestion-item.focused {
            background: #f8f9fa;
        }

        /* Modals */
        .modal-modern .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-modern .modal-title {
            font-size: 18px;
            font-weight: 600;
        }

        .modal-modern .modal-body {
            padding: 20px;
        }

        /* Icons */
        .bx {
            font-size: 18px;
            vertical-align: middle;
        }

        /* Utilities */
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-2 { margin-top: 8px; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .gap-2 { gap: 8px; }
        .w-100 { width: 100%; }
        .text-danger { color: #dc3545; }
        .text-muted { color: #6c757d; }
        .required { color: #dc3545; }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 4px;
        }

        /* Calculator Section */
        .calculator-section {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }

        .calculator-section h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* QR Scanner */
        .qr-scanner-container {
            background: #000;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Select2 Override */
        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
        }

        /* Collapse Icon */
        .toggle-icon {
            float: right;
            transition: transform 0.2s;
        }

        .collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        /* Template Selector */
        .template-selector {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .template-selector select {
            flex: 1;
        }

        /* Print Hide */
        @media print {
            .sticky-action-bar,
            .page-header,
            .btn-modern {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="prescription-wrapper">
        <!-- Page Header -->
        <div class="page-header fade-in">
            <div>
                <h1>
                    <i class='bx bx-file-blank'></i>
                    <?php echo $is_template ? "Create Template" : "New Prescription"; ?>
                </h1>
                <p>Fill in the details below to create a <?php echo $is_template ? "template" : "prescription"; ?></p>
            </div>
            <div class="header-actions">
                <a href="prescriptions.php" class="btn-modern btn-secondary-modern">
                    <i class='bx bx-arrow-back'></i>
                    <span class="d-none d-sm-inline">Back</span>
                </a>
            </div>
        </div>
<div class="card-modern mb-3" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border: 2px solid #a5b4fc;">
    <div class="card-body-modern py-3">
        <p class="mb-0 text-center" style="font-size: 0.875rem; color: #4f46e5;">
            <strong>🚀 Keyboard Shortcuts:</strong> 
            Ctrl+M (Add Drug) • Ctrl+S (Save) • Ctrl+1..6 (Jump to section) • Alt+T (Treatment Template) • Alt+P (Patient) • Esc (Close dropdowns)
        </p>
    </div>
</div>
        <!-- Alerts -->
        <?php if ($error): ?>
            <div class="alert-modern alert-danger-modern slide-up">
                <i class='bx bx-error-circle bx-sm'></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-modern alert-success-modern slide-up">
                <i class='bx bx-check-circle bx-sm'></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <!-- Main Form -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($is_template ? "?template=1" : "")); ?>" id="prescriptionForm">
            <div class="main-columns">
                <!-- Left Column -->
                <div class="left-column">
                    <!-- Patient/Template Section -->
                    <div class="card-modern slide-up">
                        <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#patientSection">
                            <h5>
                                <i class='bx bx-user-circle'></i>
                                <?php echo $is_template ? "Template Details" : "Patient Selection"; ?>
                            </h5>
                            <i class='bx bx-chevron-down toggle-icon'></i>
                        </div>
                        <div class="collapse show" id="patientSection">
                            <div class="card-body-modern">
                                <?php if ($is_template): ?>
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Template Title <span class="required">*</span>
                                        </label>
                                        <input type="text" name="title" class="form-control-modern" required 
                                               value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>" 
                                               placeholder="Enter template name..." autofocus>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Select Patient <span class="required">*</span>
                                        </label>
                                        <div class="input-group-modern">
                                            <select name="patient_id" class="form-control-modern select2-patient" required>
                                                <option value="">Choose a patient...</option>
                                                <?php while ($row = mysqli_fetch_assoc($patients_result)): ?>
                                                    <option value="<?php echo $row['id']; ?>">
                                                        <?php echo htmlspecialchars("{$row['name']} ({$row['patient_uid']}) - {$row['age']}y/{$row['sex']}"); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                            <div class="quick-actions">
                                                <button type="button" class="btn-modern btn-success-modern btn-icon" 
                                                        data-bs-toggle="modal" data-bs-target="#scanPatientModal"
                                                        title="Scan QR Code">
                                                    <i class='bx bx-qr-scan'></i>
                                                </button>
                                                <button type="button" class="btn-modern btn-primary-modern btn-icon"
                                                        data-bs-toggle="modal" data-bs-target="#addPatientModal"
                                                        title="Add New Patient">
                                                    <i class='bx bx-plus'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">Load Previous Prescription</label>
                                        <select class="form-control-modern select2-prescription" name="load_prescription">
                                            <option value="">Search prescriptions...</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Vital Signs -->
                    <div class="card-modern slide-up" style="animation-delay: 0.1s">
                        <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#vitalsSection">
                            <h5>
                                <i class='bx bx-heart'></i>
                                Vital Signs
                            </h5>
                            <i class='bx bx-chevron-down toggle-icon'></i>
                        </div>
                        <div class="collapse show" id="vitalsSection">
                            <div class="card-body-modern">
                                <div class="vitals-grid">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">BP (mmHg)</label>
                                        <input type="text" name="bp" class="form-control-modern" 
                                               placeholder="120/80"
                                               value="<?php echo htmlspecialchars($form_data['bp'] ?? ($template_data['bp'] ?? '')); ?>">
                                    </div>
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">Pulse (bpm)</label>
                                        <input type="text" name="pulse" class="form-control-modern" 
                                               placeholder="72"
                                               value="<?php echo htmlspecialchars($form_data['pulse'] ?? ($template_data['pulse'] ?? '')); ?>">
                                    </div>
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">Temp (°F)</label>
                                        <input type="text" name="temperature" class="form-control-modern" 
                                               placeholder="98.6"
                                               value="<?php echo htmlspecialchars($form_data['temperature'] ?? ($template_data['temperature'] ?? '')); ?>">
                                    </div>
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">SpO2 (%)</label>
                                        <input type="text" name="spo2" class="form-control-modern" 
                                               placeholder="98"
                                               value="<?php echo htmlspecialchars($form_data['spo2'] ?? ($template_data['spo2'] ?? '')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaints & History -->
                    <div class="card-modern slide-up" style="animation-delay: 0.2s">
                        <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#complaintsSection">
                            <h5>
                                <i class='bx bx-clipboard'></i>
                                Complaints & History
                            </h5>
                            <i class='bx bx-chevron-down toggle-icon'></i>
                        </div>
                        <div class="collapse show" id="complaintsSection">
                            <div class="card-body-modern">
                                <!-- Chief Complaints -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        Chief Complaints <span class="required">*</span>
                                    </label>
                                    <div class="template-selector mb-2">
                                        <select class="form-control-modern select2-template" id="chiefComplaintTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['chief_complaint'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ✓' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn-modern btn-secondary-modern btn-sm-modern" type="button" id="insertChiefComplaintTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <textarea name="chief_complaints" id="chief_complaints" 
                                              class="form-control-modern" rows="3" 
                                              placeholder="Enter chief complaints..."><?php echo htmlspecialchars($form_data['chief_complaints'] ?? ($template_data['chief_complaints'] ?? '')); ?></textarea>
                                    <div id="suggestions-chief_complaints" class="suggestions-dropdown"></div>
                                </div>

                               
                                
                                
                                <!-- Medical History -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Medical History</label>
                                    <div class="template-selector mb-2">
                                        <select id="medicalHistoryTemplates" class="form-control-modern select2-template">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['medical_history'] as $tmpl): ?>
                                                <option value="<?php echo htmlspecialchars($tmpl['content']); ?>">
                                                    <?php echo htmlspecialchars($tmpl['name']); ?> <?php if ($tmpl['is_default']): ?>✓<?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn-modern btn-secondary-modern btn-sm-modern" id="insertMedicalHistoryTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <textarea name="medical_history" id="medical_history" 
                                              class="form-control-modern" rows="4"
                                              placeholder="Enter medical history..."><?php echo htmlspecialchars($form_data['medical_history']); ?></textarea>
                                    <div id="suggestions-medical_history" class="suggestions-dropdown"></div>

                                    <!-- Calculators -->
                                    <div class="calculator-section">
                                        <h6><i class='bx bx-calendar-event'></i> EDD Calculator</h6>
                                        <div class="grid-2">
                                            <div class="form-group-modern">
                                                <label class="form-label-modern">LMP Date</label>
                                                <input type="date" id="lmp_date" class="form-control-modern">
                                            </div>
                                            <div class="form-group-modern" style="display: flex; align-items: flex-end;">
                                                <button type="button" id="calculate_edd" class="btn-modern btn-primary-modern w-100">
                                                    <i class='bx bx-calculator'></i> Calculate
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="calculator-section">
                                        <h6><i class='bx bx-body'></i> BMI Calculator</h6>
                                        <div class="grid-3">
                                            <div class="form-group-modern">
                                                <label class="form-label-modern">Height (in)</label>
                                                <input type="number" id="height_in" class="form-control-modern" step="0.1" placeholder="66">
                                            </div>
                                            <div class="form-group-modern">
                                                <label class="form-label-modern">Weight (kg)</label>
                                                <input type="number" id="weight_kg" class="form-control-modern" step="0.1" placeholder="70">
                                            </div>
                                            <div class="form-group-modern" style="display: flex; align-items: flex-end;">
                                                <button type="button" id="calculate_bmi" class="btn-modern btn-primary-modern w-100">
                                                    <i class='bx bx-calculator'></i> Calculate
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Examination Findings -->
                               <div class="form-group-modern">
    <label class="form-label-modern">Examination Findings</label>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="template-selector">
            <select id="examinationFindingsTemplates" class="form-control-modern select2-template">
                <option value="">Select template...</option>
                <?php foreach ($templates['examination_findings'] as $tmpl): ?>
                    <option value="<?php echo htmlspecialchars($tmpl['content']); ?>">
                        <?php echo htmlspecialchars($tmpl['name']); ?> <?php if ($tmpl['is_default']): ?>✓<?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn-modern btn-secondary-modern btn-sm-modern" id="insertExaminationFindingsTemplate">
                <i class='bx bx-plus'></i> Insert
            </button>
        </div>
        <button type="button" class="btn-modern btn-primary-modern btn-sm-modern" data-bs-toggle="modal" data-bs-target="#systemicExamModal">
            <i class='bx bx-body'></i> Systemic Examination
        </button>
    </div>
    <textarea name="examination_findings" id="examination_findings" 
              class="form-control-modern" rows="6"
              placeholder="Enter examination findings..."><?php echo htmlspecialchars($form_data['examination_findings'] ?? ($template_data['examination_findings'] ?? '')); ?></textarea>
    <div id="suggestions-examination_findings" class="suggestions-dropdown"></div>
</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <!-- Diagnosis & Investigation -->
                    <div class="card-modern slide-up" style="animation-delay: 0.15s">
                        <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#diagnosisSection">
                            <h5>
                                <i class='bx bx-search-alt'></i>
                                Diagnosis & Investigation
                            </h5>
                            <i class='bx bx-chevron-down toggle-icon'></i>
                        </div>
                        <div class="collapse show" id="diagnosisSection">
                            <div class="card-body-modern">
                                <!-- Diagnosis -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        Diagnosis <span class="required">*</span>
                                    </label>
                                    <div class="template-selector mb-2">
                                        <select class="form-control-modern select2-template" id="diagnosisTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['diagnosis'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ✓' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn-modern btn-secondary-modern btn-sm-modern" type="button" id="insertDiagnosisTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <textarea class="form-control-modern" id="diagnosis" name="diagnosis" rows="3" required
                                              placeholder="Enter diagnosis..."><?php echo htmlspecialchars($form_data['diagnosis'] ?? ($template_data['diagnosis'] ?? '')); ?></textarea>
                                    <div id="suggestions-diagnosis" class="suggestions-dropdown"></div>
                                </div>

                                <!-- Investigation -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Investigations</label>
                                    <div class="template-selector mb-2">
                                        <select class="form-control-modern select2-template" id="investigationTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['investigation'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ✓' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn-modern btn-secondary-modern btn-sm-modern" type="button" id="insertInvestigationTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <textarea class="form-control-modern" id="investigation" name="investigation" rows="3"
                                              placeholder="Enter investigations..."><?php echo htmlspecialchars($form_data['investigation'] ?? ($template_data['investigation'] ?? '')); ?></textarea>
                                    <div id="suggestions-investigation" class="suggestions-dropdown"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Templates -->
                    <div class="card-modern slide-up" style="animation-delay: 0.2s">
                        <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#treatmentSection">
                            <h5>
                                <i class='bx bx-collection'></i>
                                Treatment Templates
                            </h5>
                            <i class='bx bx-chevron-down toggle-icon'></i>
                        </div>
                        <div class="collapse show" id="treatmentSection">
                            <div class="card-body-modern">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Quick Load Treatment</label>
                                    <div class="template-selector">
                                        <select class="form-control-modern select2-template" id="treatmentTemplates">
                                            <option value="">Choose treatment template...</option>
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
                                        <button type="button" class="btn-modern btn-primary-modern" id="insertTreatmentTemplate">
                                            <i class='bx bx-import'></i> Load
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advice & Follow-up -->
                    <div class="card-modern slide-up" style="animation-delay: 0.25s">
                        <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#adviceSection">
                            <h5>
                                <i class='bx bx-message-detail'></i>
                                Advice & Follow-up
                            </h5>
                            <i class='bx bx-chevron-down toggle-icon'></i>
                        </div>
                        <div class="collapse show" id="adviceSection">
                            <div class="card-body-modern">
                                <!-- Advice -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Advice</label>
                                    <div class="template-selector mb-2">
                                        <select class="form-control-modern select2-template" id="adviceTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['advice'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ✓' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn-modern btn-secondary-modern btn-sm-modern" type="button" id="insertAdviceTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <textarea class="form-control-modern" id="advice" name="advice" rows="3"
                                              placeholder="Enter advice..."><?php echo htmlspecialchars($form_data['advice'] ?? ($template_data['advice'] ?? '')); ?></textarea>
                                    <div id="suggestions-advice" class="suggestions-dropdown"></div>
                                </div>

                                <!-- Next Visit -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Next Visit</label>
                                    <?php $today = date('Y-m-d'); ?>
                                    <input type="date" name="next_visit" class="form-control-modern" 
                                           min="<?php echo $today; ?>" 
                                           value="<?php echo htmlspecialchars($form_data['next_visit'] ?? $today); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicines Section - Full Width -->
            <div class="card-modern slide-up" style="animation-delay: 0.3s">
                <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#medicinesSection">
                    <h5>
                        <i class='bx bx-capsule'></i>
                        Prescribed Medicines
                    </h5>
                    <i class='bx bx-chevron-down toggle-icon'></i>
                </div>
                <div class="collapse show" id="medicinesSection">
                    <div class="card-body-modern">
                        <div id="drugsList">
                            <?php if (!empty($template_drugs)):
                                foreach ($template_drugs as $index => $drug): ?>
                                    <div class="drug-row-modern fade-in">
                                        <span class="drug-row-number"><?php echo $index + 1; ?></span>
                                        
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                Medicine <span class="required">*</span>
                                            </label>
                                            <div class="input-group-modern">
                                                <select class="form-control-modern select2-drug" name="drugs[<?php echo $index; ?>][drug_id]">
                                                    <?php if ($drug['drug_id']): ?>
                                                        <option value="<?php echo $drug['drug_id']; ?>" selected
                                                                data-manufacturer="<?php echo htmlspecialchars($drug['manufacturer']); ?>"
                                                                data-price="<?php echo htmlspecialchars($drug['price']); ?>"
                                                                data-drug-class="<?php echo htmlspecialchars($drug['drug_class']); ?>">
                                                            <?php echo htmlspecialchars("{$drug['brand_name']} ({$drug['generic_name']}) {$drug['strength']}"); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                </select>
                                                <input type="text" class="form-control-modern drug-name-input" 
                                                       name="drugs[<?php echo $index; ?>][drug_name]" 
                                                       value="<?php echo htmlspecialchars($drug['drug_name'] ?? ''); ?>" 
                                                       placeholder="Or type medicine name..."
                                                       style="<?php echo $drug['drug_id'] ? 'display:none;' : ''; ?>">
                                            </div>
                                            
                                            <?php if ($drug['drug_id']): ?>
                                                <div class="drug-info-modern">
                                                    <span><strong>Manufacturer:</strong> <?php echo htmlspecialchars($drug['manufacturer'] ?: 'N/A'); ?></span>
                                                    <span><strong>Price:</strong> <?php echo htmlspecialchars($drug['price'] ?: 'N/A'); ?></span>
                                                    <span><strong>Class:</strong> <?php echo htmlspecialchars($drug['drug_class'] ?: 'N/A'); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="drug-info-modern">
                                                    <span><strong>Manufacturer:</strong> N/A</span>
                                                    <span><strong>Price:</strong> N/A</span>
                                                    <span><strong>Class:</strong> N/A</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="grid-3" style="margin-top: 1rem;">
                                            <div class="form-group-modern">
                                                <label class="form-label-modern">
                                                    Frequency <span class="required">*</span>
                                                </label>
                                                <div class="input-group-modern">
                                                    <select class="form-control-modern frequency-template" style="max-width: 120px;">
                                                        <option value="">Template</option>
                                                        <?php foreach ($templates['frequency'] as $template): ?>
                                                            <option value="<?php echo htmlspecialchars($template['frequency']); ?>">
                                                                <?php echo htmlspecialchars($template['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="text" class="form-control-modern" 
                                                           name="drugs[<?php echo $index; ?>][frequency]" 
                                                           value="<?php echo htmlspecialchars($drug['frequency']); ?>" 
                                                           required placeholder="1+1+1">
                                                </div>
                                            </div>
                                            <div class="form-group-modern">
                                                <label class="form-label-modern">
                                                    Duration <span class="required">*</span>
                                                </label>
                                                <div class="input-group-modern">
                                                    <select class="form-control-modern duration-template" style="max-width: 120px;">
                                                        <option value="">Template</option>
                                                        <?php foreach ($templates['duration'] as $template): ?>
                                                            <option value="<?php echo htmlspecialchars($template['duration']); ?>">
                                                                <?php echo htmlspecialchars($template['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="text" class="form-control-modern" 
                                                           name="drugs[<?php echo $index; ?>][duration]" 
                                                           value="<?php echo htmlspecialchars($drug['duration']); ?>" 
                                                           required placeholder="7 days">
                                                </div>
                                            </div>
                                            <div class="form-group-modern">
                                                <label class="form-label-modern">Instructions</label>
                                                <div class="input-group-modern">
                                                    <select class="form-control-modern instruction-template" style="max-width: 120px;">
                                                        <option value="">Template</option>
                                                        <?php foreach ($templates['instruction'] as $template): ?>
                                                            <option value="<?php echo htmlspecialchars($template['instruction']); ?>">
                                                                <?php echo htmlspecialchars($template['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="text" class="form-control-modern" 
                                                           name="drugs[<?php echo $index; ?>][instructions]" 
                                                           value="<?php echo htmlspecialchars($drug['instructions']); ?>"
                                                           placeholder="After meal">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="drug-actions">
                                            <button type="button" class="btn-modern btn-outline-modern btn-sm-modern change-brand-btn">
                                                <i class='bx bx-refresh'></i> Change Brand
                                            </button>
                                            <button type="button" class="btn-modern btn-outline-modern btn-sm-modern select-manufacturer-btn">
                                                <i class='bx bx-building'></i> Select Manufacturer
                                            </button>
                                            <button type="button" class="btn-modern btn-danger-modern btn-sm-modern remove-drug" style="margin-left: auto;">
                                                <i class='bx bx-trash'></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach;
                            endif; ?>
                        </div>

                        <button type="button" class="btn-modern btn-secondary-modern" id="addDrug">
                            <i class='bx bx-plus'></i> Add Medicine
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions slide-up" style="animation-delay: 0.35s">
                <a href="prescriptions.php" class="btn-modern btn-secondary-modern">
                    <i class='bx bx-x'></i> Cancel
                </a>
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class='bx bx-save'></i> Save <?php echo $is_template ? "Template" : "Prescription"; ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Add Patient Modal -->
    <div class="modal fade modal-modern" id="addPatientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class='bx bx-user-plus me-2'></i> Add New Patient
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quickAddPatientForm">
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                Name <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-control-modern" required placeholder="Patient name">
                        </div>
                        <div class="grid-2">
                            <div class="form-group-modern">
                                <label class="form-label-modern">Age</label>
                                <input type="number" name="age" class="form-control-modern" placeholder="Age">
                            </div>
                            <div class="form-group-modern">
                                <label class="form-label-modern">Sex</label>
                                <select name="sex" class="form-control-modern">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label-modern">Phone</label>
                            <input type="text" name="phone" class="form-control-modern" placeholder="Phone number">
                        </div>
                        <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-secondary-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-modern btn-primary-modern" id="savePatient">
                        <i class='bx bx-check'></i> Save Patient
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade modal-modern" id="scanPatientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class='bx bx-qr-scan me-2'></i> Scan Patient QR Code
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="qr-scanner-container">
                        <div id="qr-reader-patient"></div>
                    </div>
                    <div class="text-center p-3">
                        <p class="mb-0 text-muted">
                            <i class='bx bx-camera me-1'></i>
                            Point your camera at the patient's QR code
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Systemic Examination Modal -->
<div class="modal fade modal-modern" id="systemicExamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class='bx bx-body me-2'></i> Systemic Examination
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php 
                    $systems = [
                        'General Appearance' => ['Well nourished', 'Ill-looking', 'Conscious', 'Oriented', 'Febrile', 'Pale', 'Jaundiced', 'Cyanosed'],
                        'Cardiovascular System (CVS)' => ['S1 S2 normal', 'No murmur', 'Apex beat normal', 'Peripheral pulses normal'],
                        'Respiratory System (RS)' => ['Bilateral air entry equal', 'No added sounds', 'Normal vesicular breathing'],
                        'Gastrointestinal System (GIS)' => ['Abdomen soft', 'Non-tender', 'No organomegaly', 'Bowel sounds normal'],
                        'Central Nervous System (CNS)' => ['Conscious', 'Oriented', 'No cranial nerve deficit', 'Motor power 5/5', 'Sensory intact'],
                        'Musculoskeletal System' => ['No deformity', 'Normal gait', 'No joint swelling'],
                        'Skin' => ['No rash', 'No lesion'],
                        'ENT' => ['Throat normal', 'Ear canals clear'],
                        'Eyes' => ['Pupils equal and reactive', 'No conjunctival pallor']
                    ];
                    foreach ($systems as $system => $options): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card-modern h-100">
                            <div class="card-header-modern collapsed" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo str_replace(' ', '', $system); ?>">
                                <h6 class="mb-0"><?php echo $system; ?></h6>
                                <i class='bx bx-chevron-down toggle-icon'></i>
                            </div>
                            <div class="collapse show" id="collapse<?php echo str_replace(' ', '', $system); ?>">
                                <div class="card-body-modern pt-3">
                                    <?php foreach ($options as $option): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($option); ?>" id="<?php echo str_replace(' ', '', $system . $option); ?>">
                                            <label class="form-check-label" for="<?php echo str_replace(' ', '', $system . $option); ?>">
                                                <?php echo htmlspecialchars($option); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="mt-3">
                                        <textarea class="form-control-modern" rows="2" placeholder="Other findings..." data-system="<?php echo $system; ?>"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-secondary-modern" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-modern btn-success-modern" id="insertSystemicExam">
                    <i class='bx bx-check'></i> Insert into Examination Findings
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Drug Row Template -->
    <template id="drugRowTemplate">
        <div class="drug-row-modern fade-in">
            <span class="drug-row-number">1</span>
            
            <div class="form-group-modern">
                <label class="form-label-modern">
                    Medicine <span class="required">*</span>
                </label>
                <div class="input-group-modern">
                    <select class="form-control-modern select2-drug" name="drugs[INDEX][drug_id]"></select>
                    <input type="text" class="form-control-modern drug-name-input" 
                           name="drugs[INDEX][drug_name]" 
                           placeholder="Or type medicine name...">
                </div>
                <div class="drug-info-modern">
                    <span><strong>Manufacturer:</strong> N/A</span>
                    <span><strong>Price:</strong> N/A</span>
                    <span><strong>Class:</strong> N/A</span>
                </div>
            </div>

            <div class="grid-3" style="margin-top: 1rem;">
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        Frequency <span class="required">*</span>
                    </label>
                    <div class="input-group-modern">
                        <select class="form-control-modern frequency-template" style="max-width: 120px;">
                            <option value="">Template</option>
                            <?php foreach ($templates['frequency'] as $template): ?>
                                <option value="<?php echo htmlspecialchars($template['frequency']); ?>">
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control-modern autosuggest-input" 
                               name="drugs[INDEX][frequency]" data-field="frequency" 
                               required placeholder="1+1+1">
                    </div>
                    <div class="suggestions-dropdown"></div>
                </div>
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        Duration <span class="required">*</span>
                    </label>
                    <div class="input-group-modern">
                        <select class="form-control-modern duration-template" style="max-width: 120px;">
                            <option value="">Template</option>
                            <?php foreach ($templates['duration'] as $template): ?>
                                <option value="<?php echo htmlspecialchars($template['duration']); ?>">
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control-modern autosuggest-input" 
                               name="drugs[INDEX][duration]" data-field="duration" 
                               required placeholder="7 days">
                    </div>
                    <div class="suggestions-dropdown"></div>
                </div>
                <div class="form-group-modern">
                    <label class="form-label-modern">Instructions</label>
                    <div class="input-group-modern">
                        <select class="form-control-modern instruction-template" style="max-width: 120px;">
                            <option value="">Template</option>
                            <?php foreach ($templates['instruction'] as $template): ?>
                                <option value="<?php echo htmlspecialchars($template['instruction']); ?>">
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control-modern autosuggest-input" 
                               name="drugs[INDEX][instructions]" data-field="instructions" 
                               placeholder="After meal">
                    </div>
                    <div class="suggestions-dropdown"></div>
                </div>
            </div>

            <div class="drug-actions">
                <button type="button" class="btn-modern btn-outline-modern btn-sm-modern change-brand-btn">
                    <i class='bx bx-refresh'></i> Change Brand
                </button>
                <button type="button" class="btn-modern btn-outline-modern btn-sm-modern select-manufacturer-btn">
                    <i class='bx bx-building'></i> Select Manufacturer
                </button>
                <button type="button" class="btn-modern btn-danger-modern btn-sm-modern remove-drug" style="margin-left: auto;">
                    <i class='bx bx-trash'></i> Remove
                </button>
            </div>
        </div>
    </template>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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
            
            // EDD Calculator - Inserts both LMP and EDD
$('#calculate_edd').on('click', function() {
    const lmpInput = $('#lmp_date').val();
    if (!lmpInput) {
        alert('Please select LMP date');
        return;
    }

    const lmpDate = new Date(lmpInput);
    const eddDate = new Date(lmpDate);
    eddDate.setDate(eddDate.getDate() + 280);  // 280 days = 40 weeks

    // Format dates (e.g., 15 December 2025)
    const lmpFormatted = lmpDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
    const eddFormatted = eddDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });

    // Create text to insert
    const textToInsert = `LMP: ${lmpFormatted}\nEDD: ${eddFormatted}`;

    // Insert into Examination Findings
    const current = $('#medical_history').val().trim();
    const newValue = current ? current + "\n" + textToInsert : textToInsert;
    $('#medical_history').val(newValue);
});

            // BMI Calculator
$('#calculate_bmi').on('click', function() {
    const heightIn = parseFloat($('#height_in').val());
    const weightKg = parseFloat($('#weight_kg').val());
    if (isNaN(heightIn) || isNaN(weightKg) || heightIn <= 0 || weightKg <= 0) {
        alert('Please enter valid height and weight');
        return;
    }

    const heightM = heightIn * 0.0254;
    const bmi = (weightKg / (heightM * heightM)).toFixed(1);
    let category = '';
    if (bmi < 18.5) category = 'Underweight';
    else if (bmi < 25) category = 'Normal';
    else if (bmi < 30) category = 'Overweight';
    else category = 'Obese';

    const textToInsert = `BMI: ${bmi} (${category}) - Height: ${heightIn} inches, Weight: ${weightKg} kg`;

    const current = $('#medical_history').val().trim();
    const newValue = current ? current + "\n" + textToInsert : textToInsert;
    $('#medical_history').val(newValue);

    // Clear inputs
    $('#height_in').val('');
    $('#weight_kg').val('');
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
                                    const $row = $('#drugsList .drug-row-modern:last');
                                    if (drug.drug_id) {
                                        $row.find('.select2-drug').append(new Option(
                                            `${drug.brand_name} (${drug.generic_name}) ${drug.strength}`,
                                            drug.drug_id,
                                            true,
                                            true
                                        )).trigger('change');
                                        $row.find('.drug-info-modern').html(
                                            '<span><strong>Manufacturer:</strong> ' + (drug.manufacturer || 'N/A') + '</span><br>' +
                                            '<span><strong>Price:</strong> ' + (drug.price || 'N/A') + '</span><br>' +
                                            '<span><strong>Class:</strong> ' + (drug.drug_class || 'N/A') + '</span>'
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
                    const $row = $(this).closest('.drug-row-modern');
                    if (data.id && data.id !== data.text) {
                        $row.find('.drug-info-modern').html(
                            '<span><strong>Manufacturer:</strong> ' + (data.manufacturer || 'N/A') + '</span><br>' +
                            '<span><strong>Price:</strong> ' + (data.price || 'N/A') + '</span><br>' +
                            '<span><strong>Class:</strong> ' + (data.drug_class || 'N/A') + '</span>'
                        );
                        $row.data('generic_name', data.generic_name);
                        $row.data('strength', data.strength);
                        $row.data('current_brand_index', 0);
                        $row.find('.drug-name-input').val('').hide();
                    } else {
                        $row.find('.drug-info-modern').html(
                            '<span><strong>Manufacturer:</strong> N/A</span><br>' +
                            '<span><strong>Price:</strong> N/A</span><br>' +
                            '<span><strong>Class:</strong> N/A</span>'
                        );
                        $row.data('generic_name', '');
                        $row.data('strength', '');
                        $row.data('current_brand_index', -1);
                        $row.find('.drug-name-input').val(data.text).show();
                    }
                }).on('select2:clear', function() {
                    const $row = $(this).closest('.drug-row-modern');
                    $row.find('.drug-info-modern').html(
                        '<span><strong>Manufacturer:</strong> N/A</span><br>' +
                        '<span><strong>Price:</strong> N/A</span><br>' +
                        '<span><strong>Class:</strong> N/A</span>'
                    );
                    $row.data('generic_name', '');
                    $row.data('strength', '');
                    $row.data('current_brand_index', -1);
                    $row.find('.drug-name-input').val('').show();
                });
            }

            $('.select2-drug').each(function() { initializeDrugSelect($(this)); });

            $('#addDrug').click(function() {
                const index = $('#drugsList .drug-row-modern').length;
                const template = $('#drugRowTemplate').html().replace(/INDEX/g, index);
                $('#drugsList').append(template);
                initializeDrugSelect($('#drugsList .drug-row-modern:last .select2-drug'));
                $('#drugsList .drug-row-modern:last .drug-name-input').show();
                updateDrugNumbers();
            });

            function updateDrugNumbers() {
                $('#drugsList .drug-row-modern').each(function(i) {
                    $(this).find('.drug-row-number').text(i + 1);
                });
            }

            $(document).on('click', '.remove-drug', function() {
                $(this).closest('.drug-row-modern').remove();
                updateDrugNumbers();
            });

            $(document).on('click', '.change-brand-btn', function() {
                const $row = $(this).closest('.drug-row-modern');
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
                            $row.find('.drug-info-modern').html(
                                '<span><strong>Manufacturer:</strong> ' + (newDrug.manufacturer || 'N/A') + '</span><br>' +
                                '<span><strong>Price:</strong> ' + (newDrug.price || 'N/A') + '</span><br>' +
                                '<span><strong>Class:</strong> ' + (newDrug.drug_class || 'N/A') + '</span>'
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
                const $row = $(this).closest('.drug-row-modern');
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
                                    $row.find('.drug-info-modern').html(
                                        '<span><strong>Manufacturer:</strong> ' + (selectedDrug.manufacturer || 'N/A') + '</span><br>' +
                                        '<span><strong>Price:</strong> ' + (selectedDrug.price || 'N/A') + '</span><br>' +
                                        '<span><strong>Class:</strong> ' + (selectedDrug.drug_class || 'N/A') + '</span>'
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
                if (value) $(this).closest('.input-group-modern').find('input[name$="[frequency]"]').val(value);
            });
            $(document).on('change', '.duration-template', function() {
                const value = $(this).val();
                if (value) $(this).closest('.input-group-modern').find('input[name$="[duration]"]').val(value);
            });
            $(document).on('change', '.instruction-template', function() {
                const value = $(this).val();
                if (value) $(this).closest('.input-group-modern').find('input[name$="[instructions]"]').val(value);
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
                                const $row = $('#drugsList .drug-row-modern:last');
                                const $select = $row.find('.select2-drug');
                                if (drug.drug_id) {
                                    $select.append(new Option(
                                        `${drug.brand_name} (${drug.generic_name}) ${drug.strength}`,
                                        drug.drug_id,
                                        true,
                                        true
                                    )).trigger('change');
                                    $row.find('.drug-info-modern').html(
                                        '<span><strong>Manufacturer:</strong> ' + (drug.manufacturer || 'N/A') + '</span><br>' +
                                        '<span><strong>Price:</strong> ' + (drug.price || 'N/A') + '</span><br>' +
                                        '<span><strong>Class:</strong> ' + (drug.drug_class || 'N/A') + '</span>'
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
// Enhanced Autosuggest with Keyboard Navigation
function createAutosuggest(input, suggestionsDiv, fieldName) {
    let suggestions = [];
    let selectedIndex = -1;

    const showSuggestions = (items) => {
        suggestions = items;
        selectedIndex = -1;
        suggestionsDiv.innerHTML = '';

        if (items.length === 0) {
            suggestionsDiv.innerHTML = '<div class="suggestion-item text-muted">কোনো সাজেশন পাওয়া যায়নি</div>';
        } else {
            items.forEach((text, index) => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.textContent = text;
                item.onclick = () => selectSuggestion(text);
                suggestionsDiv.appendChild(item);
            });
        }
        suggestionsDiv.style.display = 'block';
    };

    const hideSuggestions = () => {
        suggestionsDiv.style.display = 'none';
        selectedIndex = -1;
    };

    const selectSuggestion = (text) => {
        input.value = text;
        hideSuggestions();
        input.focus();
    };

    const highlightItem = (index) => {
        const items = suggestionsDiv.querySelectorAll('.suggestion-item');
        items.forEach((item, i) => {
            item.classList.toggle('focused', i === index);
        });
    };

    input.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            hideSuggestions();
            return;
        }

        fetch(`ajax_suggestions.php?field=${fieldName}&q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(data => {
                showSuggestions(data);
            })
            .catch(() => {
                suggestionsDiv.innerHTML = '<div class="suggestion-item text-danger">লোড করতে সমস্যা হয়েছে</div>';
                suggestionsDiv.style.display = 'block';
            });
    });

    input.addEventListener('keydown', function(e) {
        const items = suggestionsDiv.querySelectorAll('.suggestion-item');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            highlightItem(selectedIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            highlightItem(selectedIndex);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && selectedIndex < items.length) {
                const text = items[selectedIndex].textContent;
                selectSuggestion(text);
            }
        } else if (e.key === 'Escape') {
            hideSuggestions();
        }
    });

    // Hide on click outside
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            hideSuggestions();
        }
    });
}

// Initialize static fields
createAutosuggest(
    document.getElementById('chief_complaints'),
    document.getElementById('suggestions-chief_complaints'),
    'chief_complaints'
);
createAutosuggest(
    document.getElementById('medical_history'),
    document.getElementById('suggestions-medical_history'),
    'medical_history'
);
createAutosuggest(
    document.getElementById('examination_findings'),
    document.getElementById('suggestions-examination_findings'),
    'examination_findings'
);
createAutosuggest(
    document.getElementById('diagnosis'),
    document.getElementById('suggestions-diagnosis'),
    'diagnosis'
);
createAutosuggest(
    document.getElementById('investigation'),
    document.getElementById('suggestions-investigation'),
    'investigation'
);
createAutosuggest(
    document.getElementById('advice'),
    document.getElementById('suggestions-advice'),
    'advice'
);

// Dynamic drug fields (frequency, duration, instructions)
$(document).on('focus', '.autosuggest-input', function() {
    const $input = $(this);
    const field = $input.data('field');
    const $suggestionsDiv = $input.closest('.form-group-modern').find('.suggestions-dropdown').first();

    // Only initialize once per input
    if ($input.data('autosuggest-initialized')) return;
    $input.data('autosuggest-initialized', true);

    let suggestions = [];
    let selectedIndex = -1;

    const showSuggestions = (items) => {
        suggestions = items;
        selectedIndex = -1;
        $suggestionsDiv.empty();

        if (items.length === 0) {
            $suggestionsDiv.append('<div class="suggestion-item text-muted">কোনো সাজেশন পাওয়া যায়নি</div>');
        } else {
            items.forEach((text, i) => {
                const $item = $('<div>').addClass('suggestion-item').text(text);
                $item.click(() => {
                    $input.val(text).trigger('input');
                    $suggestionsDiv.hide();
                });
                $suggestionsDiv.append($item);
            });
        }
        $suggestionsDiv.show();
    };

    $input.on('input', function() {
        const query = $(this).val().trim();
        if (query.length < 2) {
            $suggestionsDiv.hide();
            return;
        }

        $.getJSON(`ajax_suggestions.php?field=${field}&q=${encodeURIComponent(query)}`, showSuggestions);
    });

    $input.on('keydown', function(e) {
        const items = $suggestionsDiv.find('.suggestion-item');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            items.removeClass('focused');
            items.eq(selectedIndex).addClass('focused');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            items.removeClass('focused');
            items.eq(selectedIndex).addClass('focused');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0) {
                const text = items.eq(selectedIndex).text();
                $input.val(text).trigger('input');
                $suggestionsDiv.hide();
            }
        } else if (e.key === 'Escape') {
            $suggestionsDiv.hide();
        }
    });
});

// Hide all suggestions when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('.form-group-modern').length) {
        $('.suggestions-dropdown').hide();
    }
});
</script>
    <script>
// Advanced Keyboard Shortcuts for Super-Fast Prescription Writing
document.addEventListener('keydown', function(e) {
    // Ignore if typing in input/textarea/select (unless specific combo)
    if (e.target.closest('input, textarea, select, .select2-search__field')) {
        // Allow Ctrl+S and Ctrl+Enter even inside inputs
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'Enter')) {
            // Let through
        } else {
            return; // Don't trigger shortcuts while typing normally
        }
    }

    // Ctrl + M → Add new medicine
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'm') {
        e.preventDefault();
        document.getElementById('addDrug').click();
    }

    // Ctrl + S → Save form
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
        e.preventDefault();
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.click();
        }
    }

    // Ctrl + Enter → Save form (alternative)
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.click();
        }
    }

    // Alt + P → Focus patient selection (only in prescription mode)
    if (e.altKey && e.key.toLowerCase() === 'p' && !document.querySelector('input[name="title"]')) {
        e.preventDefault();
        const patientSelect = document.querySelector('.select2-patient');
        if (patientSelect) {
            $(patientSelect).select2('open');
        }
    }

    // Alt + T → Open Treatment Template dropdown
    if (e.altKey && e.key.toLowerCase() === 't') {
        e.preventDefault();
        const templateSelect = document.getElementById('treatmentTemplates');
        if (templateSelect) {
            $(templateSelect).select2('open');
        }
    }

    // Ctrl + 1 → Chief Complaints
    if ((e.ctrlKey || e.metaKey) && e.key === '1') {
        e.preventDefault();
        const el = document.getElementById('chief_complaints');
        if (el) el.focus();
    }

    // Ctrl + 2 → Medical History
    if ((e.ctrlKey || e.metaKey) && e.key === '2') {
        e.preventDefault();
        const el = document.getElementById('medical_history');
        if (el) el.focus();
    }

    // Ctrl + 3 → Examination Findings
    if ((e.ctrlKey || e.metaKey) && e.key === '3') {
        e.preventDefault();
        const el = document.getElementById('examination_findings');
        if (el) el.focus();
    }

    // Ctrl + 4 → Diagnosis
    if ((e.ctrlKey || e.metaKey) && e.key === '4') {
        e.preventDefault();
        const el = document.getElementById('diagnosis');
        if (el) el.focus();
    }

    // Ctrl + 5 → Investigation
    if ((e.ctrlKey || e.metaKey) && e.key === '5') {
        e.preventDefault();
        const el = document.getElementById('investigation');
        if (el) el.focus();
    }

    // Ctrl + 6 → Advice
    if ((e.ctrlKey || e.metaKey) && e.key === '6') {
        e.preventDefault();
        const el = document.getElementById('advice');
        if (el) el.focus();
    }

    // Esc → Close all Select2 dropdowns and suggestions
    if (e.key === 'Escape') {
        $('.select2-dropdown').hide();
        $('.suggestions-dropdown').hide();
        $('.select2-container--open').removeClass('select2-container--open').find('.select2-selection').blur();
    }
});

// Bonus: Tab navigation enhancement in drug rows
// When pressing Tab in last field of a drug row → go to first field of next row (or add new)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Tab' && !e.shiftKey) {
        const active = document.activeElement;
        const drugRow = active.closest('.drug-row-modern');
        if (!drugRow) return;

        // Check if we're in the last input of this row (instructions)
        const lastInput = drugRow.querySelector('input[name$="[instructions]"]');
        if (active === lastInput || active.name?.endsWith('[instructions]')) {
            const nextRow = drugRow.nextElementSibling;
            if (nextRow && nextRow.classList.contains('drug-row-modern')) {
                const firstSelect = nextRow.querySelector('.select2-drug');
                if (firstSelect) {
                    e.preventDefault();
                    setTimeout(() => $(firstSelect).select2('open'), 50);
                }
            } else {
                // If no next row, add one and focus
                e.preventDefault();
                document.getElementById('addDrug').click();
                setTimeout(() => {
                    const newRow = document.querySelector('#drugsList .drug-row-modern:last');
                    const firstSelect = newRow.querySelector('.select2-drug');
                    if (firstSelect) $(firstSelect).select2('open');
                }, 100);
            }
        }
    }
});
</script>
    
    
<script>
// Systemic Examination Modal - Insert into Examination Findings (FULLY FIXED)
$('#insertSystemicExam').click(function() {
    let findings = [];

    // Helper function to safely escape IDs with special chars
    function escapeId(id) {
        return id.replace(/([()])/g, '\\$1');
    }

    // List of systems with their raw names and collapse IDs
    const systems = [
        { name: 'General Appearance', id: 'GeneralAppearance' },
        { name: 'Cardiovascular System (CVS)', id: 'CardiovascularSystem(CVS)' },
        { name: 'Respiratory System (RS)', id: 'RespiratorySystem(RS)' },
        { name: 'Gastrointestinal System (GIS)', id: 'GastrointestinalSystem(GIS)' },
        { name: 'Central Nervous System (CNS)', id: 'CentralNervousSystem(CNS)' },
        { name: 'Musculoskeletal System', id: 'MusculoskeletalSystem' },
        { name: 'Skin', id: 'Skin' },
        { name: 'ENT', id: 'ENT' },
        { name: 'Eyes', id: 'Eyes' }
    ];

    systems.forEach(sys => {
        let items = [];

        // Safely select checkboxes
        const checkboxSelector = `#collapse${escapeId(sys.id)} input[type="checkbox"]:checked`;
        $(checkboxSelector).each(function() {
            items.push($(this).val());
        });

        // Safely select "Other findings" textarea
        const textareaSelector = `#collapse${escapeId(sys.id)} textarea`;
        const other = $(textareaSelector).val().trim();
        if (other) {
            items.push(other);
        }

        if (items.length > 0) {
            findings.push(sys.name + ": " + items.join(", "));
        }
    });

    // Insert into Examination Findings
    if (findings.length > 0) {
        const textToInsert = findings.join("\n");
        const textarea = $('#examination_findings');
        const current = textarea.val().trim();
        textarea.val(current ? current + "\n\n" + textToInsert : textToInsert);
        
        // Optional: Scroll to the textarea
        textarea[0].scrollIntoView({ behavior: 'smooth' });
    } else {
        alert('No findings selected. Please check some options or add notes.');
    }

    // Close modal
    $('#systemicExamModal').modal('hide');
});
</script>
    
    
</body>
</html>