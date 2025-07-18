<?php
$page_title = 'My Account';
require_once '../includes/config.php';

// Security: Ensure user is logged in and is a student
if (!isLoggedIn()) {
    redirect(BASE_URL . '/auth/login.php');
}
if (isAdmin()) {
    redirect(BASE_URL . '/admin/dashboard.php');
}

$user_id = $_SESSION['user_id'];

// Fetch user's details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// Fetch user's currently borrowed items
$stmt = $pdo->prepare(
    "SELECT b.id as borrowing_id, b.due_date, m.title, m.cover_image, m.id as material_id
     FROM borrowings b
     JOIN materials m ON b.material_id = m.id
     WHERE b.user_id = :user_id AND b.status = 'borrowed'
     ORDER BY b.due_date ASC"
);
$stmt->execute(['user_id' => $user_id]);
$borrowed_items = $stmt->fetchAll();

// Fetch user's notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 15");
$stmt->execute(['user_id' => $user_id]);
$notifications = $stmt->fetchAll();

require_once '../includes/templates/header.php';
?>

<style>
/* Page-specific styles for the dashboard */
.dashboard-container { display: flex; gap: 30px; }
.dashboard-nav { flex: 0 0 220px; }
.dashboard-content { flex-grow: 1; }
.dashboard-nav ul { list-style: none; padding: 0; margin: 0; background: var(--light-gray); border-radius: 8px; overflow: hidden; }
.dashboard-nav a { display: block; padding: 15px 20px; color: var(--dark-gray); text-decoration: none; border-bottom: 1px solid #ddd; transition: background-color 0.3s; font-weight: 600; }
.dashboard-nav a:hover, .dashboard-nav a.active { background-color: var(--secondary-color); color: #fff; }
.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.5s; }
.profile-form .form-group { margin-bottom: 20px; }
.profile-form label { font-weight: 600; display: block; margin-bottom: 5px; }
.profile-form input { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; background: #f9f9f9; }
.borrowed-item-card { display: flex; align-items: center; gap: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px; margin-bottom: 15px; }
.borrowed-item-card img { width: 80px; height: 110px; object-fit: cover; border-radius: 5px; }
.borrowed-item-info { flex-grow: 1; }
.borrowed-item-info h4 { margin: 0 0 10px; }
.notification-item { padding: 10px; border-bottom: 1px solid #eee; }
.notification-item:last-child { border-bottom: none; }
</style>

<div class="page-section">
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></h1>
        
        <div class="dashboard-container">
            <aside class="dashboard-nav">
                <ul>
                    <li><a href="#profile" class="tab-link active">My Profile</a></li>
                    <li><a href="#borrowings" class="tab-link">My Borrowings (<?php echo count($borrowed_items); ?>)</a></li>
                    <li><a href="#notifications" class="tab-link">Notifications</a></li>
                </ul>
            </aside>

            <main class="dashboard-content">
                <!-- Profile Tab -->
                <div id="profile" class="tab-content active">
                    <h2>Account Details</h2>
                    <form action="handlers/profile_handler.php" method="POST" class="profile-form">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Student Number</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['student_number']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Course</label>
                            <input type="text" name="course" value="<?php echo htmlspecialchars($user['course']); ?>">
                        </div>
                        <button type="submit" name="update_details" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <!-- Borrowings Tab -->
                <div id="borrowings" class="tab-content">
                    <h2>My Borrowed Items</h2>
                    <?php if (empty($borrowed_items)): ?>
                        <p>You have no items currently borrowed.</p>
                    <?php else: ?>
                        <?php foreach ($borrowed_items as $item): 
                            $is_overdue = strtotime($item['due_date']) < time();
                        ?>
                            <div class="borrowed-item-card">
                                <img src="<?php echo BASE_URL . '/uploads/book_covers/' . ($item['cover_image'] ?: 'default_book.png'); ?>" alt="">
                                <div class="borrowed-item-info">
                                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                                    <p style="<?php echo $is_overdue ? 'color: red; font-weight: bold;' : ''; ?>">
                                        Due Date: <?php echo date('F j, Y', strtotime($item['due_date'])); ?>
                                        <?php if ($is_overdue) echo " (Overdue)"; ?>
                                    </p>
                                </div>
                                <a href="handlers/return_handler.php?id=<?php echo $item['borrowing_id']; ?>" class="btn btn-secondary" onclick="return confirm('Are you sure you want to return this book?');">Return Book</a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Notifications Tab -->
                <div id="notifications" class="tab-content">
                    <h2>My Notifications</h2>
                    <div class="notification-list">
                        <?php if (empty($notifications)): ?>
                            <p>You have no new notifications.</p>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="notification-item">
                                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                    <small style="color: #777;"><?php echo date('M j, Y, g:i a', strtotime($notification['created_at'])); ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>

<?php require_once '../includes/templates/footer.php'; ?>// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
// Simple tabbing script for dashboard
document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const targetId = link.getAttribute('href');

            // Update active link
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            // Update active content
            tabContents.forEach(content => {
                if ('#' + content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });
});
</script>
