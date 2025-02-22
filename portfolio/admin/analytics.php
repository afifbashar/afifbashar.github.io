<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

$visits = $pdo->query("SELECT visit_count, last_updated FROM site_visits WHERE id = 1")->fetch();
$top_posts = $pdo->query("SELECT title, read_count FROM blog_posts ORDER BY read_count DESC LIMIT 5")->fetchAll();
?>

<section class="py-10">
    <h1 class="text-4xl font-bold mb-6">Website Analytics</h1>
    <div class="bg-gray-800 p-6 rounded-lg mb-6">
        <h2 class="text-2xl">Total Visits: <?php echo $visits['visit_count']; ?></h2>
        <p>Last Updated: <?php echo $visits['last_updated']; ?></p>
    </div>
    <h2 class="text-2xl font-bold mb-4">Top Read Articles</h2>
    <ul class="space-y-4">
        <?php foreach ($top_posts as $post): ?>
            <li class="bg-gray-800 p-4 rounded-lg"><?php echo $post['title']; ?> - <?php echo $post['read_count']; ?> reads</li>
        <?php endforeach; ?>
    </ul>
</section>

<?php include '../includes/footer.php'; ?>
