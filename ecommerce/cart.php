<?php
    session_start();
    require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");

    
    include(ROOT_DIR."app/config/DatabaseConnect.php");
    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    $carts          = [];
    $id             = @$_GET['id'];
    $userId         = $_SESSION["user_id"];
    $subtotal       = 0;
    $purchase_total = 0;


    try {

        $sql = "SELECT carts.id, products.product_name, carts.quantity, carts.unit_price, carts.total_price " 
                . " FROM carts "
                . " LEFT JOIN products ON products.id = carts.product_id"
                . " WHERE carts.user_id = $userId AND carts.status = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $carts = $stmt->fetchAll();


    } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();
        $db = null;

    }

    require_once(ROOT_DIR."includes/header.php");

    if(isset($_SESSION["success"])){
        $messageSuccess = $_SESSION["success"];
        unset($_SESSION["success"]);

    }


    if(isset($_SESSION["error"])){
        $messageError = $_SESSION["error"];
        unset($_SESSION["error"]);

    }
?>

    <!-- Navbar -->
    <?php
    require_once("includes/navbar.php")
    ?>

   <?php 
        if(!isset($_SESSION["username"])){
            header("location: ".BASE_URL."login.php");
            exit;
        }
   ?>

    <!-- Shopping Cart -->
    <div class="container content mt-5">
        <!-- Message Response -->
        <?php if (isset( $messageSuccess)){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo  $messageSuccess; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php } ?>
    

                    <?php if (isset($messageError)){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><strong><?php echo $messageError; ?></strong></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
        <?php } ?>

        <div class="row">
            <!-- Shopping Cart Items -->
            <div class="col-md-8">
                <h3>Shopping Cart</h3>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($carts){ 
                        foreach($carts as $indvCart){
                            $subtotal += $indvCart["total_price"];
                            
                        ?>
                        <tr>
                            <td><?php echo $indvCart["product_name"]; ?></td>
                            <td><?php echo $indvCart["quantity"]; ?></td>
                            <td><?php echo number_format ($indvCart["unit_price"],2); ?></td>
                            <td><?php echo number_format ($indvCart["total_price"],2); ?></td>
                        </tr>
                        <?php } //end of foreach?>
                        <?php } else {?>
                            <tr>
                                <td colspan="4">You do not have any products yet</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary and Payment -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Order Summary</h4>
                    </div>
                    <div class="card-body">
                            <?php if($carts){ ?>
                        <form action="<?php echo BASE_URL;?>app/cart/confirm_payment.php" method="POST">
                        <p>Subtotal: <span class="float-end">Php <?php echo number_format ($subtotal,2); ?></span></p>
                        <p>Shipping: <span class="float-end">Php 50.00</span></p>
                        <hr>
                        <h5>Total: <span class="float-end">Php <?php echo number_format($subtotal + 50,2); ?></span></h5>

                        <input type="hidden" class="form-control" name="total_order" value="<?php echo $subtotal; ?>">
                        <input type="hidden" class="form-control" name="delivery_fee" value="50">
                        <input type="hidden" class="form-control" name="total_amount" value="<?php echo ($subtotal + 50); ?>">

                        <!-- Payment Method Selection -->
                        <div class="mt-4">
                            <label for="paymentMethod" class="form-label">Select Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="payment_method" required>
                                <option value="1">Credit/Debit Card</option>
                                <option value="2">PayPal</option>
                                <option value="3">GCash</option>
                            </select>
                        </div>

                        <!-- Payment Details -->
                        <div class="mt-3">
                            <label for="cardNumber" class="form-label">Card/Account Number</label>
                            <input type="text" class="form-control" id="cardNumber" name="card_number" placeholder="Enter your card or account number" required>
                        </div>

                        <!-- Confirm Payment Button -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success">Confirm Payment</button>
                        </div>
                    </form>

                        <?php } else { ?>
                                <p class="text-center">No product details yet</p>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
   

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>