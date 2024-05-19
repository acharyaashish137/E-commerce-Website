<?php
include 'dbConnect.php';

session_start();

if (!isset($_SESSION['customerID'])) {
    echo '<a href="/account/LoginPage.php">Login</a>';
}

if (isset($_SESSION['customerID'])) {
    echo '<a href="/account/logout.php">Logout</a>';
	$customerID = $_SESSION['customerID'];
	$email = $_SESSION['email'];
}



if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['new_quantity'] as $itemIndex => $newQuantity) {
            if (isset($_SESSION['cart'][$itemIndex]) && is_numeric($newQuantity) && $newQuantity > 0) {
                $_SESSION['cart'][$itemIndex]['quantity'] = $newQuantity;
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $itemIndex = $_POST['remove_item'];
        if (isset($_SESSION['cart'][$itemIndex])) {
            unset($_SESSION['cart'][$itemIndex]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

$isEmpty = empty($_SESSION['cart']);

if($isEmpty){
	$subtotal = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peak Cart</title>

   <link rel="stylesheet" href="cart.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   

</head>

<body>
    <div class="container">
        <div class="navbar">
        
            <img src="https://i.postimg.cc/bwXcStHR/Logo.png" class="Logo">
            <nav>
                <a href="../pages/cart.php">MY CART</a>
                <a href="../pages/cart.php">
<img src="https://i.postimg.cc/rFR2pJWm/cart.png" class="cart"></a>
            </nav>
        </div>
    </div>

    <div class="Slogan">
        <p></p>
        <p><b>Scaling New Heights in Fashion, Living and Wellness</b></p>
    </div>

    <div class="Navigation">
        <a href="/home.php"><button type="button" class="buttonOne">Home</button></a>
        <a href="../pages/catalog.php"><button type="button" class="buttonTwo">Catalog</button></a>
        <a href="../account/accCreate.php"><button type="button" class="buttonThree">Account</button></a>
        <a href="../pages/checkout.php"><button type="button" class="buttonFour">Check Out</button></a>
        <a href="../pages/about.php"><button type="button" class="buttonFive">About Us</button></a>
    </div>
    <div class="cart-div">
        <h2>Shopping Cart</h2>
        <div class="cart-table-div">
        <?php if ($isEmpty): ?>
            <div class="table empty-cart">
            <table>
            <tr><td>Your cart is empty.</td></tr>
            <tr><td><a href="../pages/catalog.php"><button class="btn-group">Add Items</a></tr>
            </table>
            </div>
        <?php else: ?>
            <div class="table">
            <form method="post" action="">
                <table border="1">
                <?php $subtotal = 0; ?>
                <?php foreach ($_SESSION['cart'] as $index => $cartItem): ?>
                <?php
                    $itemTotal = $cartItem['price'] * $cartItem['quantity'];
                    $subtotal += $itemTotal;
                ?>
                    <tr>
                        <td>
                            <img src="<?php echo $cartItem['image']; ?>" alt="<?php echo $cartItem['name']; ?>" width="50">
                        </td>
                        <td>
                            <?php echo $cartItem['name']; ?>
                        </td>
                        <td>
                            $<?php echo $cartItem['price']; ?>
                        </td>
                        <td>
                            <input type="number" name="new_quantity[<?php echo $index; ?>]" value="<?php echo $cartItem['quantity']; ?>" min="1">
                        <td>
                            $<?php echo number_format($itemTotal, 2); ?>
                        </td>
                        <td>
                            <button type="submit" name="remove_item" value="<?php echo $index; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
        </div>
        <?php endif; ?>
            <div class="table-side-div">
                <div class="blue">
                    <p>Subtotal <span class="rightalign">$<?php echo number_format($subtotal, 2); ?></span></p>
                <p>Shipping and taxes will
                    be calculated during
                    checkout</p>
                </div>
                <a href="../pages/checkout.php"><button class="btn-group" type="button">Proceed to Checkout</button></a>
            </div>
        </div>
        <div class="cart-button ">
            <a href="/pages/catalog.php"><button class="btn-group" type="button">Continue Shopping</button></a>
                <button type="submit" name="update_cart" class="btn-group">Update Cart</button>
            </form>
      
        </div>
    </div>



</body>

</html>