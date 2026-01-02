<?php
session_start();
require_once 'config.php';
require_once 'auth_check.php';
requireLogin();

$doctor_id = $_SESSION['user_id'];

// ==================== IMAGE UPLOAD HANDLER (Built-in) ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload'])) {
    $upload_dir = 'uploads/guidelines/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $file = $_FILES['upload'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];

    if (!in_array($ext, $allowed) || $file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file or upload error']);
        exit;
    }

    $filename = uniqid('guideline_img_') . '.' . $ext;
    $path = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $path)) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' . $path;
        echo json_encode(['url' => $url]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save image']);
    }
    exit;
}

// ==================== SAVE / UPDATE ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    header('Content-Type: application/json');
    $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Title and content are required']);
        exit;
    }

    if ($id) {
        $stmt = mysqli_prepare($conn, "UPDATE medical_guidelines SET title=?, category=?, content=?, updated_at=NOW() WHERE id=? AND created_by=?");
        mysqli_stmt_bind_param($stmt, "sssii", $title, $category, $content, $id, $doctor_id);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO medical_guidelines (title, category, content, created_by, created_at) VALUES (?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "sssi", $title, $category, $content, $doctor_id);
    }

    $success = mysqli_stmt_execute($stmt);
    echo json_encode([
        'success' => $success,
        'id' => $id ?: mysqli_insert_id($conn),
        'message' => $success ? 'Saved successfully' : 'Database error'
    ]);
    exit;
}

// ==================== FETCH SINGLE (for Edit/View) ====================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = (int)$_GET['fetch_id'];

    $stmt = mysqli_prepare($conn, "SELECT id, title, category, content, created_at FROM medical_guidelines WHERE id=? AND created_by=?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'success' => true,
            'id' => $row['id'],
            'title' => $row['title'],
            'category' => $row['category'],
            'content' => $row['content'],
            'created_at' => $row['created_at']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Not found']);
    }
    exit;
}

// ==================== DELETE ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    header('Content-Type: application/json');
    $id = (int)$_POST['id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM medical_guidelines WHERE id=? AND created_by=?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $doctor_id);

    echo json_encode(['success' => mysqli_stmt_execute($stmt)]);
    exit;
}

// ==================== LIST + PAGINATION ====================
$search = trim($_GET['search'] ?? '');
$category_filter = $_GET['category'] ?? '';
$per_page = 12;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

// Count total
$count_sql = "SELECT COUNT(*) AS total FROM medical_guidelines WHERE created_by = ?";
$params = [$doctor_id];
$types = "i";
if ($search !== '') {
    $count_sql .= " AND (title LIKE ? OR content LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like;
    $types .= "ss";
}
if ($category_filter !== '') {
    $count_sql .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}
$stmt = mysqli_prepare($conn, $count_sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$total_guidelines = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;
$total_pages = ceil($total_guidelines / $per_page);

// Fetch guidelines
$sql = "SELECT g.*, u.name AS author_name FROM medical_guidelines g LEFT JOIN users u ON g.created_by = u.id WHERE g.created_by = ?";
$params = [$doctor_id];
$types = "i";
if ($search !== '') {
    $sql .= " AND (g.title LIKE ? OR g.content LIKE ?)";
    $params[] = $like; $params[] = $like;
    $types .= "ss";
}
if ($category_filter !== '') {
    $sql .= " AND g.category = ?";
    $params[] = $category_filter;
    $types .= "s";
}
$sql .= " ORDER BY g.created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page; $params[] = $offset; $types .= "ii";

$guidelines = [];
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) $guidelines[] = $row;

// Categories
$categories = [];
$stmt = mysqli_prepare($conn, "SELECT DISTINCT category FROM medical_guidelines WHERE created_by = ? AND category != '' ORDER BY category");
mysqli_stmt_bind_param($stmt, "i", $doctor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) $categories[] = $row['category'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Guidelines - Smart RX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+Bengali:wght@100..900&family=Hind+Siliguri:wght@300;400;500;600;700&family=Kalpurush&family=Noto+Serif+Bengali:wght@100..900&family=Baloo+Da+2:wght@400..800&family=Tiro+Bangla&family=Mukta:wght@200..800&family=Roboto&family=Open+Sans&family=Lato&family=Montserrat&family=Poppins&family=Playfair+Display&family=Merriweather&family=Oswald&family=Raleway&family=Ubuntu&family=Nunito&family=PT+Sans&family=Source+Sans+3&family=Work+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px 0; }
        .container-custom { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        .page-header { background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); text-align: center; margin-bottom: 2rem; }
        .page-header h1 { font-size: 2.5rem; font-weight: 700; color: #1e293b; }
        .stats-badge { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: 600; }
        .search-card { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .guideline-card { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 2px solid #e2e8f0; height: 100%; display: flex; flex-direction: column; transition: all 0.3s; }
        .guideline-card:hover { transform: translateY(-8px); box-shadow: 0 20px 60px rgba(102,126,234,0.25); border-color: #6366f1; }
        .guideline-title { font-size: 1.35rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; }
        .category-badge { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; }
        .date-badge { color: #64748b; font-size: 0.9rem; }
        .guideline-preview { color: #64748b; line-height: 1.7; flex-grow: 1; margin-bottom: 1.5rem; overflow: hidden; }
        .guideline-preview.collapsed { max-height: 120px; position: relative; }
        .guideline-preview.collapsed::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 60px; background: linear-gradient(transparent, white); }
        .read-more { color: #6366f1; font-weight: 600; cursor: pointer; }
        .card-actions { display: flex; gap: 0.75rem; margin-top: auto; }
        .modal-content { border-radius: 20px; box-shadow: 0 25px 80px rgba(0,0,0,0.3); }
        .modal-header { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 20px 20px 0 0; }
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
        }  </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-custom">
        <div class="page-header">
            <h1><i class='bx bx-book-bookmark'></i> Medical Guidelines</h1>
            <p class="stats-badge mt-3"><i class='bx bx-library'></i> <?= number_format($total_guidelines) ?> Guidelines</p>
        </div>

        <div class="search-card">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <input type="text" name="search" class="form-control" placeholder="Search title or content..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-lg-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $category_filter === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class='bx bx-search'></i> Search</button>
                    <button type="button" class="btn btn-success flex-fill" onclick="newGuideline()"><i class='bx bx-plus'></i> New</button>
                </div>
            </form>
        </div>

        <?php if (!empty($guidelines)): ?>
            <div class="row g-4 mb-5">
                <?php foreach ($guidelines as $g): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="guideline-card">
                            <h3 class="guideline-title"><?= htmlspecialchars($g['title']) ?></h3>
                            <div class="guideline-meta mb-3 d-flex flex-wrap gap-2">
                                <?php if ($g['category']): ?><span class="category-badge"><?= htmlspecialchars($g['category']) ?></span><?php endif; ?>
                                <span class="date-badge"><i class='bx bx-calendar'></i> <?= date('M d, Y', strtotime($g['created_at'])) ?></span>
                            </div>
                            <div class="guideline-preview collapsed" id="preview-<?= $g['id'] ?>"><?= $g['content'] ?></div>
                            <span class="read-more" onclick="togglePreview(<?= $g['id'] ?>)">
                                <span id="toggle-text-<?= $g['id'] ?>">Read More</span> <i class='bx bx-chevron-down' id="toggle-icon-<?= $g['id'] ?>"></i>
                            </span>
                            <div class="card-actions">
                                <button class="btn btn-info btn-sm flex-fill" onclick="viewGuideline(<?= $g['id'] ?>)"><i class='bx bx-show'></i> View</button>
                                <button class="btn btn-warning btn-sm" onclick="editGuideline(<?= $g['id'] ?>)"><i class='bx bx-edit'></i></button>
                                <button class="btn btn-danger btn-sm" onclick="deleteGuideline(<?= $g['id'] ?>)"><i class='bx bx-trash'></i></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center gap-2">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category_filter) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class='bx bx-book-open' style="font-size:7rem;color:#cbd5e1;"></i>
                <h3>No Guidelines Found</h3>
                <p class="text-muted">Start building your medical knowledge library</p>
                <button class="btn btn-success btn-lg mt-3" onclick="newGuideline()">Create First Guideline</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="guidelineModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">New Guideline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="guidelineId">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" id="guidelineTitle" class="form-control" placeholder="Enter guideline title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" id="guidelineCategory" class="form-control" placeholder="e.g., Cardiology, Pediatrics">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <div id="editor"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="saveGuideline()">Save Guideline</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="viewMeta" class="mb-3"></div>
                    <hr>
                    <div id="viewContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

        let editor = null;

        
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

        class UploadAdapter {
            constructor(loader) { this.loader = loader; }
            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    fetch(location.href, { method: 'POST', body: data })
                        .then(r => r.json())
                        .then(res => res.url ? resolve({ default: res.url }) : reject(res.error || 'Upload failed'))
                        .catch(() => reject('Upload failed'));
                }));
            }
            abort() {}
        }

        const modal = document.getElementById('guidelineModal');
        modal.addEventListener('shown.bs.modal', () => {
            if (!editor) {
                ClassicEditor.create(document.querySelector('#editor'), editorConfig)
                    .then(e => {
                        editor = e;
                        e.plugins.get('FileRepository').createUploadAdapter = loader => new UploadAdapter(loader);
                    })
                    .catch(err => console.error(err));
            }
        });

        function newGuideline() {
            document.getElementById('modalTitle').innerHTML = '<i class="bx bx-plus-circle"></i> New Guideline';
            document.getElementById('guidelineId').value = '';
            document.getElementById('guidelineTitle').value = '';
            document.getElementById('guidelineCategory').value = '';
            if (editor) editor.setData('');
            new bootstrap.Modal(modal).show();
        }

        function editGuideline(id) {
            fetch(`?fetch_id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) { alert('Failed to load guideline'); return; }
                    document.getElementById('modalTitle').innerHTML = '<i class="bx bx-edit"></i> Edit Guideline';
                    document.getElementById('guidelineId').value = data.id;
                    document.getElementById('guidelineTitle').value = data.title;
                    document.getElementById('guidelineCategory').value = data.category || '';
                    if (editor) editor.setData(data.content);
                    new bootstrap.Modal(modal).show();
                });
        }

        function saveGuideline() {
            if (!editor) { alert('Editor not ready'); return; }
            const id = document.getElementById('guidelineId').value;
            const title = document.getElementById('guidelineTitle').value.trim();
            const category = document.getElementById('guidelineCategory').value.trim();
            const content = editor.getData();

            if (!title || !content) { alert('Title and content are required!'); return; }

            const fd = new FormData();
            fd.append('action', 'save');
            if (id) fd.append('id', id);
            fd.append('title', title);
            fd.append('category', category);
            fd.append('content', content);

            fetch(location.href, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(res => res.success ? location.reload() : alert(res.message || 'Save failed'));
        }

        function deleteGuideline(id) {
            if (!confirm('Permanently delete this guideline?')) return;
            const fd = new FormData();
            fd.append('action', 'delete');
            fd.append('id', id);
            fetch(location.href, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(res => res.success ? location.reload() : alert('Delete failed'));
        }

        function viewGuideline(id) {
            fetch(`?fetch_id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    document.getElementById('viewTitle').textContent = data.title;
                    document.getElementById('viewMeta').innerHTML = `
                        ${data.category ? `<span class="category-badge">${data.category}</span>` : ''}
                        <span class="date-badge ms-2"><i class='bx bx-calendar'></i> ${new Date(data.created_at).toLocaleDateString()}</span>
                    `;
                    document.getElementById('viewContent').innerHTML = data.content;
                    new bootstrap.Modal(document.getElementById('viewModal')).show();
                });
        }

        function togglePreview(id) {
            const preview = document.getElementById(`preview-${id}`);
            const text = document.getElementById(`toggle-text-${id}`);
            const icon = document.getElementById(`toggle-icon-${id}`);
            preview.classList.toggle('collapsed');
            text.textContent = preview.classList.contains('collapsed') ? 'Read More' : 'Show Less';
            icon.className = preview.classList.contains('collapsed') ? 'bx bx-chevron-down' : 'bx bx-chevron-up';
        }
    </script>
</body>
</html>
