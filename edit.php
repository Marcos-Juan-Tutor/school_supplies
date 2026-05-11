<?php
include 'database.php';

$id = intval($_GET['id']);
$error = "";

$stmt = $conn->prepare("SELECT * FROM supplies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: index.php?msg=notfound");
    exit();
}

if (isset($_POST['update'])) {
    $item        = trim($_POST['item']);
    $category    = trim($_POST['category']);
    $price       = floatval($_POST['price']);
    $quantity    = intval($_POST['quantity']);
    $amount_sold = intval($_POST['amount_sold']);
    $revenue     = $price * $amount_sold;

    if (empty($item) || empty($category) || $price <= 0) {
        $error = "Please fill in all fields with valid values.";
    } else {
        $stmt = $conn->prepare("UPDATE supplies SET item=?, category=?, amount_sold=?, price=?, quantity=?, revenue=? WHERE id=?");
        $stmt->bind_param("ssididi", $item, $category, $amount_sold, $price, $quantity, $revenue, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $error = "Update failed. Please try again.";
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item — Supplies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrapper">
        <nav class="topbar">
            <a href="index.php" class="nav-back">← Back to Inventory</a>
            <span class="nav-title">Supplies Manager</span>
        </nav>

        <div class="form-container">
            <div class="form-header">
                <h1>Edit Item</h1>
                <p>Update the details for <strong><?php echo htmlspecialchars($row['item']); ?></strong>.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="supply-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="item">Item Name</label>
                        <input type="text" id="item" name="item" required
                               value="<?php echo htmlspecialchars($row['item']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" required
                               value="<?php echo htmlspecialchars($row['category']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="price">Price (₱)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0.01" required
                               value="<?php echo htmlspecialchars($row['price']); ?>"
                               oninput="calcRevenue()">
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity in Stock</label>
                        <input type="number" id="quantity" name="quantity" min="0" required
                               value="<?php echo htmlspecialchars($row['quantity']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="amount_sold">Amount Sold</label>
                        <input type="number" id="amount_sold" name="amount_sold" min="0" required
                               value="<?php echo htmlspecialchars($row['amount_sold']); ?>"
                               oninput="calcRevenue()">
                    </div>

                    <div class="form-group revenue-preview">
                        <label>Estimated Revenue</label>
                        <div class="revenue-display" id="revenueDisplay">
                            ₱<?php echo number_format($row['revenue'], 2); ?>
                        </div>
                        <small>Auto-calculated: Price × Amount Sold</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcRevenue() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const sold = parseFloat(document.getElementById('amount_sold').value) || 0;
            const revenue = price * sold;
            document.getElementById('revenueDisplay').textContent = '₱' + revenue.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    </script>
</body>
</html>
