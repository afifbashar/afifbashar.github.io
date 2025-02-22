<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

$visits = $pdo->query("SELECT visit_count FROM site_visits WHERE id = 1")->fetch()['visit_count'];
?>

<section class="py-10">
    <h1 class="text-4xl font-bold text-center mb-6">Admin Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="manage_posts.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">Manage Blog Posts</a>
        <a href="manage_appointments.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">Manage Appointments</a>
        <a href="manage_health_tips.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">Manage Health Tips</a>
        <a href="manage_youtube.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">Manage YouTube Videos</a>
        <a href="manage_social_media.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">Manage Social Media</a>
        <a href="manage_seo.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">SEO Settings</a>
        <a href="analytics.php" class="bg-gray-800 p-6 rounded-lg text-center hover:bg-gray-700 transition">Analytics (Visits: <?php echo $visits; ?>)</a>
        <a href="logout.php" class="bg-red-600 p-6 rounded-lg text-center hover:bg-red-700 transition">Logout</a>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
