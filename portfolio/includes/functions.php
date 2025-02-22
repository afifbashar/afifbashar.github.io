<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function generateSlug($string) {
    return strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', trim($string)));
}

function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

function trackVisit($pdo) {
    $stmt = $pdo->query("UPDATE site_visits SET visit_count = visit_count + 1, last_updated = NOW() WHERE id = 1");
    $stmt->execute();
}
?>
