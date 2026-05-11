<?php
include 'database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$msg    = isset($_GET['msg']) ? $_GET['msg'] : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM supplies WHERE item LIKE ? OR category LIKE ? ORDER BY id DESC");
    $like = '%' . $search . '%';
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM supplies ORDER BY id DESC");
}

// Stats
$stats = $conn->query("SELECT COUNT(*) as total, SUM(revenue) as total_revenue, SUM(quantity) as total_stock FROM supplies")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplies Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if ($msg): ?>
    <div class="toast" id="toast">
        <?php
            if ($msg === 'created') echo '✓ Item added successfully!';
            elseif ($msg === 'updated') echo '✓ Item updated successfully!';
            elseif ($msg === 'deleted') echo '✓ Item deleted.';
            elseif ($msg === 'notfound') echo '⚠ Item not found.';
        ?>
    </div>
    <script>
        setTimeout(() => {
            const t = document.getElementById('toast');
            t.classList.add('toast-hide');
        }, 3000);
    </script>
    <?php endif; ?>

    <div class="page-wrapper">
        <nav class="topbar">
            <span class="nav-title">Supplies Manager</span>
        </nav>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-value"><?php echo intval($stats['total']); ?></span>
                    <span class="stat-label">Total Items</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format(intval($stats['total_stock'])); ?></span>
                    <span class="stat-label">Units in Stock</span>
                </div>
            </div>
            <div class="stat-card highlight">
                <div class="stat-info">
                    <span class="stat-value">₱<?php echo number_format(floatval($stats['total_revenue']), 2); ?></span>
                    <span class="stat-label">Total Revenue</span>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="toolbar">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search items or categories..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
                <?php if ($search): ?>
                    <a href="index.php" class="clear-search">✕ Clear</a>
                <?php endif; ?>
            </form>
            <span class="result-count">
                <?php echo ($result->num_rows); ?> item<?php echo $result->num_rows !== 1 ? 's' : ''; ?> found
            </span>
            <a href="create.php" class="btn btn-primary btn-sm">+ Add Item</a>
        </div>

        <!-- Table -->
        <div class="table-wrapper">
            <?php if ($result->num_rows === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>No items found</h3>
                    <p><?php echo $search ? 'No results for "' . htmlspecialchars($search) . '".' : 'Your inventory is empty.'; ?></p>
                    <?php if (!$search): ?>
                        <a href="create.php" class="btn btn-primary">Add Your First Item</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
            <table class="supply-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Qty in Stock</th>
                        <th>Amount Sold</th>
                        <th>Revenue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="id-cell"><?php echo $row['id']; ?></td>
                        <td class="item-name"><?php echo htmlspecialchars($row['item']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars($row['category']); ?></span></td>
                        <td>₱<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo number_format($row['quantity']); ?></td>
                        <td><?php echo number_format($row['amount_sold']); ?></td>
                        <td class="revenue-cell">₱<?php echo number_format($row['revenue'], 2); ?></td>
                        <td class="actions-cell">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-action btn-delete"
                               onclick="return confirm('Delete \'<?php echo htmlspecialchars(addslashes($row['item'])); ?>\'? This cannot be undone.')">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
