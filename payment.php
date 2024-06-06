<?php
session_start();
if (isset($_SESSION["message"])) {
    // Handle the error appropriately
    $message = $_SESSION["message"];
    unset($_SESSION["message"]);
}
unset($_SESSION["permission"]);
unset($_SESSION["ID"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    
<?php
    require_once("header.php");
?>

    <div class="main-content">
        <div class="container">
            
            <?php /*
            <div class="above">
                <?php
                    if (isset($message)) {
                        echo "<p style='color:red'>$message</p>";
                    }
                ?>
            </div>
            */?>
            
            <div class="below">
                <div class="form-container">
                    <?php
                    require_once("connect.php");

                    $errorCreate = 0;
                    $errorEdit = 0;
                    $errorCard = 0;
                    $errorCardMissing = 0;
                    $errorShowPayment = 0;
                    

                    if (isset($_POST['EditBtn'])) {
                        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
                            $errorEdit = 0;
                            
                            $idEdit = $_POST['edit_id'];

                            $_SESSION["permission"] = "yes";
                            $_SESSION["ID"] = $idEdit;

                            header("Location: edit_Payment.php");
                            exit();
                        } else {
                            $errorEdit = 1;
                        }
                    }

                    if (isset($_POST['ShowPaymentBtn'])) {
                        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
                            $errorEdit = 0;
                            
                            $idEdit = $_POST['edit_id'];

                            $_SESSION["permission"] = "yes";
                            $_SESSION["ID"] = $idEdit;

                            header("Location: edit_Payment.php");
                            exit();
                        } else {
                            $errorShowPayment = 1;
                        }
                    }


                    if(isset($_POST['addCardBtn'])) {
                        $staff_id = $_POST['staff_id'];
                        $customer_id = $_POST['customer_id'];
                        $quantity = $_POST['quantity'];

                        $product_id_price = $_POST['product_id_price'];
                    
                        if ($quantity <= 0) {
                            $errorCard = 1;
                        }
                        else {
                            if ($staff_id == 0 || $customer_id == 0 || $product_id_price == 0) {
                                $errorCardMissing = 1;
                            }
                            else {
                                list($product_id, $price) = explode(',', $product_id_price);

                                $_SESSION['staff_id'] = $staff_id;
                                $_SESSION['customer_id'] = $customer_id;
                                $price *= $quantity;
                                // Initialize cart session if not already set
                                if (!isset($_SESSION['cart'])) {
                                    $_SESSION['cart'] = [];
                                }

                                // Add product and quantity to cart
                                $_SESSION['cart'][] = [
                                    'product_id' => $product_id,
                                    'quantity' => $quantity,
                                    'price' => $price
                                ];

                                $_SESSION['overall'] += $price;

                                header("Location: payment.php");
                                exit();
                            }
                        }
                    }
                    if (isset($_POST['deleteCardbtn'])) {
                        unset($_SESSION['cart']);
                        unset($_SESSION["staff_id"]);
                        unset($_SESSION["customer_id"]);
                        unset($_SESSION['overall']);
                    }

                    if (isset($_POST['addPaymentBtn'])) {
                        $staff_id = $_POST['staff_id'];
                        $customer_id = $_POST['customer_id'];
                        
                        if ($staff_id == 0 || $customer_id == 0 || !isset($_SESSION['overall'])) {
                            $errorCreate = 1;
                        } else {   
                            $amount = $_SESSION['overall'];
                
                            $sql = "INSERT INTO payment (staff_id, customer_id , amount) VALUES ('$staff_id', '$customer_id', '$amount')";
                            $isInserted = $conn->query($sql);  

                            if ($isInserted) {
                                $paymentID = $conn->insert_id;
                                
                                foreach ($_SESSION['cart'] as $item) {
                                    $product_id = $item['product_id'];
                                    $quantity = $item['quantity'];
                                    
                                    $sql = "INSERT INTO payment_details (payment_id, product_id , quantity) VALUES ('$paymentID', '$customer_id', '$quantity')";
                                    $result = $conn->query($sql);
                                }
                                
                                unset($_SESSION['cart']);
                                unset($_SESSION["staff_id"]);
                                unset($_SESSION["customer_id"]);
                                unset($_SESSION['overall']);

                                header("Location: payment.php");      
                                exit();                       
                            } 
                            else {
                                echo "errorCreateor: " . $sql . "<br>" . mysqli_errorCreateor($conn);
                            }
                        }
                    }
                    ?>
                    <form class="payment-form" method="POST">
                        <label for="staff_id">Staff ID</label>
                        <input type="number" id="staff_id" min="1" name="staff_id" value="<?php echo isset($_SESSION['staff_id']) ? $_SESSION['staff_id'] : ''; ?>"><br><br>

                        <label for="customer_id">Customer</label> 
                        <input type="number" id="customer_id" min="1" name="customer_id" value="<?php echo isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : ''; ?>"><br><br>

                        <label for="product">Product</label>
                        <select name="product_id_price" id="product">
                            <option value="-1"></option>
                    <?php
                        $sqlSelect = "SELECT * FROM product";
                        $resultProduct = $conn->query($sqlSelect);

                        if ($resultProduct->num_rows > 0) {
                            $products = $resultProduct->fetch_all(MYSQLI_ASSOC);
                        }

                        foreach ($products as $product) {
                            ?>
                                <option value="<?php echo $product['product_id'] . ',' . $product['price']; ?>">
                                    <?php echo $product['name'] ?>
                                </option>
                            <?php
                            }
                    ?>
                        </select><br><br>
                        
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="0"><br><br>

                        <?php
                        if ($errorCreate == 1) {
                        ?>
                            <p style='color:red'> Niečo ste nevyplnili </p>
                        <?php
                        }

                        if ($errorCard == 1) {
                         ?>
                            <p style='color:red'> Pocet produktu je 0 </p>
                        <?php
                        }

                        if ($errorCardMissing == 1) {
                            ?>
                               <p style='color:red'> Zabudli ste nieco vyplnit </p>
                           <?php
                           }
                        ?>

                        

                        <input type="submit" name="addCardBtn" value="Add to Card">
                        <input type="submit" name="addPaymentBtn" value="Insert">
                    </form>
                </div>

                <div class="cart">
                    <div>
                        <h2>Your Cart</h2>
                    </div>
                    <?php
                        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        ?>
                            <table>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                </tr>
                        <?php
                            foreach ($_SESSION['cart'] as $item) {
                                $product_id = $item['product_id'];
                                $quantity = $item['quantity'];
                                
                                // Fetch product details from database
                                $sql = "SELECT name FROM product WHERE product_id = $product_id";
                                $result = $conn->query($sql);
                                $product = $result->fetch_assoc();
                        ?>
                    
                                <tr>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $quantity; ?></td>
                                </tr>
                        <?php
                            }
                        ?>      
                                 
                            </table>
                                <h2>Overall : <?php echo $_SESSION['overall'] ?> $</h2>
                            <form method="post">
                                    <input type="submit" name="deleteCardbtn" value="Delete Card">
                            </form>   
                        <?php
                        } 
                        else {
                        ?>
                            <h3>Your cart is empty</h3>
                        <?php
                        }
                    ?>
                </div>


                <div class="sql-table">       
                <?php
                    $sql = "SELECT * FROM payment ORDER BY payment_id DESC LIMIT 10";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $payment = $result->fetch_all(MYSQLI_ASSOC);
                    
                    ?>
                    <div class="edit-div">
                        <form method="post">
                            <table>
                                <tr>
                                    <th></th>
                                    <th>Id</th>
                                    <th>Staff Id</th>
                                    <th>Customer Id</th>
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                    <th>Store Address</th>
                                </tr>

                            <?php
                                foreach ($payment as $row) 
                                {
                            ?>
                                <tr>
                                    <td>
                                         <input type="radio" id="<?php echo $row["payment_id"]; ?>" name="edit_id" value="<?php echo $row["payment_id"]; ?>">
                                        <label for="<?php echo $row["payment_id"]; ?>">
                                    </td>
                                    <td> <?php echo $row["payment_id"] ?> </td>
                                    <td> <?php echo $row["staff_id"]  ?> </td>
                                    <td> <?php echo $row["customer_id"]   ?> </td>
                                    <td> <?php echo $row["amount"]   ?> </td>
                                    <td> <?php echo $row["payment_date"]?> </td>
                                    <td> <?php echo $row["store_address"]     ?> </td>
                                </tr>
                            <?php                   
                                }
                        ?>
                            </table>
                        <?php
                            if ($errorEdit == 1) {
                            ?>
                                <p style='color:red'> Choose payments to edit. </p>
                            <?php
                            }
                            if ($errorShowPayment == 1) {
                                ?>
                                    <p style='color:red'> Choose payments to show. </p>
                                <?php
                                }
                            
                    }
                    else {
                    ?>
                        <p style='color:red'> None Payments. </p>
                    <?php
                    }     
                    ?>
                        <input type="submit" name="EditBtn" value="Edit">
                        <input type="submit" name="ShowPaymentBtn" value="Show Payment">
                        </form>
                </div>
               </div>
            </div>
        </div>
    </div>

    <?php
    require_once("footer.php");
    ?>

</body>
</html
