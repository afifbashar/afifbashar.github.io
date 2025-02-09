<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/config.php';

// Theme settings
$theme_color = get_setting('theme_color') ?? '#007bff';
$dark_bg = get_setting('dark_bg') ?? '#121212';
$dark_secondary = get_setting('dark_secondary') ?? '#1e1e1e';
$dark_text = get_setting('dark_text') ?? '#fff';
$dark_text_secondary = get_setting('dark_text_secondary') ?? '#b3b3b3';
$accent_color = get_setting('accent_color') ?? '#007bff';
$accent_hover = get_setting('accent_hover') ?? '#0056b3';
$dark_card_bg = get_setting('dark_card_bg') ?? '#1e1e1e';
$hero_gradient_start = get_setting('hero_gradient_start') ?? '#000000';
$hero_gradient_end = get_setting('hero_gradient_end') ?? '#1a1a1a';

// Weather data placeholder
$weatherData = [
	'temp' => null,
	'description' => null,
	'icon' => null
];

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Debug session
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
	error_log("No admin_id in session - redirecting to login");
	header('Location: login.php');
	exit;
}

$db = getDBConnection();

// Fetch statistics
$stats = [
	'appointments' => $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn(),
	'testimonials' => $db->query("SELECT COUNT(*) FROM testimonials WHERE status = 'pending'")->fetchColumn(),
	'messages' => $db->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'")->fetchColumn(),
	'posts' => $db->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn()
];

// Fetch recent activity
$recentActivity = $db->query("
    SELECT a.*, u.username 
    FROM audit_log a 
    LEFT JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch today's appointments
$today = date('Y-m-d');
$todayAppointments = $db->query("
    SELECT * FROM appointments 
    WHERE appointment_date = '$today' 
    ORDER BY appointment_time ASC
")->fetchAll(PDO::FETCH_ASSOC);

// System health check
$systemHealth = [
    'database' => true, 
    'disk_space' => true, // Always assume sufficient space
    'upload_dir' => defined('UPLOADS_PATH') ? is_writable(UPLOADS_PATH) : false, 
    'last_backup' => defined('ROOT_PATH') ? (@filemtime(ROOT_PATH . '/backup') ?: 0) : 0
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link rel="stylesheet" href="../assets/css/dark-theme.css">
	<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
	<style>
		.weather-widget {
			background: rgba(255,255,255,0.1);
			border-radius: 10px;
			padding: 1rem;
		}
		.notification {
			position: fixed;
			top: 20px;
			right: 20px;
			z-index: 1000;
			display: none;
		}
		.sidebar {
			min-height: 100vh;
			background: var(--dark-secondary);
			padding: 1rem;
		}
		.nav-link {
			color: var(--dark-text);
			padding: 0.8rem 1rem;
			border-radius: 5px;
			margin-bottom: 0.5rem;
		}
		.nav-link:hover {
			background: var(--accent-color);
			color: white;
		}
		.nav-link.active {
			background: var(--accent-color);
			color: white;
		}
		.statistics-section {
			background: linear-gradient(135deg, <?php echo CURRENT_THEME === 'dark' ? $hero_gradient_start : '#f8f9fa'; ?> 0%, <?php echo CURRENT_THEME === 'dark' ? $hero_gradient_end : '#e9ecef'; ?> 100%);
		}

		.stat-card {
			background: <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'; ?>;
			border: 1px solid <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'; ?>;
			border-radius: 10px;
			transition: transform 0.3s ease;
		}

		.stat-card:hover {
			transform: translateY(-5px);
			background: <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)'; ?>;
			border-color: <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.2)'; ?>;
		}

		.stat-card i {
			color: <?php echo $theme_color; ?>;
		}

		.stat-card h2 {
			color: <?php echo CURRENT_THEME === 'dark' ? '#333' : '#333'; ?>;
		}

		.stat-card p {
			color: <?php echo CURRENT_THEME === 'dark' ? '#6c757d' : '#6c757d'; ?>;
		}
		.statistics-section {
			background: linear-gradient(135deg, <?php echo CURRENT_THEME === 'dark' ? $hero_gradient_start : '#f8f9fa'; ?> 0%, <?php echo CURRENT_THEME === 'dark' ? $hero_gradient_end : '#e9ecef'; ?> 100%);
		}

		.stat-card {
			background: <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'; ?>;
			border: 1px solid <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'; ?>;
			border-radius: 10px;
			transition: transform 0.3s ease;
		}

		.stat-card:hover {
			transform: translateY(-5px);
			background: <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.08)'; ?>;
			border-color: <?php echo CURRENT_THEME === 'dark' ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.2)'; ?>;
		}

		.stat-card i {
			color: <?php echo $theme_color; ?>;
		}

		.stat-card h2 {
			color: <?php echo CURRENT_THEME === 'dark' ? '#333' : '#333'; ?>;
		}

		.stat-card p {
			color: <?php echo CURRENT_THEME === 'dark' ? '#6c757d' : '#6c757d'; ?>;
		}
	</style>
</head>
<body class="dark-theme">
	<div class="container-fluid">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-md-3 col-lg-2 px-0 position-fixed sidebar">
				<div class="text-center mb-4">
					<i class="fas fa-user-md fa-3x text-primary mb-2"></i>
					<h5 class="mb-0">Dr. Afif Bashar</h5>
					<small class="text-muted">Admin Panel</small>
				</div>
				<nav class="nav flex-column">
					<a class="nav-link active" href="<?php echo url('admin/dashboard.php'); ?>">
						<i class="fas fa-tachometer-alt me-2"></i>Dashboard
					</a>
					<a class="nav-link" href="<?php echo url('admin/appointments.php'); ?>">
						<i class="fas fa-calendar-check me-2"></i>Appointments
					</a>
					<a class="nav-link" href="<?php echo url('admin/services.php'); ?>">
						<i class="fas fa-stethoscope me-2"></i>Services
					</a>
					<a class="nav-link" href="<?php echo url('admin/blog.php'); ?>">
						<i class="fas fa-blog me-2"></i>Blog Posts
					</a>
					<a class="nav-link" href="<?php echo url('admin/testimonials.php'); ?>">
						<i class="fas fa-star me-2"></i>Testimonials
					</a>
					<a class="nav-link" href="<?php echo url('admin/messages.php'); ?>">
						<i class="fas fa-envelope me-2"></i>Messages
					</a>
					<a class="nav-link" href="<?php echo url('admin/settings.php'); ?>">
						<i class="fas fa-cog me-2"></i>Settings
					</a>
					<a class="nav-link text-danger" href="<?php echo url('admin/logout.php'); ?>">
						<i class="fas fa-sign-out-alt me-2"></i>Logout
					</a>
				</nav>
			</div>

			<!-- Main Content -->
			<div class="col-md-9 col-lg-10 ms-auto px-4 py-3">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h2>Dashboard</h2>
					<span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
				</div>

				<!-- Statistics Cards -->
				<div class="row">
					<div class="col-md-6 col-lg-3 mb-4">
						<div class="card stat-card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<h6 class="card-title">Pending Appointments</h6>
										<h2 class="mb-0"><?php echo $stats['appointments']; ?></h2>
									</div>
									<i class="fas fa-calendar-check fa-2x text-primary"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 mb-4">
						<div class="card stat-card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<h6 class="card-title">New Testimonials</h6>
										<h2 class="mb-0"><?php echo $stats['testimonials']; ?></h2>
									</div>
									<i class="fas fa-star fa-2x text-warning"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 mb-4">
						<div class="card stat-card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<h6 class="card-title">Unread Messages</h6>
										<h2 class="mb-0"><?php echo $stats['messages']; ?></h2>
									</div>
									<i class="fas fa-envelope fa-2x text-info"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 mb-4">
						<div class="card stat-card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<h6 class="card-title">Total Blog Posts</h6>
										<h2 class="mb-0"><?php echo $stats['posts']; ?></h2>
									</div>
									<i class="fas fa-blog fa-2x text-success"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Quick Actions -->
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title mb-4">Quick Actions</h5>
								<div class="d-flex flex-wrap gap-2">
									<a href="<?php echo url('admin/blog.php?action=new'); ?>" class="btn btn-primary">
										<i class="fas fa-plus me-2"></i>New Blog Post
									</a>
									<a href="<?php echo url('admin/appointments.php'); ?>" class="btn btn-info text-white">
										<i class="fas fa-calendar me-2"></i>View Appointments
									</a>
									<a href="<?php echo url('admin/messages.php'); ?>" class="btn btn-warning text-dark">
										<i class="fas fa-envelope me-2"></i>Check Messages
									</a>
									<a href="<?php echo url('admin/testimonials.php'); ?>" class="btn btn-success">
										<i class="fas fa-star me-2"></i>Review Testimonials
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

                <!-- Recent Activity -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Activity</h5>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recentActivity as $activity): ?>
                                    <div class="list-group-item bg-transparent">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo ucfirst($activity['action']); ?></h6>
                                            <small><?php echo date('H:i', strtotime($activity['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-1"><?php echo $activity['username'] ?? 'System'; ?> - <?php echo $activity['table_name']; ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Today's Appointments</h5>
                                <?php if (empty($todayAppointments)): ?>
                                    <p class="text-muted">No appointments scheduled for today</p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($todayAppointments as $apt): ?>
                                        <div class="list-group-item bg-transparent">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($apt['patient_name']); ?></h6>
                                                <small><?php echo date('H:i', strtotime($apt['appointment_time'])); ?></small>
                                            </div>
                                            <p class="mb-1"><?php echo $apt['notes'] ?? 'No notes'; ?></p>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">System Health</h5>
                                <div class="row">
                                    <?php foreach ($systemHealth as $key => $status): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-circle me-2 <?php echo $status ? 'text-success' : 'text-danger'; ?>"></i>
                                            <span><?php echo ucwords(str_replace('_', ' ', $key)); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
</div>

<!-- Weather & Notifications -->
<div class="row">
	<div class="col-md-6 mb-4">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Weather Forecast</h5>
				<div class="weather-widget" id="weather-widget">
					<div class="text-center">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 mb-4">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">System Actions</h5>
				<button class="btn btn-primary mb-2" onclick="createBackup()">
					<i class="fas fa-download me-2"></i>Create Backup
				</button>
				<div id="backup-status"></div>
			</div>
		</div>
	</div>
</div>

<!-- Notification Toast -->
<div class="toast notification" role="alert">
	<div class="toast-header">
		<strong class="me-auto" id="toast-title">Notification</strong>
		<button type="button" class="btn-close" data-bs-dismiss="toast"></button>
	</div>
	<div class="toast-body" id="toast-message"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	// Weather update
	function updateWeather() {
		fetch('api/weather.php')
			.then(response => response.json())
			.then(data => {
				const widget = document.getElementById('weather-widget');
				widget.innerHTML = `
					<div class="d-flex align-items-center">
						<img src="http://openweathermap.org/img/w/${data.weather[0].icon}.png" alt="Weather icon">
						<div class="ms-3">
							<h3 class="mb-0">${Math.round(data.main.temp)}°C</h3>
							<p class="mb-0">${data.weather[0].description}</p>
						</div>
					</div>
				`;
			})
			.catch(() => {
				document.getElementById('weather-widget').innerHTML = 
					'<div class="text-muted">Weather data unavailable</div>';
			});
	}

	// Backup functionality
	function createBackup() {
		const status = document.getElementById('backup-status');
		status.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div> Creating backup...';
		
		fetch('api/backup.php')
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					status.innerHTML = `<div class="text-success">Backup created: ${data.file}</div>`;
					showNotification('Backup Created', 'Database backup completed successfully');
				} else {
					throw new Error(data.error);
				}
			})
			.catch(error => {
				status.innerHTML = `<div class="text-danger">Backup failed: ${error.message}</div>`;
				showNotification('Backup Failed', error.message, 'danger');
			});
	}

	// Notification helper
	function showNotification(title, message, type = 'success') {
		const toast = document.querySelector('.notification');
		const toastInstance = new bootstrap.Toast(toast);
		
		document.getElementById('toast-title').textContent = title;
		document.getElementById('toast-message').textContent = message;
		toast.classList.remove('bg-success', 'bg-danger');
		toast.classList.add(`bg-${type}`);
		
		toastInstance.show();
	}

	// Initialize
	updateWeather();
	setInterval(updateWeather, 300000); // Update weather every 5 minutes

	// Real-time notifications
	let lastCheck = new Date().toISOString();

	function checkNotifications() {
		fetch(`api/notifications.php?last_check=${encodeURIComponent(lastCheck)}`)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					const notifications = data.data;
					if (notifications.appointments > 0) {
						showNotification('New Appointments', `${notifications.appointments} new appointment(s) received`);
					}
					if (notifications.messages > 0) {
						showNotification('New Messages', `${notifications.messages} new message(s) received`);
					}
					if (notifications.testimonials > 0) {
						showNotification('New Testimonials', `${notifications.testimonials} new testimonial(s) received`);
					}
					lastCheck = data.timestamp;
				}
			})
			.catch(error => console.error('Notification check failed:', error));
	}

	// Check for notifications every minute
	setInterval(checkNotifications, 60000);

	// Backup status check
	function checkBackupStatus() {
		fetch('api/backup-status.php')
			.then(response => response.json())
			.then(data => {
				const statusHtml = `
					<div class="mt-3">
						<h6>Backup Status</h6>
						<div class="small">
							<div>Last Backup: ${data.last_backup || 'Never'}</div>
							<div>Backup Size: ${data.backup_size} MB</div>
							<div>Next Scheduled: ${data.next_backup || 'Not scheduled'}</div>
							<div class="mt-2">
								<span class="badge bg-${data.status === 'ok' ? 'success' : 'warning'}">
									${data.status === 'ok' ? 'Up to date' : 'Backup recommended'}
								</span>
							</div>
						</div>
					</div>
				`;
				document.getElementById('backup-status').innerHTML = statusHtml;
			})
			.catch(error => console.error('Backup status check failed:', error));
	}

	// Initialize backup status
	checkBackupStatus();
	setInterval(checkBackupStatus, 300000); // Check every 5 minutes

	// Popup after 5 seconds
	setTimeout(function() {
		const editModal = new bootstrap.Modal(document.getElementById('editModal'));
		editModal.show();
	}, 5000);

	// Editable popups
	const editModal = new bootstrap.Modal(document.getElementById('editModal'));

	function editItem(id, title, content) {
		document.getElementById('itemId').value = id;
		document.getElementById('itemTitle').value = title;
		document.getElementById('itemContent').value = content;
		editModal.show();
	}

	function saveItem() {
		const id = document.getElementById('itemId').value;
		const title = document.getElementById('itemTitle').value;
		const content = document.getElementById('itemContent').value;

		fetch('api/save-item.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				id: id,
				title: title,
				content: content
			})
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				showNotification('Success', 'Item updated successfully');
				editModal.hide();
				// Refresh the relevant section
				location.reload();
			} else {
				throw new Error(data.error);
			}
		})
		.catch(error => {
			showNotification('Error', error.message, 'danger');
		});
	}

	// Editable popups
	const editModal = new bootstrap.Modal(document.getElementById('editModal'));

	function editItem(id, title, content) {
		document.getElementById('itemId').value = id;
		document.getElementById('itemTitle').value = title;
		document.getElementById('itemContent').value = content;
		editModal.show();
	}

	function saveItem() {
		const id = document.getElementById('itemId').value;
		const title = document.getElementById('itemTitle').value;
		const content = document.getElementById('itemContent').value;

		fetch('api/save-item.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				id: id,
				title: title,
				content: content
			})
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				showNotification('Success', 'Item updated successfully');
				editModal.hide();
				// Refresh the relevant section
				location.reload();
			} else {
				throw new Error(data.error);
			}
		})
		.catch(error => {
			showNotification('Error', error.message, 'danger');
		});
	}
</script>

<!-- Editable Popups -->
<div class="modal fade" id="editModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Item</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form id="editForm">
					<input type="hidden" name="id" id="itemId">
					<div class="mb-3">
						<label class="form-label">Title</label>
						<input type="text" class="form-control" name="title" id="itemTitle" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Content</label>
						<textarea class="form-control" name="content" id="itemContent" rows="4" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="saveItem()">Save</button>
			</div>
		</div>
	</div>
</div>
