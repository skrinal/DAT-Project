<?php
session_start();

if (!isset($_SESSION["permission"]) || $_SESSION["permission"] !== "yes") {
    header("Location: list_payment.php");
    exit();
}

if (!isset($_SESSION["ID"])) {
    header("Location: list_payment.php");
    exit();
}

$payment_ID = $_SESSION["ID"];
require_once("connect.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="style.css?a=<?php echo time();?>">
</head>

<body>
    
<?php
    require_once("header.php");
?>

    <div class="main-content">
        <div class="container">
            <div class="payment-list">
                    <?php
                    require_once("connect.php");
                    $errorShowPayment = 0;
                    ?>

                <div class="sql-table">       
                <?php
                    $sql = "SELECT * FROM payment_details WHERE payment_id = $payment_ID";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $payment = $result->fetch_all(MYSQLI_ASSOC);
                    
                    ?>
                    <div class="edit-div">
                        <form method="post">
                            <table>
                                <tr>
                                    <th></th>
                                    <th>payment_id</th>
                                    <th>Product_id</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Discount</th>
                                </tr>

                            <?php
                                foreach ($payment as $row) 
                                {
                            ?>
                                <tr>
                                    <td></td>
                                    <td> <?php echo $row["payment_id"] ?> </td>
                                    <td> <?php echo $row["product_id"]  ?> </td>

                                    <?php

                                    $sql = "SELECT * FROM product";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $products = $result->fetch_all(MYSQLI_ASSOC);
                                    
                                        foreach ($products as $product) {
                                            if ($product['product_id'] == $row["product_id"]) {
                                            ?>
                                                <td> <?php echo $product["name"]   ?> </td>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                                    <td> <?php echo $row["quantity"]   ?> </td>
                                    <td> <?php echo $row["discount_percentage"]   ?> </td>
                                </tr>
                            <?php                   
                                }
                        ?>
                            </table>
                        <?php
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
