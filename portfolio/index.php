<?php 
$page_title = "Home";
include 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM health_tips WHERE is_active = 1 ORDER BY RAND() LIMIT 1");
$health_tip = $stmt->fetch();

$stmt = $pdo->query("SELECT * FROM youtube_videos ORDER BY created_at DESC LIMIT 2");
$videos = $stmt->fetchAll();
?>

<section class="hero text-center py-10 animate__animated animate__fadeIn">
    <img src="assets/images/profile.jpg" alt="Dr. Afif Bashar" class="rounded-full w-48 h-48 mx-auto mb-4 transform hover:scale-105 transition duration-300" loading="lazy">
    <h1 class="text-4xl font-bold"><?php echo $seo['meta_title']; ?></h1>
    <p class="text-xl mt-2">Specialist in Internal Medicine</p>
    <div class="mt-6 flex justify-center gap-4">
        <a href="appointment.php" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition transform hover:-translate-y-1">Book Appointment</a>
        <a href="contact.php" class="bg-transparent border border-white text-white px-6 py-2 rounded-full hover:bg-white hover:text-gray-900 transition transform hover:-translate-y-1">Contact Me</a>
    </div>
</section>

<section class="health-tips py-6">
    <div class="bg-blue-600 text-white p-4 rounded-lg animate__animated animate__slideInLeft">
        <p class="text-lg"><i class="fas fa-heartbeat mr-2"></i> Health Tip: <?php echo $health_tip['tip_text']; ?></p>
    </div>
</section>

<section class="clock text-center py-6">
    <div id="clock" class="text-3xl font-mono"></div>
    <div id="date" class="text-lg"></div>
</section>

<section class="youtube py-10">
    <h2 class="text-3xl font-bold text-center mb-6 animate__animated animate__fadeInUp">Featured Videos</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($videos as $video): ?>
            <div class="animate__animated animate__zoomIn">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?php echo $video['video_id']; ?>" title="<?php echo $video['title']; ?>" frameborder="0" allowfullscreen loading="lazy"></iframe>
                <p class="mt-2"><?php echo $video['title']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
