<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $content = $_POST['content']; // No sanitization due to rich text
    $category = sanitize($_POST['category']);
    $slug = generateSlug($title);
    $status = $_POST['status'];
    $featured_image = null;

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $target_dir = "../assets/uploads/";
        $featured_image = time() . '_' . basename($_FILES["featured_image"]["name"]);
        move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_dir . $featured_image);
    }

    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, content, category, featured_image, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $content, $category, $featured_image, $status]);
    echo '<div class="bg-green-500 p-4 rounded-lg mb-6">Post added!</div>';
}

$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC")->fetchAll();
?>

<section class="py-10">
    <h1 class="text-4xl font-bold mb-6">Manage Blog Posts</h1>
    <form method="POST" enctype="multipart/form-data" class="bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label class="block text-lg">Title</label>
            <input type="text" name="title" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <div class="mb-4">
            <label class="block text-lg">Content</label>
            <textarea name="content" id="editor" class="w-full h-64 bg-gray-700 text-white"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-lg">Category</label>
            <input type="text" name="category" class="w-full p-2 rounded bg-gray-700 text-white">
        </div>
        <div class="mb-4">
            <label class="block text-lg">Featured Image</label>
            <input type="file" name="featured_image" class="w-full p-2 bg-gray-700 text-white">
        </div>
        <div class="mb-4">
            <label class="block text-lg">Status</label>
            <select name="status" class="w-full p-2 rounded bg-gray-700 text-white">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600">Add Post</button>
    </form>

    <h2 class="text-2xl font-bold mt-10">Existing Posts</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
        <?php foreach ($posts as $post): ?>
            <div class="bg-gray-800 p-4 rounded-lg">
                <h3 class="text-xl"><?php echo $post['title']; ?></h3>
                <p>Status: <?php echo $post['status']; ?> | Reads: <?php echo $post['read_count']; ?></p>
                <p>Created: <?php echo $post['created_at']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
tinymce.init({
    selector: '#editor',
    plugins: 'advlist autolink lists link image charmap preview anchor',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image'
});
</script>

<?php include '../includes/footer.php'; ?>
