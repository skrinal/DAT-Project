<?php
/************************************* 
    READ / CREATE - WITH FK + remake 160-170
*****************************************/

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
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
            
            <div class="below">
                <div class="form-container">
                    <?php
                    require_once("connect.php");

                    $errorCreate = 0;
                    $errorEdit = 0;
                    
                    if (isset($_POST['EditBtn'])) {
                        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
                            $errorEdit = 0;
                            
                            $idEdit = $_POST['edit_id'];
                            $_SESSION["permission"] = "yes";
                            $_SESSION["ID"] = $idEdit;

                            header("Location: Update_Delete.php");
                            exit();
                        } else {
                            $errorEdit = 1;
                        }
                    }

                    if (isset($_POST['addCustomerBtn'])) {
                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $birthDate = $_POST['birthDate'];
                        $allergy = $_POST['allergy'];

                        if ($first_name == "" || $last_name == "" || $birthDate == "") {
                            $errorCreate = 1;
                        } else {
                            if ($allergy == "") {
                                $sql = "INSERT INTO customer (first_name, last_name , birthDate) VALUES ('$first_name', '$last_name', '$birthDate')";
                                $isInserted = $conn->query($sql);       
                            } else {
                                $sql = "INSERT INTO customer (first_name, last_name , birthDate, allergy) VALUES ('$first_name', '$last_name', '$birthDate' , '$allergy')";
                                $isInserted = $conn->query($sql);       
                            }

                            if ($isInserted) {
                                $sql = "SELECT customer_id FROM customer WHERE first_name = '$first_name' AND last_name = '$last_name' AND birthDate = '$birthDate'";    
                                $createdCustomerID = $conn->query($sql);
                                
                                $customerID = $createdCustomerID->fetch_assoc();
                                echo $customerID["customer_id"];
                                
                                //header("Location: index.php");      
                                //exit();                             
                            } else {
                                echo "errorCreateor: " . $sql . "<br>" . mysqli_errorCreateor($conn);
                            }
                        }
                    }
                    ?>
                    <form class="customer-form" method="POST">
                        <label for="first_name">First Name</label><br>
                        <input type="text" id="first_name" name="first_name"><br>

                        <label for="last_name">Last Name</label><br>
                        <input type="text" id="last_name" name="last_name"><br>

                        <label for="birthDate">BirthDate</label><br>
                        <input type="date" id="birthDate" name="birthDate"><br>

                        <label for="allergy">Allergy (optional)</label><br>
                        <input type="text" id="allergy" name="allergy"><br><br>

                        <?php
                        if ($errorCreate == 1) {
                        ?>
                            <p style='color:red'> Niečo ste nevyplnili </p>
                        <?php
                        }
                        ?>

                        <input type="submit" name="addCustomerBtn" value="Create">
                    </form>
                </div>

                <div class="sql-table">
                    <?php
                    $sqlSelect = "SELECT customer_id, first_name, last_name, birthDate, last_payment, allergy FROM customer ORDER BY customer_id DESC LIMIT 10";
                    $resultCustomer = $conn->query($sqlSelect);

                    if ($resultCustomer->num_rows > 0) {
                        $rows = $resultCustomer->fetch_all(MYSQLI_ASSOC);
                        ?>
                            <form method="post">
                        <?php

                        echo "<table>";
                        echo "<tr>
                                <th></th>
                                <th>Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>BirthDay</th>
                                <th>last_payment</th>
                                <th>Allergy</th>
                            </tr>";

                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>";

                        ?> 
                            <input type="radio" id="<?php echo $row["customer_id"]; ?>" name="edit_id" value="<?php echo $row["customer_id"]; ?>">
                            <label for="<?php echo $row["customer_id"]; ?>">
                        <?php

                            echo "</td>";
                            echo "<td>" . $row["customer_id"] . "</td>";
                            echo "<td>" . $row["first_name"] . "</td>";
                            echo "<td>" . $row["last_name"] . "</td>";
                            echo "<td>" . $row["birthDate"] . "</td>";
                            echo "<td>" . $row["last_payment"] . "</td>";
                            echo "<td>" . $row["allergy"] . "</td>";
                        ?>
                            </label>
                        <?php
                            echo "</tr>";                    
                        }
                        echo "</table>";
                        
                        
                        if ($errorEdit == 1) {
                        ?>
                            <p style='color:red'> Choose customer to edit. </p>
                        <?php
                        }
                        
                    } else {
                        echo "None Customers";
                    }
                    ?>
                        <input type="submit" name="EditBtn" value="Edit">
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
