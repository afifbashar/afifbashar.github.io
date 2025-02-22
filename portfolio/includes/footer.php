<?php
// Fetch social media links
$stmt = $pdo->query("SELECT * FROM social_media WHERE is_active = 1 ORDER BY created_at ASC");
$social_media = $stmt->fetchAll();
?>

    </main>
    <footer class="bg-gray-900 text-white py-6 mt-10">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center space-x-4 mb-4">
                <?php foreach ($social_media as $social): ?>
                    <a href="<?php echo $social['url']; ?>" target="_blank" class="hover:text-blue-400 transition transform hover:scale-110">
                        <i class="<?php echo $social['icon_class']; ?> fa-2x"></i>
                    </a>
                <?php endforeach; ?>
            </div>
            <p>© <?php echo date('Y'); ?> Dr. Afif Bashar. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
