<?php
require_once 'config.php';
requireLogin();

$success_message = $error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $specialization = trim($_POST['specialization']);
    $qualification = trim($_POST['qualification']);
    $registration_no = trim($_POST['registration_no']);
    $chamber_address = trim($_POST['chamber_address']);
    $consultation_fee = (float)$_POST['consultation_fee'];
    $prescription_header = $_POST['prescription_header'];
    $prescription_footer = $_POST['prescription_footer'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, specialization=?, qualification=?, 
                            registration_no=?, chamber_address=?, consultation_fee=?, prescription_header=?, prescription_footer=? 
                            WHERE id=?");
    $stmt->bind_param("ssssssdsssi", $name, $email, $phone, $specialization, $qualification,
                      $registration_no, $chamber_address, $consultation_fee, $prescription_header, $prescription_footer, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart RX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Load ALL Bengali and English fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@300;400;500;600;700;900&family=Hind+Siliguri:wght@300;400;500;600;700&family=Kalpurush&family=Noto+Serif+Bengali:wght@400;500;600;700;900&family=Baloo+Da+2:wght@400;500;600;700;800&family=Tiro+Bangla:ital@0;1&family=Mukta:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;600;700;800&family=Lato:wght@300;400;700;900&family=Montserrat:wght@300;400;600;700;900&family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- CKEditor 5 LATEST VERSION 47.3.0 -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.css">
    
    <style>
        :root {
            --ck-border-radius: 8px;
            --ck-color-base-border: #e2e8f0;
            --ck-color-toolbar-background: #f8fafc;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: #212529;
            line-height: 1.5;
            font-size: 14px;
            min-height: 100vh;
            padding: 20px 0;
        }

        /* NAVBAR FIX */
        nav.navbar, .navbar, .sticky-top {
            position: relative !important;
            top: auto !important;
        }

        .profile-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px;
        }

        .page-header {
            background: white;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-modern {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .card-header-modern h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body-modern {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-save {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .alert-modern {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success-modern {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .alert-danger-modern {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .info-badge {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .editor-container {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .editor-container:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .ck-editor__editable {
            min-height: 400px;
            max-height: 600px;
        }

        .ck.ck-editor__main > .ck-editor__editable {
            background: white;
        }

        .ck.ck-toolbar {
            border: none !important;
            border-bottom: 2px solid #e2e8f0 !important;
            background: linear-gradient(to bottom, #f8fafc, #f1f5f9) !important;
            padding: 10px !important;
        }

        .ck.ck-editor__editable:not(.ck-focused) {
            border: none !important;
        }

        .ck-content {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="profile-wrapper">
        <div class="page-header">
            <h1>
                <i class='bx bx-user-circle' style="color: #6366f1;"></i>
                My Profile
            </h1>
            <p style="color: #64748b; margin: 0;">Update your profile information and prescription templates</p>
        </div>

        <?php if ($success_message): ?>
        <div class="alert-modern alert-success-modern">
            <i class='bx bx-check-circle' style="font-size: 1.5rem;"></i>
            <span><?php echo htmlspecialchars($success_message); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
        <div class="alert-modern alert-danger-modern">
            <i class='bx bx-error-circle' style="font-size: 1.5rem;"></i>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" id="profileForm">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h5><i class='bx bx-id-card'></i> Basic Information</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+880 1XXX-XXXXXX">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control" value="<?php echo htmlspecialchars($user['specialization'] ?? ''); ?>" placeholder="e.g. MBBS, MD (Medicine)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control" value="<?php echo htmlspecialchars($user['qualification'] ?? ''); ?>" placeholder="e.g. FCPS, MRCP">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Registration No.</label>
                                <input type="text" name="registration_no" class="form-control" value="<?php echo htmlspecialchars($user['registration_no'] ?? ''); ?>" placeholder="BMDC Registration Number">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chamber Address</label>
                                <textarea name="chamber_address" class="form-control" rows="3" placeholder="Enter your chamber address..."><?php echo htmlspecialchars($user['chamber_address'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Consultation Fee (৳)</label>
                                <input type="number" name="consultation_fee" class="form-control" value="<?php echo htmlspecialchars($user['consultation_fee'] ?? 0); ?>" min="0" step="50" placeholder="500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h5><i class='bx bx-file-blank'></i> Prescription Header</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="info-badge">
                                <i class='bx bx-info-circle'></i> This appears at the top of every prescription
                            </div>
                            <div class="editor-container">
                                <div id="headerEditor"><?php echo $user['prescription_header'] ?? '<p>Enter your prescription header...</p>'; ?></div>
                            </div>
                            <input type="hidden" name="prescription_header" id="headerData">
                        </div>
                    </div>

                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h5><i class='bx bx-file-blank'></i> Prescription Footer</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="info-badge">
                                <i class='bx bx-info-circle'></i> This appears at the bottom of every prescription
                            </div>
                            <div class="editor-container">
                                <div id="footerEditor"><?php echo $user['prescription_footer'] ?? '<p>Enter your prescription footer...</p>'; ?></div>
                            </div>
                            <input type="hidden" name="prescription_footer" id="footerData">
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn-save">
                    <i class='bx bx-save'></i> Save All Changes
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CKEditor 5 LATEST VERSION 47.3.0 - UMD Build -->
    <script src="https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.umd.js"></script>
    
    <script>
        const {
            ClassicEditor,
            Essentials, Paragraph, Heading, Bold, Italic, Underline, Strikethrough, 
            Subscript, Superscript, Code, RemoveFormat,
            Font, FontFamily, FontSize, FontColor, FontBackgroundColor,
            List, TodoList, ListProperties,
            Link, AutoLink, LinkImage,
            BlockQuote, Table, TableToolbar, TableProperties, TableCellProperties, TableColumnResize,
            Alignment, Indent, IndentBlock,
            Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageInsert, ImageUpload,
            MediaEmbed, HorizontalLine, PageBreak,
            SourceEditing, GeneralHtmlSupport, HtmlEmbed, ShowBlocks,
            FindAndReplace, SelectAll, Undo,
            SpecialCharacters, SpecialCharactersEssentials,
            WordCount, Autosave
        } = CKEDITOR;

        let headerEditor, footerEditor;

        const editorConfig = {
            plugins: [
                Essentials, Paragraph, Heading, Bold, Italic, Underline, Strikethrough,
                Subscript, Superscript, Code, RemoveFormat,
                Font, FontFamily, FontSize, FontColor, FontBackgroundColor,
                List, TodoList, ListProperties,
                Link, AutoLink, LinkImage,
                BlockQuote, Table, TableToolbar, TableProperties, TableCellProperties, TableColumnResize,
                Alignment, Indent, IndentBlock,
                Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, ImageInsert, ImageUpload,
                MediaEmbed, HorizontalLine, PageBreak,
                SourceEditing, GeneralHtmlSupport, HtmlEmbed, ShowBlocks,
                FindAndReplace, SelectAll, Undo,
                SpecialCharacters, SpecialCharactersEssentials,
                WordCount, Autosave
            ],
                        licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Nzk0OTQzOTksImp0aSI6ImFmYjQ4YTY2LTlmYjctNDg4Mi04YTc3LTk5NWJmNzI3NzQ3NCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCIsIkUyUCIsIkUyVyJdLCJ2YyI6ImYyZGM4YWRhIn0.1NRmv4AyOFfNLVcHmcLSItw4ReASVOxFoiL03Cqnoz4XjcRWXZ-5WzSpKHbkUcaON4tfenP7leb-eLRRfgI-CA',

           
            toolbar: {
                items: [
                    'heading', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', 'code', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'link', 'insertTable', 'blockQuote', 'mediaEmbed', 'insertImage', 'horizontalLine', 'pageBreak', '|',
                    'specialCharacters', '|',
                    'findAndReplace', 'selectAll', 'removeFormat', '|',
                    'undo', 'redo', '|',
                    'sourceEditing', 'htmlEmbed', 'showBlocks'
                ],
                shouldNotGroupWhenFull: true
            },
            fontFamily: {
                options: [
                    'default',
                    'Hind Siliguri, sans-serif',
                    'Noto Sans Bengali, sans-serif',
                    'Kalpurush, sans-serif',
                    'Noto Serif Bengali, serif',
                    'Baloo Da 2, cursive',
                    'Tiro Bangla, serif',
                    'Mukta, sans-serif',
                    'Inter, sans-serif',
                    'Roboto, sans-serif',
                    'Open Sans, sans-serif',
                    'Lato, sans-serif',
                    'Montserrat, sans-serif',
                    'Poppins, sans-serif',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            fontSize: {
                options: [8, 9, 10, 11, 12, 14, 'default', 16, 18, 20, 22, 24, 26, 28, 30, 32, 36, 40, 44, 48],
                supportAllValues: true
            },
            fontColor: {
                columns: 6,
                colors: [
                    { color: '#000000', label: 'Black' },
                    { color: '#ffffff', label: 'White', hasBorder: true },
                    { color: '#ff0000', label: 'Red' },
                    { color: '#00ff00', label: 'Green' },
                    { color: '#0000ff', label: 'Blue' },
                    { color: '#ffff00', label: 'Yellow' },
                    { color: '#ff00ff', label: 'Magenta' },
                    { color: '#00ffff', label: 'Cyan' },
                    { color: '#1e293b', label: 'Slate' },
                    { color: '#6366f1', label: 'Indigo' }
                ]
            },
            fontBackgroundColor: {
                columns: 6,
                colors: [
                    { color: '#000000', label: 'Black' },
                    { color: '#ffffff', label: 'White', hasBorder: true },
                    { color: '#ff0000', label: 'Red' },
                    { color: '#00ff00', label: 'Green' },
                    { color: '#0000ff', label: 'Blue' },
                    { color: '#ffff00', label: 'Yellow' },
                    { color: '#ff00ff', label: 'Magenta' },
                    { color: '#00ffff', label: 'Cyan' }
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            },
            image: {
                toolbar: ['imageTextAlternative', 'toggleImageCaption', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', 'linkImage']
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            htmlSupport: {
                allow: [{
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }]
            }
        };

        // Custom upload adapter
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);

                    fetch('upload_image.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            resolve({ default: result.url });
                        } else {
                            reject(result.error || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        reject('Upload failed: ' + error);
                    });
                }));
            }

            abort() {
                // Handle abort if needed
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        ClassicEditor.create(document.querySelector('#headerEditor'), editorConfig)
            .then(editor => {
                headerEditor = editor;
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
                console.log('✅ Header editor ready (CKEditor 47.3.0)');
                window.headerEditor = editor;
            })
            .catch(error => console.error(error));

        ClassicEditor.create(document.querySelector('#footerEditor'), editorConfig)
            .then(editor => {
                footerEditor = editor;
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
                console.log('✅ Footer editor ready (CKEditor 47.3.0)');
                window.footerEditor = editor;
            })
            .catch(error => console.error(error));

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            if (headerEditor) document.getElementById('headerData').value = headerEditor.getData();
            if (footerEditor) document.getElementById('footerData').value = footerEditor.getData();
        });
    </script>

    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-modern');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
