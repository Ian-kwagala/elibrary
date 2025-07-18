<?php 
require_once 'templates/admin_header.php'; 

// --- Fetch all necessary data for the dashboard ---
try {
    $total_students = $pdo->query("SELECT count(id) FROM users WHERE role = 'student'")->fetchColumn();
    $total_materials = $pdo->query("SELECT count(id) FROM materials")->fetchColumn();
    $borrowed_books = $pdo->query("SELECT count(id) FROM borrowings WHERE status = 'borrowed'")->fetchColumn();
    $overdue_books = $pdo->query("SELECT count(id) FROM borrowings WHERE status = 'borrowed' AND due_date < NOW()")->fetchColumn();
    $chart_materials_data = $pdo->query("SELECT material_type, COUNT(id) as count FROM materials GROUP BY material_type")->fetchAll(PDO::FETCH_ASSOC);
    $material_labels = []; $material_data = [];
    foreach ($chart_materials_data as $item) {
        $material_labels[] = ucfirst(str_replace('_', ' ', $item['material_type']));
        $material_data[] = $item['count'];
    }
    $recent_materials = $pdo->query("SELECT id, title, material_type, created_at FROM materials ORDER BY created_at DESC LIMIT 5")->fetchAll();
    $recent_borrowings = $pdo->query("SELECT u.full_name, m.title, b.borrow_date FROM borrowings b JOIN users u ON b.user_id = u.id JOIN materials m ON b.material_id = m.id ORDER BY b.borrow_date DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {
    $total_students = $total_materials = $borrowed_books = $overdue_books = 0;
    $material_labels = $material_data = $recent_materials = $recent_borrowings = [];
    error_log("Dashboard query failed: " . $e->getMessage());
}
?>
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <button class="btn btn-primary" onclick="openMaterialModal()">
        <i class="fas fa-plus"></i> Add New Material
    </button>
</div>

<!-- Animated Statistic Cards -->
<div class="stat-card-grid">
    <div class="stat-card blue"><div class="stat-card-info"><h4><?php echo $total_students; ?></h4><p>Total Students</p></div><div class="stat-card-icon"><i class="fas fa-users"></i></div></div>
    <div class="stat-card green"><div class="stat-card-info"><h4><?php echo $total_materials; ?></h4><p>Total Library Items</p></div><div class="stat-card-icon"><i class="fas fa-book-open"></i></div></div>
    <div class="stat-card orange"><div class="stat-card-info"><h4><?php echo $borrowed_books; ?></h4><p>Items Currently Borrowed</p></div><div class="stat-card-icon"><i class="fas fa-hand-holding-heart"></i></div></div>
    <div class="stat-card red"><div class="stat-card-info"><h4><?php echo $overdue_books; ?></h4><p>Items Overdue</p></div><div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div></div>
</div>

<!-- Charts Section -->
<div class="form-grid" style="margin-top: 30px; grid-template-columns: 1fr 2fr;">
    <div class="card"><div class="card-header"><h3 class="card-title">Materials by Type</h3></div><div class="card-body" style="height: 350px;"><canvas id="materialsChart"></canvas></div></div>
    <div class="card"><div class="card-header"><h3 class="card-title">Borrowing Activity (Last 7 Days)</h3></div><div class="card-body" style="height: 350px;"><canvas id="borrowingsChart"></canvas></div></div>
</div>

<!-- Recent Activity Tables -->
<div class="form-grid">
    <div class="card"><div class="card-header"><h3 class="card-title">Recently Added Materials</h3></div><div class="card-body" style="padding: 0;">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Type</th><th>Date Added</th></tr></thead>
            <tbody>
                <?php if(empty($recent_materials)): ?><tr><td colspan="3" style="text-align: center;">No materials added recently.</td></tr><?php else: foreach($recent_materials as $item): ?>
                <tr><td><?php echo htmlspecialchars($item['title']); ?></td><td><span class="badge badge-<?php echo str_replace('_', '-', $item['material_type']); ?>"><?php echo htmlspecialchars($item['material_type']); ?></span></td><td><?php echo date('M j, Y', strtotime($item['created_at'])); ?></td></tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div></div>
    <div class="card"><div class="card-header"><h3 class="card-title">Recent Borrowings</h3></div><div class="card-body" style="padding: 0;">
        <table class="admin-table">
            <thead><tr><th>Student Name</th><th>Item Borrowed</th><th>Date</th></tr></thead>
            <tbody>
                <?php if(empty($recent_borrowings)): ?><tr><td colspan="3" style="text-align: center;">No recent borrowing activity.</td></tr><?php else: foreach($recent_borrowings as $item): ?>
                <tr><td><?php echo htmlspecialchars($item['full_name']); ?></td><td><?php echo htmlspecialchars($item['title']); ?></td><td><?php echo date('M j, Y, g:i a', strtotime($item['borrow_date'])); ?></td></tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div></div>
</div>

<!-- Include the modal HTML from the partial file for the "Add" button -->
<?php include_once __DIR__ . '/manage_books_modal.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const materialsCtx = document.getElementById('materialsChart');
    if (materialsCtx) { new Chart(materialsCtx, { type: 'doughnut', data: { labels: <?php echo json_encode($material_labels); ?>, datasets: [{ data: <?php echo json_encode($material_data); ?>, backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6366f1', '#8b5cf6'], borderColor: '#fff', borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } }); }
    const borrowingsCtx = document.getElementById('borrowingsChart');
    if (borrowingsCtx) {
        const borrowingDataRaw = <?php echo json_encode($pdo->query("SELECT DATE(borrow_date) as date, COUNT(id) as count FROM borrowings WHERE borrow_date >= CURDATE() - INTERVAL 6 DAY GROUP BY DATE(borrow_date)")->fetchAll(PDO::FETCH_KEY_PAIR)); ?>;
        const chartLabels = []; const chartData = [];
        for (let i = 6; i >= 0; i--) { const d = new Date(); d.setDate(d.getDate() - i); const dateString = d.toISOString().split('T')[0]; const formattedLabel = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }); chartLabels.push(formattedLabel); chartData.push(borrowingDataRaw[dateString] || 0); }
        new Chart(borrowingsCtx, { type: 'bar', data: { labels: chartLabels, datasets: [{ label: 'Items Borrowed', data: chartData, backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: 'rgba(59, 130, 246, 1)', borderWidth: 1, borderRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } } });
    }
});
</script>

<?php 
echo '</main></div>'; 
echo '<script src="' . BASE_URL . '/admin/assets/js/admin_script.js"></script>';
?>
</body>
</html>