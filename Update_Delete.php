<?php
session_start();

if (!isset($_SESSION["permission"]) || $_SESSION["permission"] !== "yes") {
    // Redirect
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION["ID"])) {
    //No customer ID
    header("Location: index.php");
    exit();
}

$customer_id = $_SESSION["ID"];
require_once("connect.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update/Delete Customer</title>
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
            $sql = "DELETE FROM customer WHERE customer_id = $customer_id";
            $isDeleted = $conn->query($sql);

            if ($isDeleted) 
            {   
                $_SESSION["message"] = "User with ID $customer_id has been deleted";
                sleep(2);
                header("Location: index.php");      
                exit();                             
            } 
            else 
            {
                echo "Error while deleting user: " . $sql . "<br>" . mysqli_errorCreateor($conn);
            }
        }
        else {
        ?>
            <div class="edit-legend">
                Editing user with ID <?php echo $customer_id ?>
            </div>
        <?php
        }  
        
        
        if (isset($_POST['EditBtn']))
        {
            $sql = "DELETE FROM customer WHERE customer_id = $customer_id";
            $isDeleted = $conn->query($sql);

            if ($isDeleted) 
            {
                header("Location: index.php");      
                exit();                             
            } 
            else 
            {
                echo "Error while deleting user: " . $sql . "<br>" . mysqli_errorCreateor($conn);
            }
        } 

      
   

        $sql = "SELECT * FROM customer WHERE customer_id = $customer_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $customer = $result->fetch_all(MYSQLI_ASSOC);

        ?>
    <div class="edit-div">
        <form method="post">
            <table>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>BirthDay</th>
                    <th>last_payment</th>
                    <th>Allergy</th>
                </tr>
        
            <?php
                foreach ($customer as $row) 
                {
            ?>
                <tr>
                    <td></td>
                    <td> <?php echo $row["customer_id"] ?> </td>
                    <td> <?php echo $row["first_name"]  ?> </td>
                    <td> <?php echo $row["last_name"]   ?> </td>
                    <td> <?php echo $row["birthDate"]   ?> </td>
                    <td> <?php echo $row["last_payment"]?> </td>
                    <td> <?php echo $row["allergy"]     ?> </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input class="edit-input" type="number" id="customer_id" name="customer_id" ></td>
                    <td><input class="edit-input" type="text" id="first_name" name="first_name" ></td>
                    <td><input class="edit-input" type="text" id="last_name" name="last_name" ></td>
                    <td><input class="edit-input" type="date" id="birthDate" name="birthDate" ></td>
                    <td><input class="edit-input" type="date" id="last_payment" name="last_payment" ></td>
                    <td><input class="edit-input" type="text" id="allergy" name="allergy"></td>
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
