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
    <title>Update/Delete Payment</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="style.css?a=<?php echo time();?>">
</head>
<body>

    <?php
    require_once("header.php");
    ?>
    
<div class="main-content">
    <div class="edit-container" >
    <?php

        if (isset($_POST['DeleteBtn']))
        {
            $sql = "DELETE FROM payment_details WHERE payment_id = $payment_ID";
            $isDeletedDetails = $conn->query($sql);
            
            if ($isDeletedDetails) 
            {   
                $sql = "DELETE FROM payment WHERE payment_id = $payment_ID";
                $isDeleted = $conn->query($sql);

                if ($isDeleted) {
                    $_SESSION["message"] = "Payment with ID $payment_ID has been deleted";
                    sleep(2);
                    header("Location: list_payment.php");      
                    exit();
                }
                else {
                    echo "Error while deleting payment: " . $sql . "<br>" . mysqli_errorCreateor($conn);
                }
                                             
            } 
            else 
            {
                echo "Error while deleting payment_details: " . $sql . "<br>" . mysqli_errorCreateor($conn);
            }
        }
        else {
        ?>
            <div class="edit-legend">
                Editing payment with ID <?php echo $payment_ID ?>
            </div>
        <?php
        }  
        
        
        if (isset($_POST['EditBtn']))
        {
            $staff_id = $_POST['staff_id'];
            $customer_id = $_POST['customer_id'];
            $amount = $_POST['amount'];
            $payment_date = $_POST['payment_date'];
            $store_address = $_POST['store_address'];

            $fieldsToUpdate = [];

            if (!empty($staff_id)) {
                $fieldsToUpdate[] = "staff_id = '$staff_id'";
            }
            if (!empty($customer_id)) {
                $fieldsToUpdate[] = "customer_id = '$customer_id'";
            }
            if (!empty($amount)) {
                $fieldsToUpdate[] = "amount = '$amount'";
            }
            if (!empty($payment_date)) {
                $fieldsToUpdate[] = "payment_date = '$payment_date'";
            }
            if (!empty($store_address)) {
                $fieldsToUpdate[] = "store_address = '$store_address'";
            }

            $sql = "UPDATE payment SET " . implode(', ', $fieldsToUpdate) . " WHERE payment_id = '$payment_ID'";
            $isUpdated = $conn->query($sql);

            if ($isUpdated) 
            {
                header("Location: list_payment.php");      
                exit();                             
            } 
            else 
            {
                echo "Error while deleting payment: " . $sql . "<br>" . mysqli_errorCreateor($conn);
            }
        } 

      
   

        $sql = "SELECT * FROM payment WHERE payment_id = $payment_ID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $payment = $result->fetch_all(MYSQLI_ASSOC);

        ?>
    <div class="edit-div">
        <form method="post">
            <table>
                <tr>
                    <th></th>   
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
                    <td></td>
                    <td> <?php echo $row["staff_id"]  ?> </td>
                    <td> <?php echo $row["customer_id"]   ?> </td>
                    <td> <?php echo $row["amount"]   ?> </td>
                    <td> <?php echo $row["payment_date"]?> </td>
                    <td> <?php echo $row["store_address"]     ?> </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input class="edit-input" type="number" id="staff_id" name="staff_id" ></td>
                    <td><input class="edit-input" type="number" id="customer_id" name="customer_id" ></td>
                    <td><input class="edit-input" type="number" id="amount" name="amount" ></td>
                    <td><input class="edit-input" type="date" id="payment_date" name="payment_date" ></td>
                    <td><input class="edit-input" type="text" id="store_address" name="store_address"></td>
                </tr>
            <?php                   
                }
            ?>
            </table>
    <?php
        }
    ?>
        </div>
        
        <div class="edit-buttons">
                <input type="submit" name="DeleteBtn" value="Delete">
                <input type="submit" name="EditBtn" value="Edit">
            </form>
        </div>

    </div>
</div>

    <?php
    require_once("footer.php");
    ?>

</body>
</html>
