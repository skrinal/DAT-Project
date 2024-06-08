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
    <link rel="stylesheet" href="style.css?a=<?php echo time();?>">
</head>

<body>
    
<?php
    require_once("header.php");
?>

    <div class="main-content">
        <div class="container">
            
            <div class="above">
                <?php
                    if (isset($message)) {
                        echo "<p style='color:red'>$message</p>";
                    }
                ?>
            </div>
        
            <div class="payment-list">
                    <?php
                    require_once("connect.php");

                    $errorCreate = 0;
                    $errorEdit = 0;
                    $errorShowPayment = 0;
                    
                    if (isset($_POST['EditBtn'])) {
                        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
                            $errorEdit = 0;
                            
                            $idEdit = $_POST['edit_id'];

                            $_SESSION["permission"] = "yes";
                            $_SESSION["ID"] = $idEdit;

                            header("Location: edit_Payment.php");
                            exit();
                        } 
                        else {
                            $errorEdit = 1;
                        }
                    }

                    if (isset($_POST['ShowPaymentBtn'])) {
                        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
                            $errorEdit = 0;
                            
                            $idEdit = $_POST['edit_id'];

                            $_SESSION["permission"] = "yes";
                            $_SESSION["ID"] = $idEdit;

                            header("Location: payment_details.php");
                            exit();
                        } 
                        else {
                            $errorShowPayment = 1;
                        }
                    }
                    ?>

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
