
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
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--gray-800);
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 200, 255, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .prescription-wrapper {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .prescription-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: 32px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Header Styles */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--gray-100);
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h2 i {
            color: var(--primary);
            font-size: 2rem;
        }

        .page-header p {
            color: var(--gray-500);
            margin-top: 4px;
            font-size: 0.95rem;
        }

        /* Card Section Styles */
        .card-section {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            margin-bottom: 24px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .card-section:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--gray-300);
            transform: translateY(-2px);
        }

        .card-header {
            padding: 18px 24px;
            background: linear-gradient(to right, var(--gray-50), white);
            border-bottom: 1px solid var(--gray-200);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            user-select: none;
        }

        .card-header:hover {
            background: linear-gradient(to right, var(--gray-100), var(--gray-50));
        }

        .card-header h5 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h5 i {
            color: var(--primary);
            font-size: 1.3rem;
        }

        .card-header .toggle-icon {
            color: var(--gray-400);
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .card-header[aria-expanded="false"] .toggle-icon {
            transform: rotate(-90deg);
        }

        .card-body {
            padding: 24px;
            animation: fadeIn 0.3s ease;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label .text-danger {
            color: var(--danger);
        }

        .form-control, .form-select {
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            font-size: 0.95rem;
            color: var(--gray-700);
            background: var(--gray-50);
            transition: var(--transition);
            width: 100%;
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--gray-300);
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
            line-height: 1.6;
        }

        .input-group {
            position: relative;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .input-group .form-control,
        .input-group .form-select {
            flex: 1;
            min-width: 150px;
        }

        /* Button Styles */
        .btn {
            font-weight: 600;
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9rem;
            border: 2px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            border-color: var(--primary-light);
            color: white;
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }

        .btn-outline-secondary {
            background: transparent;
            color: var(--gray-600);
            border-color: var(--gray-300);
        }

        .btn-outline-secondary:hover {
            background: var(--gray-100);
            color: var(--gray-800);
            border-color: var(--gray-400);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
        }

        .btn-light {
            background: var(--gray-100);
            color: var(--gray-700);
            border-color: var(--gray-200);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .btn-icon {
            width: 44px;
            height: 44px;
            padding: 0;
            border-radius: var(--radius-sm);
        }

        /* Drug Row Styles */
        .drug-row {
            background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 16px;
            transition: var(--transition);
            position: relative;
        }

        .drug-row:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow-md);
        }

        .drug-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 4px 0 0 4px;
        }

        .drug-info {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 12px;
            padding: 12px;
            background: var(--gray-100);
            border-radius: var(--radius-sm);
            line-height: 1.8;
        }

        .drug-info strong {
            color: var(--gray-700);
        }

        /* Select2 Custom Styles */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 48px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            transition: var(--transition);
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: var(--gray-300);
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px;
            padding-left: 16px;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 12px;
        }

        .select2-dropdown {
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            margin-top: 4px;
        }

        .select2-results__option {
            padding: 12px 16px;
            transition: var(--transition);
        }

        .select2-results__option--highlighted {
            background: var(--primary) !important;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideUp 0.4s ease;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        .alert-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert i {
            font-size: 1.3rem;
        }

        /* Calculator Sections */
        .calculator-section {
            background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
            border: 2px dashed var(--gray-300);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-top: 20px;
            transition: var(--transition);
        }

        .calculator-section:hover {
            border-color: var(--primary-light);
            border-style: solid;
        }

        .calculator-section h6 {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .calculator-section h6 i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 24px;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        /* Suggestions Dropdown */
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--gray-200);
            border-top: none;
            border-radius: 0 0 var(--radius-sm) var(--radius-sm);
            box-shadow: var(--shadow-lg);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .suggestions-dropdown .suggestion-item {
            padding: 12px 16px;
            cursor: pointer;
            transition: var(--transition);
            border-bottom: 1px solid var(--gray-100);
        }

        .suggestions-dropdown .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestions-dropdown .suggestion-item:hover {
            background: var(--primary);
            color: white;
        }

        /* Footer Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 24px;
            margin-top: 24px;
            border-top: 2px solid var(--gray-100);
            flex-wrap: wrap;
        }

        /* Vitals Grid */
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .vital-card {
            background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            padding: 16px;
            text-align: center;
            transition: var(--transition);
        }

        .vital-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .vital-card label {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-500);
            margin-bottom: 8px;
        }

        .vital-card input {
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            background: transparent;
            color: var(--gray-800);
        }

        .vital-card input:focus {
            outline: none;
        }

        /* Drug Actions */
        .drug-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .prescription-wrapper {
                padding: 16px;
            }

            .prescription-container {
                padding: 24px;
            }
        }

        @media (max-width: 992px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .col-md-6 {
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .prescription-wrapper {
                padding: 0;
            }

            .prescription-container {
                border-radius: 0;
                padding: 16px;
                margin: 0;
            }

            .card-header {
                padding: 14px 16px;
            }

            .card-header h5 {
                font-size: 0.95rem;
            }

            .card-body {
                padding: 16px;
            }

            .drug-row {
                padding: 16px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 0.85rem;
            }

            .btn-icon {
                width: 40px;
                height: 40px;
            }

            .form-control, .form-select {
                padding: 10px 14px;
                font-size: 0.9rem;
            }

            .select2-container--default .select2-selection--single {
                height: 44px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 40px;
                font-size: 0.9rem;
            }

            .vitals-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }

            .input-group {
                flex-direction: column;
            }

            .input-group .form-control,
            .input-group .form-select {
                width: 100%;
            }

            .drug-actions {
                flex-direction: column;
            }

            .drug-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .page-header h2 {
                font-size: 1.25rem;
            }

            .page-header h2 i {
                font-size: 1.5rem;
            }

            .vitals-grid {
                grid-template-columns: 1fr;
            }

            .card-section {
                margin-bottom: 16px;
            }
        }

        /* Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Collapse Animation */
        .collapse {
            transition: height 0.35s ease;
        }

        .collapsing {
            height: 0;
            overflow: hidden;
            transition: height 0.35s ease;
        }

        /* Template Select Wrapper */
        .template-select-wrapper {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .template-select-wrapper .form-select {
            flex: 1;
        }

        /* Floating Label Effect */
        .floating-label {
            position: relative;
        }

        .floating-label label {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: var(--gray-400);
            transition: var(--transition);
            pointer-events: none;
            background: white;
            padding: 0 4px;
        }

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            top: 0;
            font-size: 0.75rem;
            color: var(--primary);
        }

        /* Quick Action Buttons */
        .quick-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            padding: 6px 12px;
            font-size: 0.75rem;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
        }

        .quick-action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="prescription-wrapper">
        <div class="prescription-container">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h2>
                        <i class='bx bx-plus-medical'></i>
                        <?php echo $is_template ? "Create Prescription Template" : "Create New Prescription"; ?>
                    </h2>
                    <p>Fill in the details below to create a new <?php echo $is_template ? "template" : "prescription"; ?></p>
                </div>
                <a href="prescriptions.php" class="btn btn-outline-primary">
                    <i class='bx bx-arrow-back'></i>
                    <span>Back to List</span>
                </a>
            </div>

            <!-- Alerts -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class='bx bx-error-circle'></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class='bx bx-check-circle'></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($is_template ? "?template=1" : "")); ?>">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <!-- Patient/Template Details Card -->
                        <div class="card-section">
                            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#patientCollapse" aria-expanded="true">
                                <h5>
                                    <i class='bx bx-user-circle'></i>
                                    <?php echo $is_template ? "Template Details" : "Patient Selection"; ?>
                                </h5>
                                <i class='bx bx-chevron-down toggle-icon'></i>
                            </div>
                            <div class="card-body collapse show" id="patientCollapse">
                                <?php if ($is_template): ?>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Template Title <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" class="form-control" required 
                                               value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>" 
                                               placeholder="Enter a descriptive template name" autofocus>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Select Patient <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex gap-2">
                                            <div class="flex-grow-1">
                                                <select name="patient_id" class="form-select select2-patient" required>
                                                    <option value="">Search or select patient...</option>
                                                    <?php while ($row = mysqli_fetch_assoc($patients_result)): ?>
                                                        <option value="<?php echo $row['id']; ?>">
                                                            <?php echo htmlspecialchars("{$row['name']} ({$row['patient_uid']}) - {$row['age']}y/{$row['sex']}"); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-success btn-icon" data-bs-toggle="modal" data-bs-target="#scanPatientModal" title="Scan QR Code">
                                                <i class='bx bx-qr-scan'></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#addPatientModal" title="Add New Patient">
                                                <i class='bx bx-plus'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Load Previous Prescription</label>
                                        <select class="form-control select2-prescription" name="load_prescription">
                                            <option value="">Search by patient name or date...</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Vital Signs Card -->
                        <div class="card-section">
                            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#vitalsCollapse" aria-expanded="true">
                                <h5>
                                    <i class='bx bx-heart'></i>
                                    Vital Signs
                                </h5>
                                <i class='bx bx-chevron-down toggle-icon'></i>
                            </div>
                            <div class="card-body collapse show" id="vitalsCollapse">
                                <div class="vitals-grid">
                                    <div class="vital-card">
                                        <label>Blood Pressure</label>
                                        <input type="text" name="bp" placeholder="120/80" 
                                               value="<?php echo htmlspecialchars($form_data['bp'] ?? ($template_data['bp'] ?? '')); ?>">
                                    </div>
                                    <div class="vital-card">
                                        <label>Pulse Rate</label>
                                        <input type="text" name="pulse" placeholder="72 bpm" 
                                               value="<?php echo htmlspecialchars($form_data['pulse'] ?? ($template_data['pulse'] ?? '')); ?>">
                                    </div>
                                    <div class="vital-card">
                                        <label>Temperature</label>
                                        <input type="text" name="temperature" placeholder="98.6°F" 
                                               value="<?php echo htmlspecialchars($form_data['temperature'] ?? ($template_data['temperature'] ?? '')); ?>">
                                    </div>
                                    <div class="vital-card">
                                        <label>SpO2</label>
                                        <input type="text" name="spo2" placeholder="98%" 
                                               value="<?php echo htmlspecialchars($form_data['spo2'] ?? ($template_data['spo2'] ?? '')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Complaints & History Card -->
                        <div class="card-section">
                            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#complaintsCollapse" aria-expanded="true">
                                <h5>
                                    <i class='bx bx-message-detail'></i>
                                    Complaints & History
                                </h5>
                                <i class='bx bx-chevron-down toggle-icon'></i>
                            </div>
                            <div class="card-body collapse show" id="complaintsCollapse">
                                <!-- Chief Complaints -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        Chief Complaints <span class="text-danger">*</span>
                                    </label>
                                    <div class="template-select-wrapper">
                                        <select class="form-select select2-template" id="chiefComplaintTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['chief_complaint'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ★' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-outline-secondary" type="button" id="insertChiefComplaintTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <div class="position-relative">
                                        <textarea name="chief_complaints" id="chief_complaints" class="form-control" rows="3" 
                                                  placeholder="Describe the patient's main complaints..."><?php echo htmlspecialchars($form_data['chief_complaints'] ?? ($template_data['chief_complaints'] ?? '')); ?></textarea>
                                        <div id="suggestions-chief_complaints" class="suggestions-dropdown"></div>
                                    </div>
                                </div>

                                <!-- Medical History -->
                                <div class="mb-4">
                                    <label class="form-label">Medical History</label>
                                    <div class="template-select-wrapper">
                                        <select id="medicalHistoryTemplates" class="form-select select2-template">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['medical_history'] as $tmpl): ?>
                                                <option value="<?php echo htmlspecialchars($tmpl['content']); ?>">
                                                    <?php echo htmlspecialchars($tmpl['name'] . ($tmpl['is_default'] ? ' ★' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertMedicalHistoryTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <div class="position-relative">
                                        <textarea name="medical_history" id="medical_history" class="form-control" rows="4" 
                                                  placeholder="Past medical history, allergies, medications..."><?php echo htmlspecialchars($form_data['medical_history']); ?></textarea>
                                        <div id="suggestions-medical_history" class="suggestions-dropdown"></div>
                                    </div>

                                    <!-- Calculators -->
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-6">
                                            <div class="calculator-section">
                                                <h6><i class='bx bx-calendar-event'></i> EDD Calculator</h6>
                                                <div class="mb-2">
                                                    <label class="form-label small">LMP Date</label>
                                                    <input type="date" id="lmp_date" class="form-control">
                                                </div>
                                                <button type="button" id="calculate_edd" class="btn btn-primary btn-sm w-100">
                                                    <i class='bx bx-calculator'></i> Calculate EDD
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="calculator-section">
                                                <h6><i class='bx bx-body'></i> BMI Calculator</h6>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <input type="number" id="height_in" class="form-control" placeholder="Height (in)" step="0.1">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" id="weight_kg" class="form-control" placeholder="Weight (kg)" step="0.1">
                                                    </div>
                                                </div>
                                                <button type="button" id="calculate_bmi" class="btn btn-primary btn-sm w-100">
                                                    <i class='bx bx-calculator'></i> Calculate BMI
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Examination Findings -->
                                <div class="mb-0">
                                    <label class="form-label">Examination Findings</label>
                                    <div class="template-select-wrapper">
                                        <select id="examinationFindingsTemplates" class="form-select select2-template">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['examination_findings'] as $tmpl): ?>
                                                <option value="<?php echo htmlspecialchars($tmpl['content']); ?>">
                                                    <?php echo htmlspecialchars($tmpl['name'] . ($tmpl['is_default'] ? ' ★' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertExaminationFindingsTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <div class="position-relative">
                                        <textarea name="examination_findings" id="examination_findings" class="form-control" rows="4" 
                                                  placeholder="Physical examination findings..."><?php echo htmlspecialchars($form_data['examination_findings']); ?></textarea>
                                        <div id="suggestions-examination_findings" class="suggestions-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <!-- Diagnosis & Investigation Card -->
                        <div class="card-section">
                            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#diagnosisCollapse" aria-expanded="true">
                                <h5>
                                    <i class='bx bx-search-alt'></i>
                                    Diagnosis & Investigation
                                </h5>
                                <i class='bx bx-chevron-down toggle-icon'></i>
                            </div>
                            <div class="card-body collapse show" id="diagnosisCollapse">
                                <!-- Diagnosis -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        Diagnosis <span class="text-danger">*</span>
                                    </label>
                                    <div class="template-select-wrapper">
                                        <select class="form-select select2-template" id="diagnosisTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['diagnosis'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ★' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-outline-secondary" type="button" id="insertDiagnosisTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <div class="position-relative">
                                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required 
                                                  placeholder="Primary and secondary diagnoses..."><?php echo htmlspecialchars($form_data['diagnosis'] ?? ($template_data['diagnosis'] ?? '')); ?></textarea>
                                        <div id="suggestions-diagnosis" class="suggestions-dropdown"></div>
                                    </div>
                                </div>

                                <!-- Investigations -->
                                <div class="mb-0">
                                    <label class="form-label">Investigations</label>
                                    <div class="template-select-wrapper">
                                        <select class="form-select select2-template" id="investigationTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['investigation'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ★' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-outline-secondary" type="button" id="insertInvestigationTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <div class="position-relative">
                                        <textarea class="form-control" id="investigation" name="investigation" rows="3" 
                                                  placeholder="Lab tests, imaging, etc..."><?php echo htmlspecialchars($form_data['investigation'] ?? ($template_data['investigation'] ?? '')); ?></textarea>
                                        <div id="suggestions-investigation" class="suggestions-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Treatment Template Card -->
                        <div class="card-section">
                            <div class="card-header">
                                <h5>
                                    <i class='bx bx-bookmark-alt'></i>
                                    Quick Treatment Templates
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex gap-2">
                                    <select class="form-select select2-template flex-grow-1" id="treatmentTemplates">
                                        <option value="">Select a treatment template...</option>
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
                                    <button type="button" class="btn btn-primary" id="insertTreatmentTemplate">
                                        <i class='bx bx-import'></i> Apply
                                    </button>
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class='bx bx-info-circle'></i> This will populate medicines and related fields
                                </p>
                            </div>
                        </div>

                        <!-- Advice & Follow-up Card -->
                        <div class="card-section">
                            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#adviceCollapse" aria-expanded="true">
                                <h5>
                                    <i class='bx bx-comment-detail'></i>
                                    Advice & Follow-up
                                </h5>
                                <i class='bx bx-chevron-down toggle-icon'></i>
                            </div>
                            <div class="card-body collapse show" id="adviceCollapse">
                                <!-- Advice -->
                                <div class="mb-4">
                                    <label class="form-label">Advice</label>
                                    <div class="template-select-wrapper">
                                        <select class="form-select select2-template" id="adviceTemplates">
                                            <option value="">Select template...</option>
                                            <?php foreach ($templates['advice'] as $template): ?>
                                                <option value="<?php echo htmlspecialchars($template['content']); ?>">
                                                    <?php echo htmlspecialchars($template['name'] . ($template['is_default'] ? ' ★' : '')); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-outline-secondary" type="button" id="insertAdviceTemplate">
                                            <i class='bx bx-plus'></i> Insert
                                        </button>
                                    </div>
                                    <div class="position-relative">
                                        <textarea class="form-control" id="advice" name="advice" rows="3" 
                                                  placeholder="Dietary advice, lifestyle modifications..."><?php echo htmlspecialchars($form_data['advice'] ?? ($template_data['advice'] ?? '')); ?></textarea>
                                        <div id="suggestions-advice" class="suggestions-dropdown"></div>
                                    </div>
                                </div>

                                <!-- Next Visit -->
                                <div class="mb-0">
                                    <label class="form-label">Next Visit Date</label>
                                    <?php $today = date('Y-m-d'); ?>
                                    <input type="date" name="next_visit" class="form-control" min="<?php echo $today; ?>" 
                                           value="<?php echo htmlspecialchars($form_data['next_visit'] ?? $today); ?>">
                                    <div class="quick-actions">
                                        <span class="quick-action-btn" onclick="setNextVisit(7)">+7 Days</span>
                                        <span class="quick-action-btn" onclick="setNextVisit(14)">+14 Days</span>
                                        <span class="quick-action-btn" onclick="setNextVisit(30)">+1 Month</span>
                                        <span class="quick-action-btn" onclick="setNextVisit(90)">+3 Months</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescribed Medicines Section -->
                <div class="card-section mt-4">
                    <div class="card-header" data-bs-toggle="collapse" data-bs-target="#drugsCollapse" aria-expanded="true">
                        <h5>
                            <i class='bx bx-capsule'></i>
                            Prescribed Medicines
                        </h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="card-body collapse show" id="drugsCollapse">
                        <div id="drugsList">
                            <?php if (!empty($template_drugs)): ?>
                                <?php foreach ($template_drugs as $index => $drug): ?>
                                    <!-- Drug rows will be here -->
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-3" id="addDrug">
                            <i class='bx bx-plus-circle'></i> Add Medicine
                        </button>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="prescriptions.php" class="btn btn-light">
                        <i class='bx bx-x'></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Save <?php echo $is_template ? "Template" : "Prescription"; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1">
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
                        <div class="mb-3">
                            <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter full name">
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" class="form-control" placeholder="Years">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sex</label>
                                <select name="sex" class="form-select">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-0 mt-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX">
                        </div>
                        <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="savePatient">
                        <i class='bx bx-save'></i> Save Patient
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade" id="scanPatientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class='bx bx-qr-scan me-2'></i> Scan Patient QR Code
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-dark text-center p-0">
                    <div id="qr-reader-patient" style="width:100%; min-height:400px;"></div>
                    <div class="p-3 text-white-50">
                        <i class='bx bx-info-circle'></i> Point your camera at the patient's QR code
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Drug Row Template -->
    <template id="drugRowTemplate">
        <div class="drug-row">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Medicine <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <select class="form-control select2-drug" name="drugs[INDEX][drug_id]"></select>
                        </div>
                        <input type="text" class="form-control drug-name-input" name="drugs[INDEX][drug_name]" 
                               placeholder="Or type drug name manually" style="max-width: 250px;">
                    </div>
                    <div class="drug-actions">
                        <button type="button" class="btn btn-outline-primary btn-sm change-brand-btn">
                            <i class='bx bx-refresh'></i> Change Brand
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm select-manufacturer-btn">
                            <i class='bx bx-building'></i> Select Manufacturer
                        </button>
                    </div>
                    <div class="drug-info">
                        <strong>Manufacturer:</strong> N/A | <strong>Price:</strong> N/A | <strong>Class:</strong> N/A
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label class="form-label">Frequency <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2">
                        <select class="form-select frequency-template" style="max-width: 150px;">
                            <option value="">Template</option>
                            <?php foreach ($templates['frequency'] as $template): ?>
                                <option value="<?php echo htmlspecialchars($template['frequency']); ?>">
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" name="drugs[INDEX][frequency]" placeholder="e.g. 1+0+1" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2">
                        <select class="form-select duration-template" style="max-width: 150px;">
                            <option value="">Template</option>
                            <?php foreach ($templates['duration'] as $template): ?>
                                <option value="<?php echo htmlspecialchars($template['duration']); ?>">
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" name="drugs[INDEX][duration]" placeholder="e.g. 7 days" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Instructions</label>
                    <div class="d-flex gap-2">
                        <select class="form-select instruction-template" style="max-width: 150px;">
                            <option value="">Template</option>
                            <?php foreach ($templates['instruction'] as $template): ?>
                                <option value="<?php echo htmlspecialchars($template['instruction']); ?>">
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" name="drugs[INDEX][instructions]" placeholder="e.g. After meal">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-danger btn-sm remove-drug">
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
        // Quick next visit setter
        function setNextVisit(days) {
            const date = new Date();
            date.setDate(date.getDate() + days);
            document.querySelector('input[name="next_visit"]').value = date.toISOString().split('T')[0];
        }

        $(document).ready(function() {
            // Initialize Select2
            $('.select2-patient').select2({ 
                theme: 'bootstrap-5', 
                width: '100%', 
                placeholder: 'Search or select patient...',
                allowClear: true
            });
            
            $('.select2-template').select2({ 
                theme: 'bootstrap-5', 
                width: '100%', 
                placeholder: 'Select template...',
                allowClear: true
            });

            // EDD Calculator
            $('#calculate_edd').on('click', function() {
                const lmpInput = $('#lmp_date').val();
                if (!lmpInput) {
                    alert('Please select LMP date');
                    return;
                }
                const lmpDate = new Date(lmpInput);
                const eddDate = new Date(lmpDate);
                eddDate.setDate(eddDate.getDate() + 280);
                const lmpFormatted = lmpDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
                const eddFormatted = eddDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
                const textToInsert = `LMP: ${lmpFormatted}\nEDD: ${eddFormatted}`;
                const current = $('#medical_history').val().trim();
                $('#medical_history').val(current ? current + "\n" + textToInsert : textToInsert);
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
                const textToInsert = `BMI: ${bmi} (${category}) - Height: ${heightIn}", Weight: ${weightKg} kg`;
                const current = $('#medical_history').val().trim();
                $('#medical_history').val(current ? current + "\n" + textToInsert : textToInsert);
                $('#height_in').val('');
                $('#weight_kg').val('');
            });

            // Prescription Search
            $('.select2-prescription').select2({
                theme: 'bootstrap-5',
                width: '100%',
                ajax: {
                    url: 'search_prescriptions.php',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ search: params.term || '', doctor_id: <?php echo $_SESSION['user_id']; ?> }),
                    processResults: data => ({
                        results: data.map(p => ({ id: p.id, text: `${p.patient_name} (${p.date})` }))
                    }),
                    cache: true
                },
                placeholder: 'Search by patient name or date...',
                allowClear: true
            });

            // Drug Select2 initialization
            function initializeDrugSelect($select) {
                $select.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    ajax: {
                        url: 'search_drugs.php',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({ search: params.term || '', page: params.page || 1, limit: 50 }),
                        processResults: (data, params) => ({
                            results: data.results,
                            pagination: { more: (params.page * 50) < data.total_count }
                        }),
                        cache: true
                    },
                    placeholder: 'Search for a drug...',
                    allowClear: true,
                    minimumInputLength: 1
                }).on('select2:select', function(e) {
                    const data = e.params.data;
                    const $row = $(this).closest('.drug-row');
                    if (data.id && data.id !== data.text) {
                        $row.find('.drug-info').html(
                            '<strong>Manufacturer:</strong> ' + (data.manufacturer || 'N/A') + ' | ' +
                            '<strong>Price:</strong> ' + (data.price || 'N/A') + ' | ' +
                            '<strong>Class:</strong> ' + (data.drug_class || 'N/A')
                        );
                        $row.data('generic_name', data.generic_name);
                        $row.data('strength', data.strength);
                        $row.find('.drug-name-input').val('').hide();
                    }
                });
            }

            // Add Drug Button
            $('#addDrug').click(function() {
                const index = $('#drugsList .drug-row').length;
                const template = $('#drugRowTemplate').html().replace(/INDEX/g, index);
                $('#drugsList').append(template);
                initializeDrugSelect($('#drugsList .drug-row:last .select2-drug'));
            });

            // Remove Drug
            $(document).on('click', '.remove-drug', function() {
                $(this).closest('.drug-row').fadeOut(300, function() { $(this).remove(); });
            });

            // Template insertion handlers
            function insertTemplate(templateSelect, targetTextarea) {
                const value = $(templateSelect).val();
                if (value) {
                    const current = $(targetTextarea).val();
                    $(targetTextarea).val(current ? `${current}\n${value}` : value);
                    $(templateSelect).val('').trigger('change');
                }
            }

            $('#insertChiefComplaintTemplate').click(() => insertTemplate('#chiefComplaintTemplates', '#chief_complaints'));
            $('#insertMedicalHistoryTemplate').click(() => insertTemplate('#medicalHistoryTemplates', '#medical_history'));
            $('#insertExaminationFindingsTemplate').click(() => insertTemplate('#examinationFindingsTemplates', '#examination_findings'));
            $('#insertDiagnosisTemplate').click(() => insertTemplate('#diagnosisTemplates', '#diagnosis'));
            $('#insertInvestigationTemplate').click(() => insertTemplate('#investigationTemplates', '#investigation'));
            $('#insertAdviceTemplate').click(() => insertTemplate('#adviceTemplates', '#advice'));

            // Drug template changes
            $(document).on('change', '.frequency-template', function() {
                if ($(this).val()) $(this).closest('.d-flex').find('input').val($(this).val());
            });
            $(document).on('change', '.duration-template', function() {
                if ($(this).val()) $(this).closest('.d-flex').find('input').val($(this).val());
            });
            $(document).on('change', '.instruction-template', function() {
                if ($(this).val()) $(this).closest('.d-flex').find('input').val($(this).val());
            });

            // Add initial drug row
            if ($('#drugsList').children().length === 0) $('#addDrug').click();

            // Save Patient
            $('#savePatient').click(function() {
                const formData = $('#quickAddPatientForm').serialize();
                if (!$('#quickAddPatientForm input[name="name"]').val().trim()) {
                    alert('Patient name is required');
                    return;
                }
                $.ajax({
                    url: 'ajax_add_patient.php',
                    type: 'POST',
                    data: formData,
                    beforeSend: () => {
                        $('#savePatient').prop('disabled', true).html('<span class="loading-spinner me-2"></span>Saving...');
                    },
                    success: function(response) {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;
                        if (data.success) {
                            const newOption = new Option(
                                `${data.patient.name} (${data.patient.patient_uid}) - ${data.patient.age}y/${data.patient.sex}`,
                                data.patient.id, true, true
                            );
                            $('select[name="patient_id"]').append(newOption).trigger('change');
                            $('#addPatientModal').modal('hide');
                            $('#quickAddPatientForm')[0].reset();
                        } else {
                            alert(data.message || 'Error adding patient');
                        }
                    },
                    complete: () => {
                        $('#savePatient').prop('disabled', false).html('<i class="bx bx-save"></i> Save Patient');
                    },
                    error: xhr => alert('Error: ' + (xhr.responseText || 'Unknown error'))
                });
            });

            // Treatment Template
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
                                if (drug.drug_id) {
                                    $row.find('.select2-drug').append(new Option(
                                        `${drug.brand_name} (${drug.generic_name}) ${drug.strength}`,
                                        drug.drug_id, true, true
                                    )).trigger('change');
                                }
                                $row.find('input[name$="[frequency]"]').val(drug.frequency || '');
                                $row.find('input[name$="[duration]"]').val(drug.duration || '');
                                $row.find('input[name$="[instructions]"]').val(drug.instructions || '');
                            });
                        }
                        ['chief_complaints', 'medical_history', 'examination_findings', 'diagnosis', 'investigation', 'advice'].forEach(field => {
                            if (response.template[field]) $(`textarea[name="${field}"]`).val(response.template[field]);
                        });
                    }
                });
            });
        });

        // QR Scanner
        let scannerActive = false;
        document.getElementById('scanPatientModal').addEventListener('shown.bs.modal', function() {
            if (scannerActive) return;
            const html5QrCode = new Html5Qrcode("qr-reader-patient");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    const cleanId = decodedText.trim().replace(/[^A-Za-z0-9]/g, '');
                    fetch(`ajax_search_patient.php?q=${encodeURIComponent(cleanId)}`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.patients && data.patients.length > 0) {
                                const p = data.patients[0];
                                const select = document.querySelector('select[name="patient_id"]');
                                const newOpt = new Option(`${p.name} (${p.patient_uid}) - ${p.age}y/${p.sex}`, p.id, true, true);
                                select.appendChild(newOpt);
                                $(select).trigger('change');
                                bootstrap.Modal.getInstance(document.getElementById('scanPatientModal')).hide();
                            }
                        });
                    html5QrCode.stop();
                    scannerActive = false;
                },
                () => {}
            ).then(() => scannerActive = true);
        });

        document.getElementById('scanPatientModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('qr-reader-patient').innerHTML = '';
            scannerActive = false;
        });
    </script>
</body>
</html>
