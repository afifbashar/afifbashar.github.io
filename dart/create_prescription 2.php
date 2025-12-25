
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $is_template ? "Create Template" : "Create Prescription"; ?> - Prescription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --bg-body: #f1f5f9;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            --radius: 0.75rem;
            --radius-lg: 1rem;
            --transition: all 0.2s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #fbeaf6 100%);
            min-height: 100vh;
            color: var(--text-primary);
            background-attachment: fixed;
        }

        .prescription-wrapper {
            padding: 1.5rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-header {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-radius: var(--radius-lg);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header h1 i {
            font-size: 2.2rem;
            color: var(--primary);
        }

        .alert-modern {
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .card-modern {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition);
        }

        .card-modern:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header-modern {
            background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(6,182,212,0.08) 100%);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
        }

        .card-header-modern h5 {
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin: 0;
        }

        .card-header-modern h5 i {
            font-size: 1.4rem;
            color: var(--primary);
        }

        .toggle-icon {
            font-size: 1.4rem;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        .card-body-modern {
            padding: 1.5rem;
        }

        .form-label-modern {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .form-control-modern,
        .form-select-modern {
            border-radius: var(--radius);
            border: 1.5px solid var(--border-color);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control-modern:focus,
        .form-select-modern:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }

        textarea.form-control-modern {
            min-height: 110px;
            resize: vertical;
        }

        .btn-modern {
            border-radius: var(--radius);
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary-modern {
            background: var(--primary);
            border: none;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99,102,241,0.4);
        }

        .drug-row-modern {
            background: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .drug-row-number {
            position: absolute;
            top: -12px;
            left: 20px;
            background: var(--primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .drug-info-modern {
            background: #eef2ff;
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            margin-top: 0.75rem;
        }

        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1050;
            display: none;
        }

        .suggestions-dropdown .suggestion-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .suggestions-dropdown .suggestion-item:hover,
        .suggestions-dropdown .suggestion-item.focused {
            background: var(--primary-light);
            color: white;
        }

        .keyboard-hint {
            background: linear-gradient(135deg, #c7d2fe 0%, #e0e7ff 100%);
            border: 2px solid #a5b4fc;
            border-radius: var(--radius);
            padding: 1rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
        }

        .section-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 992px) {
            .section-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .full-width-section {
            grid-column: 1 / -1;
        }

        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (min-width: 576px) {
            .vitals-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="prescription-wrapper">
        <div class="page-header">
            <div>
                <h1>
                    <i class='bx bx-file-medical'></i>
                    <?php echo $is_template ? "Create Template" : "New Prescription"; ?>
                </h1>
                <p class="text-muted mb-0">Complete the sections below to create a <?php echo $is_template ? "template" : "prescription"; ?></p>
            </div>
            <div>
                <a href="prescriptions.php" class="btn btn-outline-secondary btn-modern">
                    <i class='bx bx-arrow-back'></i> Back
                </a>
            </div>
        </div>

        <div class="keyboard-hint">
            <strong>⚡ Keyboard Shortcuts:</strong> Ctrl+M (Add Drug) • Ctrl+S (Save) • Ctrl+1–6 (Jump to section) • Alt+T (Treatment Template) • Alt+P (Patient) • Esc (Close dropdowns)
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-modern">
                <i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($is_template ? "?template=1" : "")); ?>" id="prescriptionForm">
            <div class="section-grid">
                <!-- Part 1: Patient / Template Selection -->
                <div class="card-modern">
                    <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#part1">
                        <h5><i class='bx bx-user'></i> 1. Patient Selection</h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="collapse show" id="part1">
                        <div class="card-body-modern">
                            <?php if ($is_template): ?>
                                <div class="mb-3">
                                    <label class="form-label-modern">Template Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-control-modern" required placeholder="e.g., Common Cold Template">
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <label class="form-label-modern">Select Patient <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="patient_id" class="form-select select2-patient" required>
                                            <option value="">Choose patient...</option>
                                            <?php while ($row = mysqli_fetch_assoc($patients_result)): ?>
                                                <option value="<?php echo $row['id']; ?>">
                                                    <?php echo htmlspecialchars("{$row['name']} ({$row['patient_uid']}) - {$row['age']}y/{$row['sex']}"); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scanPatientModal"><i class='bx bx-qr-scan'></i></button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal"><i class='bx bx-plus'></i></button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-modern">Load Previous Prescription</label>
                                    <select class="form-select select2-prescription">
                                        <option value="">Search previous...</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Part 2: Vital Signs -->
                <div class="card-modern">
                    <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#part2">
                        <h5><i class='bx bx-heart'></i> 2. Vital Signs</h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="collapse show" id="part2">
                        <div class="card-body-modern">
                            <div class="vitals-grid">
                                <div>
                                    <label class="form-label-modern">BP (mmHg)</label>
                                    <input type="text" name="bp" class="form-control form-control-modern" placeholder="120/80" value="<?php echo htmlspecialchars($form_data['bp'] ?? ($template_data['bp'] ?? '')); ?>">
                                </div>
                                <div>
                                    <label class="form-label-modern">Pulse (bpm)</label>
                                    <input type="text" name="pulse" class="form-control form-control-modern" placeholder="72" value="<?php echo htmlspecialchars($form_data['pulse'] ?? ($template_data['pulse'] ?? '')); ?>">
                                </div>
                                <div>
                                    <label class="form-label-modern">Temp (°F)</label>
                                    <input type="text" name="temperature" class="form-control form-control-modern" placeholder="98.6" value="<?php echo htmlspecialchars($form_data['temperature'] ?? ($template_data['temperature'] ?? '')); ?>">
                                </div>
                                <div>
                                    <label class="form-label-modern">SpO2 (%)</label>
                                    <input type="text" name="spo2" class="form-control form-control-modern" placeholder="98" value="<?php echo htmlspecialchars($form_data['spo2'] ?? ($template_data['spo2'] ?? '')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Part 3: History & Examination -->
                <div class="card-modern full-width-section">
                    <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#part3">
                        <h5><i class='bx bx-clipboard'></i> 3. History & Examination</h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="collapse show" id="part3">
                        <div class="card-body-modern">
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <label class="form-label-modern">Chief Complaints <span class="text-danger">*</span></label>
                                    <div class="mb-2 d-flex gap-2">
                                        <select class="form-select select2-template" id="chiefComplaintTemplates"></select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertChiefComplaintTemplate">Insert</button>
                                    </div>
                                    <textarea name="chief_complaints" id="chief_complaints" class="form-control form-control-modern" rows="4"><?php echo htmlspecialchars($form_data['chief_complaints'] ?? ($template_data['chief_complaints'] ?? '')); ?></textarea>
                                    <div id="suggestions-chief_complaints" class="suggestions-dropdown"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label-modern">Medical History</label>
                                    <div class="mb-2 d-flex gap-2">
                                        <select class="form-select select2-template" id="medicalHistoryTemplates"></select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertMedicalHistoryTemplate">Insert</button>
                                    </div>
                                    <textarea name="medical_history" id="medical_history" class="form-control form-control-modern" rows="4"><?php echo htmlspecialchars($form_data['medical_history'] ?? ($template_data['medical_history'] ?? '')); ?></textarea>
                                    <div id="suggestions-medical_history" class="suggestions-dropdown"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label-modern">Examination Findings</label>
                                    <div class="mb-2 d-flex gap-2">
                                        <select class="form-select select2-template" id="examinationFindingsTemplates"></select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertExaminationFindingsTemplate">Insert</button>
                                    </div>
                                    <textarea name="examination_findings" id="examination_findings" class="form-control form-control-modern" rows="4"><?php echo htmlspecialchars($form_data['examination_findings'] ?? ($template_data['examination_findings'] ?? '')); ?></textarea>
                                    <div id="suggestions-examination_findings" class="suggestions-dropdown"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Part 4: Diagnosis & Investigation -->
                <div class="card-modern full-width-section">
                    <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#part4">
                        <h5><i class='bx bx-search-alt-2'></i> 4. Diagnosis & Investigation</h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="collapse show" id="part4">
                        <div class="card-body-modern">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label class="form-label-modern">Diagnosis <span class="text-danger">*</span></label>
                                    <div class="mb-2 d-flex gap-2">
                                        <select class="form-select select2-template" id="diagnosisTemplates"></select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertDiagnosisTemplate">Insert</button>
                                    </div>
                                    <textarea name="diagnosis" id="diagnosis" class="form-control form-control-modern" rows="4" required><?php echo htmlspecialchars($form_data['diagnosis'] ?? ($template_data['diagnosis'] ?? '')); ?></textarea>
                                    <div id="suggestions-diagnosis" class="suggestions-dropdown"></div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label-modern">Investigations</label>
                                    <div class="mb-2 d-flex gap-2">
                                        <select class="form-select select2-template" id="investigationTemplates"></select>
                                        <button type="button" class="btn btn-outline-secondary" id="insertInvestigationTemplate">Insert</button>
                                    </div>
                                    <textarea name="investigation" id="investigation" class="form-control form-control-modern" rows="4"><?php echo htmlspecialchars($form_data['investigation'] ?? ($template_data['investigation'] ?? '')); ?></textarea>
                                    <div id="suggestions-investigation" class="suggestions-dropdown"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Part 5: Medicines -->
                <div class="card-modern full-width-section">
                    <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#part5">
                        <h5><i class='bx bx-capsule'></i> 5. Medicines & Treatment</h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="collapse show" id="part5">
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label-modern">Quick Load Treatment Template</label>
                                <div class="input-group">
                                    <select class="form-select select2-template" id="treatmentTemplates"></select>
                                    <button type="button" class="btn btn-primary" id="insertTreatmentTemplate">Load Template</button>
                                </div>
                            </div>

                            <div id="drugsList">
                                <!-- Drugs will be added here -->
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-modern" id="addDrug">
                                <i class='bx bx-plus-medical'></i> Add Medicine
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Part 6: Advice & Follow-up -->
                <div class="card-modern">
                    <div class="card-header-modern" data-bs-toggle="collapse" data-bs-target="#part6">
                        <h5><i class='bx bx-calendar-check'></i> 6. Advice & Follow-up</h5>
                        <i class='bx bx-chevron-down toggle-icon'></i>
                    </div>
                    <div class="collapse show" id="part6">
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label-modern">Advice</label>
                                <div class="mb-2 d-flex gap-2">
                                    <select class="form-select select2-template" id="adviceTemplates"></select>
                                    <button type="button" class="btn btn-outline-secondary" id="insertAdviceTemplate">Insert</button>
                                </div>
                                <textarea name="advice" id="advice" class="form-control form-control-modern" rows="4"><?php echo htmlspecialchars($form_data['advice'] ?? ($template_data['advice'] ?? '')); ?></textarea>
                                <div id="suggestions-advice" class="suggestions-dropdown"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-modern">Next Visit</label>
                                <input type="date" name="next_visit" class="form-control form-control-modern" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="prescriptions.php" class="btn btn-outline-secondary btn-modern">Cancel</a>
                <button type="submit" class="btn btn-primary btn-modern btn-lg">
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
</body>
</html>
