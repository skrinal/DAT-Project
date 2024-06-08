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
    <title>Customer List</title>
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
            
            <div class="customer-list">
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

                            header("Location: edit_Customer.php");
                            exit();
                        } else {
                            $errorEdit = 1;
                        }
                    }
                ?>

                <div class="sql-table">
                    <?php
                    $sqlSelect = "SELECT * FROM customer ORDER BY customer_id DESC LIMIT 10";
                    $resultCustomer = $conn->query($sqlSelect);

                    if ($resultCustomer->num_rows > 0) {
                        $rows = $resultCustomer->fetch_all(MYSQLI_ASSOC);
                    ?>
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
                        foreach ($rows as $row) {
                    ?>
                            <tr>
                                <td>
                                    <input type="radio" id="<?php echo $row["customer_id"]; ?>" name="edit_id" value="<?php echo $row["customer_id"]; ?>">
                                    <label for="<?php echo $row["customer_id"]; ?>">
                                </td>
                                <td><?php echo $row["customer_id"]   ?></td>
                                <td><?php echo $row["first_name"]    ?></td>
                                <td><?php echo $row["last_name"]     ?></td>
                                <td><?php echo $row["birthDate"]     ?></td>
                                <td><?php echo $row["last_payment"]  ?></td>
                                <td><?php echo $row["allergy"]       ?></td>
                                    </label>
                            </tr>
                        <?php
                        }
                        if ($errorEdit == 1) {
                        ?>
                            <p style='color:red'> Choose customer to edit. </p>
                        <?php
                        }
                    ?>
                        </table>
                    <?php
                    } 
                    else {
                    ?>
                        <p style='color:red'> None Customers. </p>
                    <?php
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
