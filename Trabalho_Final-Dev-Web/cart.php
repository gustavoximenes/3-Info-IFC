<?php
include 'database.php';
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add a book to the cart
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $_SESSION['cart'][] = $bookId;
    header("Location: cart.php");
    exit();
}

// Retrieve details of books in the cart
$cartItems = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", $_SESSION['cart']);
    $sql = "SELECT * FROM Livro WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Your Cart</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalPrice = 0;
            foreach ($cartItems as $item): 
                $totalPrice += $item['preco'];
            ?>
            <tr>
                <td><?= $item['titulo'] ?></td>
                <td>$<?= number_format($item['preco'], 2) ?></td>
                <td>1</td>
                <td>$<?= number_format($item['preco'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Total: $<?= number_format($totalPrice, 2) ?></h3>
    <a href="purchase.php" class="btn btn-primary">Proceed to Checkout</a>
</div>
</body>
</html>
