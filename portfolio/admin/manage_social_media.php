<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $platform = sanitize($_POST['platform']);
    $url = sanitize($_POST['url']);
    $icon_class = sanitize($_POST['icon_class']);
    
    $stmt = $pdo->prepare("INSERT INTO social_media (platform, url, icon_class) VALUES (?, ?, ?)");
    $stmt->execute([$platform, $url, $icon_class]);
    echo '<div class="bg-green-500 p-4 rounded-lg mb-6">Social media link added!</div>';
}

$social_media = $pdo->query("SELECT * FROM social_media ORDER BY created_at DESC")->fetchAll();
?>

<section class="py-10">
    <h1 class="text-4xl font-bold mb-6">Manage Social Media Links</h1>
    <form method="POST" class="bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label class="block text-lg">Platform Name</label>
            <input type="text" name="platform" class="w-full p-2 rounded bg-gray-700 text-white" placeholder="e.g., Facebook" required>
        </div>
        <div class="mb-4">
            <label class="block text-lg">URL</label>
            <input type="url" name="url" class="w-full p-2 rounded bg-gray-700 text-white" placeholder="e.g., https://facebook.com/drafif" required>
        </div>
        <div class="mb-4">
            <label class="block text-lg">Font Awesome Icon Class</label>
            <input type="text" name="icon_class" class="w-full p-2 rounded bg-gray-700 text-white" placeholder="e.g., fab fa-facebook" required>
            <p class="text-sm text-gray-400 mt-1">Find icons at <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-400">Font Awesome</a></p>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">Add Social Media</button>
    </form>

    <h2 class="text-2xl font-bold mt-10">Existing Social Media Links</h2>
    <ul class="mt-4 space-y-4">
        <?php foreach ($social_media as $social): ?>
            <li class="bg-gray-800 p-4 rounded-lg flex justify-between items-center">
                <div>
                    <span class="font-semibold"><?php echo $social['platform']; ?></span> - 
                    <a href="<?php echo $social['url']; ?>" target="_blank" class="text-blue-400"><?php echo $social['url']; ?></a>
                </div>
                <div>
                    <i class="<?php echo $social['icon_class']; ?> mr-2"></i>
                    <span><?php echo $social['is_active'] ? 'Active' : 'Inactive'; ?></span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<?php include '../includes/footer.php'; ?>
