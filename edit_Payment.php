<?php
session_start();

if (!isset($_SESSION["permission"]) || $_SESSION["permission"] !== "yes") {
    header("Location: payment.php");
    exit();
}

if (!isset($_SESSION["ID"])) {
    header("Location: payment.php");
    exit();
}

$payment_id = $_SESSION["ID"];
require_once("connect.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update/Delete Payment</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
            $sql = "DELETE FROM payment_details WHERE payment_id = $payment_id";
            $isDeletedDetails = $conn->query($sql);
            
            if ($isDeletedDetails) 
            {   
                $sql = "DELETE FROM payment WHERE payment_id = $payment_id";
                $isDeleted = $conn->query($sql);

                if ($isDeleted) {
                    $_SESSION["message"] = "Payment with ID $payment_id has been deleted";
                    sleep(2);
                    header("Location: payment.php");      
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
                Editing payment with ID <?php echo $payment_id ?>
            </div>
        <?php
        }  
        
        
        if (isset($_POST['EditBtn']))
        {
            $sql = "DELETE FROM payment WHERE payment_id = $payment_id";
            $isDeleted = $conn->query($sql);

            if ($isDeleted) 
            {
                header("Location: payment.php");      
                exit();                             
            } 
            else 
            {
                echo "Error while deleting payment: " . $sql . "<br>" . mysqli_errorCreateor($conn);
            }
        } 

      
   

        $sql = "SELECT * FROM payment WHERE payment_id = $payment_id";
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
                    <td></td>
                    <td> <?php echo $row["payment_id"] ?> </td>
                    <td> <?php echo $row["staff_id"]  ?> </td>
                    <td> <?php echo $row["customer_id"]   ?> </td>
                    <td> <?php echo $row["amount"]   ?> </td>
                    <td> <?php echo $row["payment_date"]?> </td>
                    <td> <?php echo $row["store_address"]     ?> </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input class="edit-input" type="number" id="payment_id" name="payment_id" ></td>
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
