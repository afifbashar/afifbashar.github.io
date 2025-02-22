<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tip_text = sanitize($_POST['tip_text']);
    $stmt = $pdo->prepare("INSERT INTO health_tips (tip_text) VALUES (?)");
    $stmt->execute([$tip_text]);
    echo '<div class="bg-green-500 p-4 rounded-lg mb-6">Tip added!</div>';
}

$tips = $pdo->query("SELECT * FROM health_tips ORDER BY created_at DESC")->fetchAll();
?>

<section class="py-10">
    <h1 class="text-4xl font-bold mb-6">Manage Health Tips</h1>
    <form method="POST" class="bg-gray-800 p-6 rounded-lg">
        <div class="mb-4">
            <label class="block text-lg">Health Tip</label>
            <input type="text" name="tip_text" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600">Add Tip</button>
    </form>

    <h2 class="text-2xl font-bold mt-10">Existing Tips</h2>
    <ul class="mt-4 space-y-4">
        <?php foreach ($tips as $tip): ?>
            <li class="bg-gray-800 p-4 rounded-lg flex justify-between">
                <span><?php echo $tip['tip_text']; ?></span>
                <span><?php echo $tip['is_active'] ? 'Active' : 'Inactive'; ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<?php include '../includes/footer.php'; ?>
