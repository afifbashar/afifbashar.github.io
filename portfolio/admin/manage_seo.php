<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meta_title = sanitize($_POST['meta_title']);
    $meta_description = sanitize($_POST['meta_description']);
    $meta_keywords = sanitize($_POST['meta_keywords']);
    $stmt = $pdo->prepare("UPDATE seo_settings SET meta_title = ?, meta_description = ?, meta_keywords = ? WHERE id = 1");
    $stmt->execute([$meta_title, $meta_description, $meta_keywords]);
    echo '<div class="bg-green-500 p-4 rounded-lg mb-6">SEO settings updated!</div>';
}

$seo = $pdo->query("SELECT * FROM seo_settings WHERE id = 1")->fetch();
?>

<section class="py-10">
    <h1 class="text-4xl font-bold mb-6">SEO Settings</h1>
    <form method="POST" class="bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label class="block text-lg">Meta Title</label>
            <input type="text" name="meta_title" value="<?php echo $seo['meta_title']; ?>" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <div class="mb-4">
            <label class="block text-lg">Meta Description</label>
            <textarea name="meta_description" class="w-full p-2 rounded bg-gray-700 text-white"><?php echo $seo['meta_description']; ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-lg">Meta Keywords</label>
            <input type="text" name="meta_keywords" value="<?php echo $seo['meta_keywords']; ?>" class="w-full p-2 rounded bg-gray-700 text-white">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600">Update SEO</button>
    </form>
</section>

<?php include '../includes/footer.php'; ?>
