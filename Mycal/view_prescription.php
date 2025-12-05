<?php
require_once 'config.php';
requireLogin();

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No prescription ID provided";
    header("Location: prescriptions.php");
    exit();
}

$prescription_id = intval($_GET['id']);

if ($prescription_id <= 0) {
    $_SESSION['error'] = "Invalid prescription ID";
    header("Location: prescriptions.php");
    exit();
}

$sql = "SELECT p.*, pt.name as patient_name, pt.age, pt.sex, pt.weight, pt.phone, u.name as doctor_name 
        FROM prescriptions p 
        INNER JOIN patients pt ON p.patient_id = pt.id 
        INNER JOIN users u ON p.doctor_id = u.id 
        WHERE p.id = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $prescription_id);
    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['error'] = "Database error: " . mysqli_error($conn);
        header("Location: prescriptions.php");
        exit();
    }
    $result = mysqli_stmt_get_result($stmt);
    $prescription = mysqli_fetch_assoc($result);
    
    if (!$prescription) {
        $_SESSION['error'] = "Prescription not found";
        header("Location: prescriptions.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "Database error: " . mysqli_error($conn);
    header("Location: prescriptions.php");
    exit();
}

$sql = "SELECT *, prescription_header, prescription_footer FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $prescription['doctor_id']);
mysqli_stmt_execute($stmt);
$doctor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$clinic_info = array(
    'name' => 'Medical Clinic',
    'address' => $doctor['chamber_address'] ?? '',
    'phone' => $doctor['phone'] ?? '',
    'email' => $doctor['email'] ?? ''
);

$doctor_info = array(
    'name' => $doctor['name'] ?? '',
    'qualification' => $doctor['qualification'] ?? '',
    'specialization' => $doctor['specialization'] ?? '',
    'registration' => $doctor['registration_no'] ?? '',
    'phone' => $doctor['phone'] ?? '',
    'email' => $doctor['email'] ?? ''
);

$sql = "SELECT pd.*, d.brand_name, d.generic_name, d.strength, 
        COALESCE(d.dose_form, '') as dose_form 
        FROM prescription_drugs pd 
        LEFT JOIN drugs d ON pd.drug_id = d.id 
        WHERE pd.prescription_id = ?";

$medicines = [];
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $prescription_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $medicines[] = $row;
    }
}

$qrData = 'Patient ID: ' . $prescription['patient_id'] . 
         ' | Prescription ID: P' . str_pad($prescription['id'], 6, '0', STR_PAD_LEFT) . 
         ' | Name: ' . $prescription['patient_name'];

function isBangla($text) {
    return preg_match('/[\x{0980}-\x{09FF}]/u', $text);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Prescription - <?php echo htmlspecialchars($prescription['patient_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/siyam-rupali/font.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
        @media print {
            .no-print { display: none !important; }
            body { 
                font-size: 12px;
                padding: 0 !important;
                margin: 0 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .prescription-paper {
                margin: 0 !important;
                padding: 1cm !important;
                box-shadow: none !important;
                border: none !important;
                width: 21cm !important;
                height: 29.7cm !important;
                overflow: hidden;
                background: white !important;
            }
            .section { break-inside: avoid; }
            .medicine-item { break-inside: avoid; }
            .prescription-footer {
                position: fixed;
                bottom: 1cm;
                left: 1cm;
                right: 1cm;
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                padding: 0 2rem;
            }
            .clinic-header {
                padding: 0 2rem 1rem;
            }
            .background-layer {
                display: block !important;
                opacity: 0.15 !important;
                background-size: 50% !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
            }
        }

        .prescription-paper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            padding: 2rem;
            position: relative;
            width: 21cm;
            min-height: 29.7cm;
            margin: 2rem auto;
            border: none;
            color: #000;
            overflow: hidden;
        }

        .background-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-size: 50%;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.1;
            pointer-events: none;
            transition: background-image 0.3s ease;
        }
        .bg-none { background-image: none; }
        .bg-medical { background-image: url('https://www.transparenttextures.com/patterns/medical-background.png'); }
        .bg-stethoscope { background-image: url('https://cdn-icons-png.flaticon.com/512/33/33483.png'); }
        .bg-heart { background-image: url('https://cdn-icons-png.flaticon.com/512/520/520439.png'); }
        .bg-caduceus { background-image: url('https://cdn-icons-png.flaticon.com/512/33/33707.png'); }

        .font-selector-container {
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .bangla-text { line-height: 1.4; }
        .english-text { line-height: 1.2; }

        .clinic-header {
            justify-content: flex-start;
            padding: 0 2rem 0.5rem;
            margin-bottom: 0.7rem;
            border-bottom: 2px solid #eee;
        }

        .clinic-info { flex: 1; }
        .clinic-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.2;
        }
        .clinic-info .text-muted {
            font-size: 1.1rem;
            line-height: 1.2;
            white-space: pre-line;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .clinic-info p {
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        .prescription-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 2rem;
            margin-top: 2rem;
            border-top: 2px solid #eee;
        }

        .qr-section img { 
            width: 60px; 
            height: 60px; 
        }
        .footer-text { text-align: center; font-size: 0.8rem; color: #000; flex: 1; }
        .doctor-signature { text-align: right; padding-top: 0.6rem; border-top: 1px solid #eee; min-width: 200px; }
        .doctor-name { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.3rem; color: #000; }
        .text-muted { font-size: 0.82rem; line-height: 1.2; color: #000 !important; }
        .text-right { text-align: right !important; }

        .patient-info-bar {
            background: rgba(52,152,219,0.01);
            border: none;
            padding: 0 2rem;
            margin-bottom: 0rem;
            display: flex;
            flex-wrap: nowrap;
            gap: 1rem;
            align-items: flex-start;
            overflow-x: auto;
            white-space: nowrap;
        }

        .patient-info-bar::-webkit-scrollbar { display: none; }
        .patient-info-item { display: flex; align-items: center; gap: 0.2rem; font-size: 0.82rem; white-space: nowrap; margin: 0; padding: 0; color: #000; }
        .patient-info-label { color: #000; font-size: 0.85rem; }
        .patient-info-item strong { font-weight: 500; color: #000; }

        .prescription-body { display: grid; grid-template-columns: 30% 70%; gap: 1.2rem; padding: 0 2rem; margin-bottom: 1.2rem; }
        .left-column, .right-column { display: flex; flex-direction: column; gap: 0.6rem; }
        .section { background: white; border: none; padding: 0.8rem 0; margin-bottom: 0rem; }
        .section-title { color: #000; font-size: 0.9rem; font-weight: 600; margin-bottom: 0rem; padding-bottom: 0.3rem; border-bottom: 1px solid #fafafa; }
        .vitals-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
        .vital-item { background: rgba(52,152,219,0.03); padding: 0.3rem 0.5rem; border-radius: 4px; font-size: 0.85rem; color: #000; }
        .medicine-item { margin-bottom: 0.3rem; padding-bottom: 0.2rem; border-bottom: none; color: #000; line-height: 1.1; }
        .medicine-name { font-weight: 600; color: #000; margin-bottom: 0.15rem; font-size: 0.92rem; }
        .medicine-details { display: flex; gap: 0.7rem; font-size: 0.85rem; color: #000; margin-left: 0.7rem; line-height: 1.1; }
        .rx-symbol { font-size: 1.5rem; color: #000; margin-bottom: -0.5rem; font-weight: bold; font-family: "Arial", serif; }
        
        .export-button, .btn-success, .btn-primary, .btn-info {
            margin-bottom: 0.5rem;
        }
        
        #loadingIndicator {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem;
            border-radius: 8px;
            z-index: 1000;
        }

        /* Existing Layouts (abbreviated for brevity) */
        .layout-2 .prescription-body { grid-template-columns: 1fr; gap: 2rem; }
        .layout-2 .left-column, .layout-2 .right-column { width: 100%; }
        .layout-3 .prescription-body { display: flex; flex-direction: column; }
        .layout-3 .left-column { order: 2; }
        .layout-3 .right-column { order: 1; }
        .layout-4 .prescription-body { grid-template-columns: 40% 60%; }
        .layout-4 .section { border: 1px solid #eee; padding: 1rem; border-radius: 5px; }
        .layout-5 .prescription-body { grid-template-columns: 1fr; }
        .layout-5 .section { background: #f9f9f9; padding: 1rem; border-radius: 8px; }
        .layout-5 .prescription-footer { background: #f0f0f0; padding: 1rem 2rem; }
        .layout-6 .prescription-body { display: flex; flex-direction: column; gap: 1.5rem; }
        .layout-6 .section { border-left: 4px solid #3498db; padding-left: 1rem; background: #f8f9fa; }
        .layout-6 .medicine-item { background: white; padding: 0.5rem; margin: 0.3rem 0; border-radius: 4px; }
        .layout-7 .prescription-body { grid-template-columns: 35% 65%; gap: 1.5rem; }
        .layout-7 .section { box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 1rem; border-radius: 6px; }
        .layout-7 .prescription-footer { background: linear-gradient(to right, #f8f9fa, #ffffff); padding: 1rem 2rem; }
        .layout-8 .prescription-body { display: flex; flex-direction: column; gap: 2rem; }
        .layout-8 .left-column, .layout-8 .right-column { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 1rem; }
        .layout-8 .section-title { background: #3498db; color: white; padding: 0.3rem 0.8rem; border-radius: 4px; margin-bottom: 0.5rem; }
        .layout-8 .medicine-item { border-bottom: 1px dashed #eee; padding-bottom: 0.5rem; margin-bottom: 0.5rem; }
        .layout-9 .prescription-paper { background: linear-gradient(135deg, #ffffff 0%, #f0f4f8 100%); border: 2px solid #4a90e2; border-radius: 15px; }
        .layout-9 .clinic-header { background: #4a90e2; color: white; padding: 1rem 2rem; border-radius: 12px 12px 0 0; margin: -2rem -2rem 1rem; border-bottom: none; }
        .layout-9 .clinic-name { color: white; font-size: 1.4rem; }
        .layout-9 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-9 .patient-info-bar { background: #f8f9fa; border-radius: 8px; padding: 0.8rem 2rem; margin: 0 0 1rem; border: 1px solid #e9ecef; }
        .layout-9 .prescription-body { grid-template-columns: 35% 65%; gap: 1.5rem; }
        .layout-9 .section { background: rgba(255,255,255,0.9); padding: 1rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .layout-9 .section-title { background: linear-gradient(to right, #4a90e2, #63b3ed); color: white; padding: 0.5rem 1rem; border-radius: 6px; margin: -1rem -1rem 0.8rem; border-bottom: none; }
        .layout-9 .vital-item { background: #e6f3ff; border-left: 3px solid #4a90e2; }
        .layout-9 .medicine-item { background: #f0f9ff; padding: 0.8rem; margin: 0.5rem 0; border-radius: 6px; border-left: 4px solid #63b3ed; }
        .layout-9 .prescription-footer { background: #f8f9fa; border-top: 2px solid #4a90e2; padding: 1rem 2rem; margin: 1rem -2rem -2rem; border-radius: 0 0 12px 12px; }
        .layout-9 .qr-section img { border: 2px solid #4a90e2; border-radius: 5px; padding: 3px; background: white; }
        .layout-9 .doctor-signature { background: rgba(255,255,255,0.9); padding: 0.8rem; border-radius: 8px; border-top: none; }
        .layout-10 .prescription-paper { background: linear-gradient(to bottom, #f7faf7 0%, #ffffff 100%); border: 2px solid #2ecc71; border-radius: 10px; }
        .layout-10 .clinic-header { background: #2ecc71; color: white; padding: 1rem 2rem; border-radius: 8px 8px 0 0; margin: -2rem -2rem 1rem; border-bottom: none; }
        .layout-10 .clinic-name { color: white; font-size: 1.3rem; }
        .layout-10 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-10 .patient-info-bar { background: #ecf9f2; padding: 0.8rem 2rem; border-radius: 6px; border: 1px solid #d4ede0; }
        .layout-10 .prescription-body { grid-template-columns: 33% 67%; gap: 1.5rem; }
        .layout-10 .section { background: rgba(255,255,255,0.95); padding: 1rem; border-radius: 8px; border: 1px solid #e6f0ea; }
        .layout-10 .section-title { color: #2ecc71; border-bottom: 2px solid #2ecc71; padding-bottom: 0.3rem; }
        .layout-10 .vital-item { background: #f0faf5; border: 1px solid #b7e4c7; }
        .layout-10 .medicine-item { background: #f0faf5; padding: 0.7rem; margin: 0.4rem 0; border-radius: 5px; border: 1px dashed #2ecc71; }
        .layout-10 .prescription-footer { background: #ecf9f2; border-top: 2px solid #2ecc71; padding: 1rem 2rem; margin: 1rem -2rem -2rem; border-radius: 0 0 8px 8px; }
        .layout-10 .qr-section img { border: 2px solid #2ecc71; border-radius: 4px; background: white; }
        .layout-10 .doctor-signature { background: rgba(255,255,255,0.95); padding: 0.7rem; border-radius: 6px; }
        .layout-11 .prescription-paper { background: linear-gradient(45deg, #f9f5ff 0%, #ffffff 100%); border: 2px solid #9b59b6; border-radius: 12px; }
        .layout-11 .clinic-header { background: linear-gradient(to right, #9b59b6, #8e44ad); color: white; padding: 1rem 2rem; border-radius: 10px 10px 0 0; margin: -2rem -2rem 1rem; border-bottom: none; }
        .layout-11 .clinic-name { color: white; font-size: 1.35rem; }
        .layout-11 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-11 .patient-info-bar { background: #f5f0fa; padding: 0.8rem 2rem; border-radius: 6px; border: 1px solid #e8dcef; }
        .layout-11 .prescription-body { grid-template-columns: 35% 65%; gap: 1.8rem; }
        .layout-11 .section { background: rgba(255,255,255,0.9); padding: 1.2rem; border-radius: 10px; box-shadow: 0 3px 15px rgba(155,89,182,0.1); }
        .layout-11 .section-title { background: #9b59b6; color: white; padding: 0.5rem 1rem; border-radius: 5px; margin: -1.2rem -1.2rem 1rem; }
        .layout-11 .vital-item { background: #f5f0fa; border-left: 4px solid #9b59b6; }
        .layout-11 .medicine-item { background: #faf8fc; padding: 0.8rem; margin: 0.5rem 0; border-radius: 6px; border-left: 4px solid #8e44ad; }
        .layout-11 .prescription-footer { background: #f5f0fa; border-top: 2px solid #9b59b6; padding: 1rem 2rem; margin: 1rem -2rem -2rem; border-radius: 0 0 10px 10px; }
        .layout-11 .qr-section img { border: 3px solid #9b59b6; border-radius: 6px; background: white; }
        .layout-11 .doctor-signature { background: rgba(255,255,255,0.9); padding: 0.8rem; border-radius: 8px; }
        .layout-12 .prescription-paper { background: linear-gradient(to top, #fffaf5 0%, #ffffff 100%); border: 2px solid #e67e22; border-radius: 10px; }
        .layout-12 .clinic-header { background: #e67e22; color: white; padding: 1rem 2rem; border-radius: 8px 8px 0 0; margin: -2rem -2rem 1rem; border-bottom: none; }
        .layout-12 .clinic-name { color: white; font-size: 1.3rem; }
        .layout-12 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-12 .patient-info-bar { background: #fef5ed; padding: 0.8rem 2rem; border-radius: 6px; border: 1px solid #f9e4d4; }
        .layout-12 .prescription-body { grid-template-columns: 32% 68%; gap: 1.5rem; }
        .layout-12 .section { background: rgba(255,255,255,0.95); padding: 1rem; border-radius: 8px; border: 1px solid #f9e4d4; }
        .layout-12 .section-title { color: #e67e22; border-bottom: 2px dashed #e67e22; padding-bottom: 0.4rem; }
        .layout-12 .vital-item { background: #fef5ed; border: 1px solid #f9c9a2; }
        .layout-12 .medicine-item { background: #fff8f2; padding: 0.7rem; margin: 0.4rem 0; border-radius: 5px; border: 1px solid #f9c9a2; }
        .layout-12 .prescription-footer { background: #fef5ed; border-top: 2px solid #e67e22; padding: 1rem 2rem; margin: 1rem -2rem -2rem; border-radius: 0 0 8px 8px; }
        .layout-12 .qr-section img { border: 2px solid #e67e22; border-radius: 4px; background: white; }
        .layout-12 .doctor-signature { background: rgba(255,255,255,0.95); padding: 0.7rem; border-radius: 6px; }

        /* New Layout 13 - Teal Elegant */
        .layout-13 .prescription-paper {
            background: linear-gradient(to bottom right, #f0fbfc 0%, #ffffff 100%);
            border: 2px solid #16a085;
            border-radius: 14px;
        }
        .layout-13 .clinic-header {
            background: linear-gradient(to right, #16a085, #1abc9c);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px 12px 0 0;
            margin: -2rem -2rem 1rem;
            border-bottom: none;
        }
        .layout-13 .clinic-name { color: white; font-size: 1.35rem; }
        .layout-13 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-13 .patient-info-bar {
            background: #effffb;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            border: 1px solid #d1f1eb;
            margin: 0 0 1rem;
        }
        .layout-13 .prescription-body { grid-template-columns: 34% 66%; gap: 1.6rem; }
        .layout-13 .section {
            background: rgba(255,255,255,0.95);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #e6f6f3;
            box-shadow: 0 2px 8px rgba(22,160,133,0.1);
        }
        .layout-13 .section-title {
            background: #16a085;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin: -1rem -1rem 0.8rem;
        }
        .layout-13 .vital-item { background: #effffb; border-left: 3px solid #1abc9c; }
        .layout-13 .medicine-item {
            background: #f7fdfb;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border-radius: 6px;
            border-left: 4px solid #1abc9c;
        }
        .layout-13 .prescription-footer {
            background: #effffb;
            border-top: 2px solid #16a085;
            padding: 1rem 2rem;
            margin: 1rem -2rem -2rem;
            border-radius: 0 0 12px 12px;
        }
        .layout-13 .qr-section img { border: 2px solid #16a085; border-radius: 5px; padding: 2px; background: white; }
        .layout-13 .doctor-signature { background: rgba(255,255,255,0.95); padding: 0.8rem; border-radius: 8px; }

        /* New Layout 14 - Red Professional */
        .layout-14 .prescription-paper {
            background: linear-gradient(to top, #fff5f5 0%, #ffffff 100%);
            border: 2px solid #c0392b;
            border-radius: 10px;
        }
        .layout-14 .clinic-header {
            background: #c0392b;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px 8px 0 0;
            margin: -2rem -2rem 1rem;
            border-bottom: none;
        }
        .layout-14 .clinic-name { color: white; font-size: 1.3rem; }
        .layout-14 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-14 .patient-info-bar {
            background: #fef0ef;
            padding: 0.8rem 2rem;
            border-radius: 6px;
            border: 1px solid #fadbd8;
        }
        .layout-14 .prescription-body { grid-template-columns: 33% 67%; gap: 1.5rem; }
        .layout-14 .section {
            background: rgba(255,255,255,0.95);
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #fadbd8;
        }
        .layout-14 .section-title {
            color: #c0392b;
            border-bottom: 2px solid #c0392b;
            padding-bottom: 0.3rem;
        }
        .layout-14 .vital-item { background: #fef0ef; border: 1px solid #e6b0aa; }
        .layout-14 .medicine-item {
            background: #fff5f5;
            padding: 0.7rem;
            margin: 0.4rem 0;
            border-radius: 5px;
            border: 1px dashed #c0392b;
        }
        .layout-14 .prescription-footer {
            background: #fef0ef;
            border-top: 2px solid #c0392b;
            padding: 1rem 2rem;
            margin: 1rem -2rem -2rem;
            border-radius: 0 0 8px 8px;
        }
        .layout-14 .qr-section img { border: 2px solid #c0392b; border-radius: 4px; background: white; }
        .layout-14 .doctor-signature { background: rgba(255,255,255,0.95); padding: 0.7rem; border-radius: 6px; }

        /* New Layout 15 - Indigo Modern */
        .layout-15 .prescription-paper {
            background: linear-gradient(135deg, #f5f7ff 0%, #ffffff 100%);
            border: 2px solid #3f51b5;
            border-radius: 12px;
        }
        .layout-15 .clinic-header {
            background: linear-gradient(to right, #3f51b5, #5c6bc0);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px 10px 0 0;
            margin: -2rem -2rem 1rem;
            border-bottom: none;
        }
        .layout-15 .clinic-name { color: white; font-size: 1.35rem; }
        .layout-15 .clinic-info .text-muted { color: rgba(255,255,255,0.9) !important; }
        .layout-15 .patient-info-bar {
            background: #eef2ff;
            padding: 0.8rem 2rem;
            border-radius: 6px;
            border: 1px solid #d7defa;
        }
        .layout-15 .prescription-body { grid-template-columns: 35% 65%; gap: 1.8rem; }
        .layout-15 .section {
            background: rgba(255,255,255,0.9);
            padding: 1.2rem;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(63,81,181,0.1);
        }
        .layout-15 .section-title {
            background: #3f51b5;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin: -1.2rem -1.2rem 1rem;
        }
        .layout-15 .vital-item { background: #eef2ff; border-left: 4px solid #3f51b5; }
        .layout-15 .medicine-item {
            background: #f7f9ff;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border-radius: 6px;
            border-left: 4px solid #5c6bc0;
        }
        .layout-15 .prescription-footer {
            background: #eef2ff;
            border-top: 2px solid #3f51b5;
            padding: 1rem 2rem;
            margin: 1rem -2rem -2rem;
            border-radius: 0 0 10px 10px;
        }
        .layout-15 .qr-section img { border: 3px solid #3f51b5; border-radius: 6px; background: white; }
        .layout-15 .doctor-signature { background: rgba(255,255,255,0.9); padding: 0.8rem; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">
    <div class="no-print">
        <?php include 'navbar.php'; ?>
    </div>

    <div class="container-fluid">
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary print-button me-2">
                <i class='bx bx-printer me-2'></i> Print
            </button>
         

<!-- ADD THIS IN view_prescription.php (after print buttons) -->
<div class="text-center mt-5 p-4 bg-light rounded border">
    <h4 class="text-success mb-3">Share Prescription with Patient</h4>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="bg-white p-4 rounded shadow-sm">
                <h6>Scan QR Code</h6>
                <?php 
                // CORRECT TOKEN: Use URL-safe base64 + padding fix
                $raw = $prescription_id;
                $token = rtrim(strtr(base64_encode($raw), '+/', '-_'), '='); 
                $public_url = "https://afifbashar.unaux.com/public_prescription.php?token=" . $token;
                ?>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?=urlencode($public_url)?>" 
                     alt="QR Code" class="img-fluid border rounded">
                <p class="mt-2 text-muted"><small>Patient scans → sees prescription</small></p>
            </div>
        </div>

        <div class="col-md-5">
            <div class="bg-white p-4 rounded shadow-sm">
                <h6>Or Send This Link</h6>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?=$public_url?>" readonly id="shareLink">
                    <button class="btn btn-primary" onclick="copyLink()">Copy</button>
                </div>
                <div class="mt-3">
                    <a href="https://wa.me/?text=<?=urlencode("Your Prescription: " . $public_url)?>" 
                       target="_blank" class="btn btn-success btn-sm me-2">
                       WhatsApp
                    </a>
                    <a href="sms:?body=<?=urlencode("Your Prescription: " . $public_url)?>" 
                       class="btn btn-info btn-sm text-white">
                       SMS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    const link = document.getElementById('shareLink');
    link.select();
    document.execCommand('copy');
    alert('Link copied!');
}
</script>
            <button onclick="exportAsJPG()" class="btn btn-success export-button me-2">
                <i class='bx bx-download me-2'></i> Save JPG
            </button>
            <button onclick="shareVia('whatsapp')" class="btn btn-success me-2">
                <i class='bx bxl-whatsapp me-2'></i> WhatsApp
            </button>
            <button onclick="shareVia('messenger')" class="btn btn-primary me-2">
                <i class='bx bxl-messenger me-2'></i> Messenger
            </button>
            <button onclick="shareVia('email')" class="btn btn-info me-2">
                <i class='bx bx-envelope me-2'></i> Email
            </button>
            
            <select id="medicineNameSelector" class="form-select d-inline-block w-auto me-2">
                <option value="brand">Brand Name Only</option>
                <option value="generic">Generic Name Only</option>
                <option value="brand-generic" selected>Brand + Generic</option>
                <option value="all">All Details</option>
            </select>
            
            <select id="resolutionSelector" class="form-select d-inline-block w-auto me-2">
                <option value="2">Standard (200 DPI)</option>
                <option value="3" selected>High (300 DPI)</option>
                <option value="4">Ultra (400 DPI)</option>
            </select>

            <select id="layoutSelector" class="form-select d-inline-block w-auto me-2">
                <option value="layout-1" selected>Layout 1 (Default - Two Columns)</option>
                <option value="layout-2">Layout 2 (Single Column)</option>
                <option value="layout-3">Layout 3 (Medicines First)</option>
                <option value="layout-4">Layout 4 (Wider Right Column)</option>
                <option value="layout-5">Layout 5 (Modern Single Column)</option>
                <option value="layout-6">Layout 6 (Vertical Sections with Accent)</option>
                <option value="layout-7">Layout 7 (Shadowed Boxes)</option>
                <option value="layout-8">Layout 8 (Card Style with Colored Headers)</option>
                <option value="layout-9">Layout 9 (Modern Blue Two Columns)</option>
                <option value="layout-10">Layout 10 (Green Professional Two Columns)</option>
                <option value="layout-11">Layout 11 (Purple Modern Two Columns)</option>
                <option value="layout-12">Layout 12 (Orange Professional Two Columns)</option>
                <option value="layout-13">Layout 13 (Teal Elegant Two Columns)</option>
                <option value="layout-14">Layout 14 (Red Professional Two Columns)</option>
                <option value="layout-15">Layout 15 (Indigo Modern Two Columns)</option>
            </select>

            <select id="backgroundSelector" class="form-select d-inline-block w-auto">
                <option value="bg-none" selected>No Background</option>
                <option value="bg-medical">Medical Pattern</option>
                <option value="bg-stethoscope">Stethoscope Logo</option>
                <option value="bg-heart">Heart Logo</option>
                <option value="bg-caduceus">Caduceus Symbol</option>
            </select>
        </div>

        <div id="loadingIndicator" class="no-print text-center d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Generating prescription image...</p>
        </div>

        <div class="font-selector-container no-print">
            <div>
                <label for="banglaFontSelector" class="me-2">Bangla Font:</label>
                <select id="banglaFontSelector" class="form-select d-inline-block w-auto">
                    <option value="Noto Sans Bengali">Noto Sans Bengali</option>
                    <option value="SolaimanLipi">SolaimanLipi</option>
                    <option value="Kalpurush">Kalpurush</option>
                    <option value="Siyam Rupali">Siyam Rupali</option>
                </select>
            </div>
            <div>
                <label for="englishFontSelector" class="me-2">English Font:</label>
                <select id="englishFontSelector" class="form-select d-inline-block w-auto">
                    <option value="Poppins">Poppins</option>
                    <option value="Roboto">Roboto</option>
                    <option value="Open Sans">Open Sans</option>
                    <option value="Lato">Lato</option>
                </select>
            </div>
        </div>

        <div class="prescription-paper layout-1" id="prescriptionPaper">
            <div id="backgroundLayer" class="bg-none background-layer"></div>
            <div class="clinic-header">
                <div class="clinic-info">
                    <?php if (!empty($doctor['prescription_header'])): ?>
                        <div class="text-muted <?php echo isBangla($doctor['prescription_header']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo html_entity_decode($doctor['prescription_header']); ?>
                        </div>
                    <?php else: ?>
                        <div class="clinic-name english-text"><?php echo htmlspecialchars($clinic_info['name']); ?></div>
                        <?php if ($clinic_info['address']): ?>
                            <div class="text-muted"><?php echo htmlspecialchars($clinic_info['address']); ?></div>
                        <?php endif; ?>
                        <?php if ($clinic_info['phone']): ?>
                            <div class="text-muted">Tel: <?php echo htmlspecialchars($clinic_info['phone']); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="patient-info-bar">
                <div class="patient-info-item">
                    <span class="patient-info-label english-text">Name:</span>
                    <strong class="<?php echo isBangla($prescription['patient_name']) ? 'bangla-text' : 'english-text'; ?>">
                        <?php echo htmlspecialchars($prescription['patient_name']); ?>
                    </strong>
                </div>
                <div class="patient-info-item">
                    <span class="patient-info-label english-text">Age/Sex:</span>
                    <strong class="english-text"><?php echo htmlspecialchars($prescription['age']); ?>y/<?php echo htmlspecialchars($prescription['sex']); ?></strong>
                </div>
                <?php if (!empty($prescription['weight'])): ?>
                <div class="patient-info-item">
                    <span class="patient-info-label english-text">Weight:</span>
                    <strong class="english-text"><?php echo htmlspecialchars($prescription['weight']); ?> kg</strong>
                </div>
                <?php endif; ?>
                <div class="patient-info-item">
                    <span class="patient-info-label english-text">Phone:</span>
                    <strong class="english-text"><?php echo htmlspecialchars($prescription['phone']); ?></strong>
                </div>
                <div class="patient-info-item">
                    <span class="patient-info-label english-text">Date:</span>
                   <strong class="english-text"><?php echo date('d-m-Y', strtotime($prescription['created_at'])); ?></strong>
                </div>
            </div>

            <div class="prescription-body">
                <div class="left-column">
                    <?php if (!empty($prescription['chief_complaints'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Chief Complaints</div>
                        <div class="<?php echo isBangla($prescription['chief_complaints']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo nl2br(htmlspecialchars($prescription['chief_complaints'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prescription['bp']) || !empty($prescription['pulse']) || 
                          !empty($prescription['temperature']) || !empty($prescription['spo2'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Vitals</div>
                        <div class="vitals-grid">
                            <?php if (!empty($prescription['bp'])): ?>
                            <div class="vital-item english-text">BP: <?php echo htmlspecialchars($prescription['bp']); ?> mmHg</div>
                            <?php endif; ?>
                            <?php if (!empty($prescription['pulse'])): ?>
                            <div class="vital-item english-text">Pulse: <?php echo htmlspecialchars($prescription['pulse']); ?> BPM</div>
                            <?php endif; ?>
                            <?php if (!empty($prescription['temperature'])): ?>
                            <div class="vital-item english-text">Temp: <?php echo htmlspecialchars($prescription['temperature']); ?> °F</div>
                            <?php endif; ?>
                            <?php if (!empty($prescription['spo2'])): ?>
                            <div class="vital-item english-text">SpO2: <?php echo htmlspecialchars($prescription['spo2']); ?>%</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prescription['medical_history'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Medical History</div>
                        <div class="<?php echo isBangla($prescription['medical_history']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo nl2br(htmlspecialchars($prescription['medical_history'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prescription['examination_findings'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Examination Findings</div>
                        <div class="<?php echo isBangla($prescription['examination_findings']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo nl2br(htmlspecialchars($prescription['examination_findings'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prescription['investigation'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Investigation</div>
                        <div class="<?php echo isBangla($prescription['investigation']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo nl2br(htmlspecialchars($prescription['investigation'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prescription['diagnosis'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Diagnosis</div>
                        <div class="<?php echo isBangla($prescription['diagnosis']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo nl2br(htmlspecialchars($prescription['diagnosis'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="right-column">
                    <div class="section">
                        <div class="rx-symbol">℞</div>
                        <?php foreach ($medicines as $medicine): ?>
                        <div class="medicine-item" data-medicine='<?php echo json_encode($medicine); ?>'>
                            <div class="medicine-name">
                                <span class="medicine-text <?php echo isBangla($medicine['brand_name'] . $medicine['generic_name']) ? 'bangla-text' : 'english-text'; ?>"></span>
                            </div>
                            <div class="medicine-details <?php echo isBangla($medicine['frequency'] . $medicine['duration'] . $medicine['instructions']) ? 'bangla-text' : 'english-text'; ?>">
                                <span><?php echo htmlspecialchars($medicine['frequency']); ?></span>
                                <span><?php echo htmlspecialchars($medicine['duration']); ?></span>
                                <?php if (!empty($medicine['instructions'])): ?>
                                <span><?php echo htmlspecialchars($medicine['instructions']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!empty($prescription['advice'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Advice</div>
                        <div class="<?php echo isBangla($prescription['advice']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo nl2br(htmlspecialchars($prescription['advice'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prescription['next_visit'])): ?>
                    <div class="section">
                        <div class="section-title english-text">Follow-up</div>
                        <div class="<?php echo isBangla($prescription['next_visit']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo htmlspecialchars($prescription['next_visit']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="prescription-footer">
                <div class="qr-section">
                 <h6>Scan QR Code</h6>
                <?php 
                // CORRECT TOKEN: Use URL-safe base64 + padding fix
                $raw = $prescription_id;
                $token = rtrim(strtr(base64_encode($raw), '+/', '-_'), '='); 
                $public_url = "https://afifbashar.unaux.com/public_prescription.php?token=" . $token;
                ?>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?=urlencode($public_url)?>" 

                    <div class="text-muted small mt-1 english-text">Scan for patient details</div>
                </div>
                <div class="footer-text <?php echo isBangla($doctor['prescription_footer'] ?? '') ? 'bangla-text' : 'english-text'; ?>">
                    <?php echo htmlspecialchars($doctor['prescription_footer'] ?? 'Confidential - For Patient Use Only'); ?>
                </div>
                <div class="doctor-signature">
                    <div class="doctor-name english-text">DR. <?php echo htmlspecialchars($doctor_info['name']); ?></div>
                    <?php if ($doctor_info['qualification']): ?>
                        <div class="text-muted <?php echo isBangla($doctor_info['qualification']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo htmlspecialchars($doctor_info['qualification']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($doctor_info['specialization']): ?>
                        <div class="text-muted <?php echo isBangla($doctor_info['specialization']) ? 'bangla-text' : 'english-text'; ?>">
                            <?php echo htmlspecialchars($doctor_info['specialization']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($doctor_info['registration']): ?>
                        <div class="text-muted english-text">Reg. No: <?php echo htmlspecialchars($doctor_info['registration']); ?></div>
                    <?php endif; ?>
                    <div class="text-muted small mt-2 english-text">Digitally Signed</div>
                    <div class="text-muted small english-text"><?php echo date('d/m/Y'); ?></div>
                </div>
            </div>
        </div>

        <div class="no-print text-center mt-4 mb-4">
            <a href="prescriptions.php" class="btn btn-light">
                <i class='bx bx-arrow-back me-1'></i> Back to List
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preload background images
        const backgroundImages = {
            'bg-medical': 'https://www.transparenttextures.com/patterns/medical-background.png',
            'bg-stethoscope': 'https://cdn-icons-png.flaticon.com/512/33/33483.png',
            'bg-heart': 'https://cdn-icons-png.flaticon.com/512/520/520439.png',
            'bg-caduceus': 'https://cdn-icons-png.flaticon.com/512/33/33707.png'
        };

        function preloadImages() {
            Object.values(backgroundImages).forEach(url => {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.src = url;
            });
        }

        function toggleLoading(show) {
            document.getElementById('loadingIndicator').classList.toggle('d-none', !show);
        }

        async function generateImage() {
            toggleLoading(true);
            const element = document.getElementById('prescriptionPaper');
            const scale = parseInt(document.getElementById('resolutionSelector').value);

            element.style.width = '21cm';
            element.style.height = '29.7cm';
            element.style.padding = '1cm';
            element.style.boxShadow = 'none';
            element.style.border = 'none';

            try {
                const canvas = await html2canvas(element, {
                    scale: scale,
                    useCORS: true,
                    backgroundColor: null,
                    logging: false,
                    allowTaint: false,
                    imageTimeout: 15000,
                    onclone: (doc) => {
                        const bgLayer = doc.getElementById('backgroundLayer');
                        const currentBg = document.getElementById('backgroundSelector').value;
                        if (currentBg !== 'bg-none') {
                            bgLayer.style.backgroundImage = `url(${backgroundImages[currentBg]})`;
                            bgLayer.style.opacity = '0.15';
                        }
                    }
                });
                
                const imgData = canvas.toDataURL('image/jpeg', 1.0);
                
                element.style.width = '21cm';
                element.style.minHeight = '29.7cm';
                element.style.padding = '2rem';
                element.style.boxShadow = '0 1px 4px rgba(0,0,0,0.03)';
                element.style.border = 'none';
                
                toggleLoading(false);
                return { imgData, canvas };
            } catch (error) {
                console.error('Error generating image:', error);
                alert('Error generating image');
                toggleLoading(false);
                throw error;
            }
        }

        function exportAsJPG() {
            generateImage().then(({ imgData }) => {
                const link = document.createElement('a');
                const patientName = '<?php echo htmlspecialchars($prescription['patient_name']); ?>';
                const date = '<?php echo date('d-m-Y'); ?>';
                link.download = `Prescription_${patientName}_${date}.jpg`;
                link.href = imgData;
                link.click();
            }).catch(error => {
                console.error('Error:', error);
                alert('Error generating JPG');
                toggleLoading(false);
            });
        }

        function shareVia(platform) {
            generateImage().then(({ imgData }) => {
                const patientName = '<?php echo htmlspecialchars($prescription['patient_name']); ?>';
                const date = '<?php echo date('d-m-Y'); ?>';
                const blob = dataURLtoBlob(imgData);
                const file = new File([blob], `Prescription_${patientName}_${date}.jpg`, { type: 'image/jpeg' });
                const filesArray = [file];
                const shareData = {
                    files: filesArray,
                    title: `Prescription for ${patientName}`,
                    text: 'Prescription attached'
                };

                if (navigator.canShare && navigator.canShare({ files: filesArray })) {
                    navigator.share(shareData).catch(error => {
                        console.error('Sharing failed:', error);
                        fallbackShare(platform, imgData, patientName, date);
                    });
                } else {
                    fallbackShare(platform, imgData, patientName, date);
                }
            }).catch(error => {
                console.error('Error generating image:', error);
                alert('Error preparing share content');
                toggleLoading(false);
            });
        }

        function fallbackShare(platform, imgData, patientName, date) {
            switch(platform) {
                case 'whatsapp':
                    window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent('Prescription attached: ' + imgData)}`);
                    break;
                case 'messenger':
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(imgData)}`);
                    break;
                case 'email':
                    const subject = `Prescription for ${patientName} - ${date}`;
                    const body = `Please find the prescription attached. Download from: ${imgData}`;
                    window.location.href = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                    break;
            }
        }

        function dataURLtoBlob(dataURL) {
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while(n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], { type: mime });
        }

        function updateMedicineDisplay() {
            const mode = document.getElementById('medicineNameSelector').value;
            document.querySelectorAll('.medicine-item').forEach(item => {
                const medicine = JSON.parse(item.dataset.medicine);
                const brand = medicine.brand_name || medicine.manual_brand_name || '';
                const generic = medicine.generic_name ? `(${medicine.generic_name})` : '';
                const strength = medicine.strength || medicine.manual_strength || '';
                const dose_form = medicine.dose_form || '';
                
                let displayText = '';
                switch(mode) {
                    case 'brand': displayText = `${brand} ${strength} ${dose_form}`; break;
                    case 'generic': displayText = `${generic} ${strength} ${dose_form}`; break;
                    case 'brand-generic': displayText = `${brand} ${generic} ${strength} ${dose_form}`; break;
                    case 'all': displayText = `${brand} ${generic} ${strength} ${dose_form}`; break;
                }
                
                item.querySelector('.medicine-text').textContent = displayText.trim();
            });
        }

        document.getElementById('banglaFontSelector').addEventListener('change', function() {
            const selectedBanglaFont = this.value;
            const banglaElements = document.querySelectorAll('.bangla-text');
            banglaElements.forEach(element => {
                element.style.fontFamily = `'${selectedBanglaFont}', sans-serif`;
            });
            localStorage.setItem('selectedBanglaFont', selectedBanglaFont);
        });

        document.getElementById('englishFontSelector').addEventListener('change', function() {
            const selectedEnglishFont = this.value;
            const englishElements = document.querySelectorAll('.english-text');
            englishElements.forEach(element => {
                element.style.fontFamily = `'${selectedEnglishFont}', sans-serif`;
            });
            localStorage.setItem('selectedEnglishFont', selectedEnglishFont);
        });

        document.getElementById('medicineNameSelector').addEventListener('change', updateMedicineDisplay);

        document.getElementById('layoutSelector').addEventListener('change', function() {
            const prescriptionPaper = document.getElementById('prescriptionPaper');
            prescriptionPaper.classList.remove('layout-1', 'layout-2', 'layout-3', 'layout-4', 'layout-5', 'layout-6', 'layout-7', 'layout-8', 'layout-9', 'layout-10', 'layout-11', 'layout-12', 'layout-13', 'layout-14', 'layout-15');
            prescriptionPaper.classList.add(this.value);
            localStorage.setItem('selectedLayout', this.value);
        });

        document.getElementById('backgroundSelector').addEventListener('change', function() {
            const backgroundLayer = document.getElementById('backgroundLayer');
            backgroundLayer.classList.remove('bg-none', 'bg-medical', 'bg-stethoscope', 'bg-heart', 'bg-caduceus');
            backgroundLayer.classList.add(this.value);
            if (this.value !== 'bg-none') {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.onload = () => {
                    backgroundLayer.style.backgroundImage = `url(${backgroundImages[this.value]})`;
                };
                img.src = backgroundImages[this.value];
            } else {
                backgroundLayer.style.backgroundImage = 'none';
            }
            localStorage.setItem('selectedBackground', this.value);
        });

        window.addEventListener('load', function() {
            preloadImages();
            const savedBanglaFont = localStorage.getItem('selectedBanglaFont') || 'Noto Sans Bengali';
            document.getElementById('banglaFontSelector').value = savedBanglaFont;
            const banglaElements = document.querySelectorAll('.bangla-text');
            banglaElements.forEach(element => {
                element.style.fontFamily = `'${savedBanglaFont}', sans-serif`;
            });

            const savedEnglishFont = localStorage.getItem('selectedEnglishFont') || 'Poppins';
            document.getElementById('englishFontSelector').value = savedEnglishFont;
            const englishElements = document.querySelectorAll('.english-text');
            englishElements.forEach(element => {
                element.style.fontFamily = `'${savedEnglishFont}', sans-serif`;
            });

            const savedLayout = localStorage.getItem('selectedLayout') || 'layout-1';
            document.getElementById('layoutSelector').value = savedLayout;
            const prescriptionPaper = document.getElementById('prescriptionPaper');
            prescriptionPaper.classList.remove('layout-1', 'layout-2', 'layout-3', 'layout-4', 'layout-5', 'layout-6', 'layout-7', 'layout-8', 'layout-9', 'layout-10', 'layout-11', 'layout-12', 'layout-13', 'layout-14', 'layout-15');
            prescriptionPaper.classList.add(savedLayout);

            const savedBackground = localStorage.getItem('selectedBackground') || 'bg-none';
            document.getElementById('backgroundSelector').value = savedBackground;
            const backgroundLayer = document.getElementById('backgroundLayer');
            backgroundLayer.classList.remove('bg-none', 'bg-medical', 'bg-stethoscope', 'bg-heart', 'bg-caduceus');
            backgroundLayer.classList.add(savedBackground);
            if (savedBackground !== 'bg-none') {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.onload = () => {
                    backgroundLayer.style.backgroundImage = `url(${backgroundImages[savedBackground]})`;
                };
                img.src = backgroundImages[savedBackground];
            }

            updateMedicineDisplay();
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
    <script>
functon copyLink() {
    const link = document.getElementById('publicLink');
    link.select();
    document.execCommand('copy');
    alert('Link copied to clipboqrd!');
}
</script>
</body>
</html>