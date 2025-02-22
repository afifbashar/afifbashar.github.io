<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $video_id = sanitize($_POST['video_id']);
    $title = sanitize($_POST['title']);
    $stmt = $pdo->prepare("INSERT INTO youtube_videos (video_id, title) VALUES (?, ?)");
    $stmt->execute([$video_id, $title]);
    echo '<div class="bg-green-500 p-4 rounded-lg mb-6">Video added!</div>';
}

$videos = $pdo->query("SELECT * FROM youtube_videos ORDER BY created_at DESC")->fetchAll();
?>

<section class="py-10">
    <h1 class="text-4xl font-bold mb-6">Manage YouTube Videos</h1>
    <form method="POST" class="bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label class="block text-lg">YouTube Video ID</label>
            <input type="text" name="video_id" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <div class="mb-4">
            <label class="block text-lg">Title</label>
            <input type="text" name="title" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600">Add Video</button>
    </form>

    <h2 class="text-2xl font-bold mt-10">Existing Videos</h2>
    <ul class="mt-4 space-y-4">
        <?php foreach ($videos as $video): ?>
            <li class="bg-gray-800 p-4 rounded-lg"><?php echo $video['title']; ?> (ID: <?php echo $video['video_id']; ?>)</li>
        <?php endforeach; ?>
    </ul>
</section>

<?php include '../includes/footer.php'; ?>
