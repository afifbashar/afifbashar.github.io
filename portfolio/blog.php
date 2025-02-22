<?php 
$page_title = "Blog & Articles";
include 'includes/header.php'; 

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
if ($slug) {
    $stmt = $pdo->prepare("UPDATE blog_posts SET read_count = read_count + 1 WHERE slug = ?");
    $stmt->execute([$slug]);
    
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? AND status = 'published'");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    if ($post) {
        $page_title = $post['title'];
        ?>
        <section class="blog-post py-10 animate__animated animate__fadeIn">
            <h1 class="text-4xl font-bold"><?php echo $post['title']; ?></h1>
            <p class="text-sm mt-2">Published on: <?php echo date('F j, Y', strtotime($post['created_at'])); ?> | Reads: <?php echo $post['read_count']; ?></p>
            <?php if ($post['featured_image']): ?>
                <img src="assets/uploads/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" class="w-full h-64 object-cover rounded-lg mt-4" loading="lazy">
            <?php endif; ?>
            <div class="mt-6 prose prose-invert"><?php echo $post['content']; ?></div>
            <div class="mt-6 flex gap-4">
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://yourdomain.com/blog/' . $post['slug']); ?>" target="_blank" class="text-blue-400"><i class="fab fa-twitter fa-2x"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://yourdomain.com/blog/' . $post['slug']); ?>" target="_blank" class="text-blue-400"><i class="fab fa-facebook fa-2x"></i></a>
                <a href="https://wa.me/?text=<?php echo urlencode('Check out this article: http://yourdomain.com/blog/' . $post['slug']); ?>" target="_blank" class="text-blue-400"><i class="fab fa-whatsapp fa-2x"></i></a>
            </div>
        </section>
        <?php
    } else {
        echo "<h1 class='text-4xl font-bold text-center py-10'>Post Not Found</h1>";
    }
} else {
    $stmt = $pdo->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 6");
    $posts = $stmt->fetchAll();
    ?>
    <section class="blog py-10">
        <h1 class="text-4xl font-bold text-center mb-6 animate__animated animate__fadeInDown">Medical Blog</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($posts as $post): ?>
                <div class="bg-gray-800 p-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 animate__animated animate__zoomIn">
                    <?php if ($post['featured_image']): ?>
                        <img src="assets/uploads/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" class="w-full h-40 object-cover rounded-t-lg" loading="lazy">
                    <?php endif; ?>
                    <h2 class="text-xl font-semibold mt-4"><a href="blog/<?php echo $post['slug']; ?>" class="text-white hover:text-blue-400"><?php echo $post['title']; ?></a></h2>
                    <p class="text-gray-400"><?php echo substr(strip_tags($post['content']), 0, 100) . '...'; ?></p>
                    <p class="text-sm text-gray-500 mt-2">Reads: <?php echo $post['read_count']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}
include 'includes/footer.php'; ?>
