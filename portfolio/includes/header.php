<?php 
require_once 'db_connect.php'; 
require_once 'functions.php'; 
trackVisit($pdo);

// Fetch SEO settings
$seo = $pdo->query("SELECT * FROM seo_settings WHERE id = 1")->fetch();
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light' ? 'light-mode' : 'dark-mode'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $seo['meta_description']; ?>">
    <meta name="keywords" content="<?php echo $seo['meta_keywords']; ?>">
    <meta name="author" content="Dr. Afif Bashar">
    <meta name="robots" content="index, follow">
    <title><?php echo $seo['meta_title'] . ' - ' . (isset($page_title) ? $page_title : 'Medical Portfolio'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen transition-colors duration-300">
    <header class="fixed top-0 w-full bg-gray-900 shadow-lg z-50">
        <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold text-white">Dr. Afif Bashar</a>
            <div class="md:hidden">
                <button id="menu-toggle" class="text-white focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <ul id="nav-menu" class="hidden md:flex space-x-6 text-white">
                <li><a href="index.php" class="hover:text-blue-400 transition">Home</a></li>
                <li><a href="about.php" class="hover:text-blue-400 transition">About</a></li>
                <li><a href="services.php" class="hover:text-blue-400 transition">Services</a></li>
                <li><a href="blog.php" class="hover:text-blue-400 transition">Blog</a></li>
                <li><a href="appointment.php" class="hover:text-blue-400 transition">Appointment</a></li>
                <li><a href="contact.php" class="hover:text-blue-400 transition">Contact</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="prescription.php" class="hover:text-blue-400 transition">Prescriptions</a></li>
                    <?php if (isAdmin()): ?>
                        <li><a href="admin/index.php" class="hover:text-blue-400 transition">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="admin/logout.php" class="hover:text-blue-400 transition">Logout</a></li>
                <?php endif; ?>
            </ul>
            <button id="theme-toggle" class="text-white ml-4">
                <i class="fas fa-moon"></i>
            </button>
        </nav>
    </header>
    <main class="container mx-auto px-4 mt-20">
