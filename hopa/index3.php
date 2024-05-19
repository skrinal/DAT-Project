<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Databaza</title>
</head>

<body>
    <?php

    /* Vytvorenie konekcie na databázu - > $conn */

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'sakila_jakub';

    $conn = mysqli_connect($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /* Vytvorenie konekcie na databázu - > $conn */

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    $err = 0;

    if (isset($_POST['addActorBtn'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];

        if ($first_name == "" || $last_name == "") {
            $err = 1;
        } else {
            $sql = "INSERT INTO actor (first_name, last_name) VALUES ('$first_name', '$last_name')";
            // mysqli_query($conn, $sql);
            $isInserted = $conn->query($sql);       // spustenie príkazu $sql v databáze
            if ($isInserted) {
                header("Location: index3.php");      // refresh stránky, aby sa vymazali dáta z POST formulára
                exit();                             // ukončenie všetkého ďalšieho po refreshi
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
    ?>

    <form method="post">

        <label for="first_name">Meno:</label>
        <input type="text" id="first_name" name="first_name"><br><br>
        <label for="last_name">Priezvisko:</label>
        <input type="text" id="last_name" name="last_name"><br><br>

        <?php
        if ($err == 1) {
        ?>
            <p style='color:red'> Niečo ste nevyplnili </p>
        <?php
        }
        ?>

        <input type="submit" name="addActorBtn" value="Vytvoriť">


    </form>

    <br><br>

    <form method="post">
        <table style="border: 3px solid black;">
            <tr>
                <th><strong>ID</strong></th>
                <th><strong>Meno</strong></th>
                <th><strong>Priezvisko</strong></th>
                <th></th>
            </tr>

            <tr>
                <td><input type="text" name="filter_id"></td>
                <td><input type="text" name="filter_name"></td>
                <td><input type="text" name="filter_last_name"></td>
                <td><input type="submit" name="filter_btn" value="Vyhľadať"></td>
            </tr>

            <?php

            $whereString = "";

            if (isset($_POST['filter_btn'])) {
                $filter_name = $_POST['filter_name'];
                $filter_last_name = $_POST['filter_last_name'];

                if ($filter_name != "" || $filter_last_name != "") {
                    $cond_arr = [];

                    if ($filter_name != "") {
                        array_push($cond_arr, "first_name LIKE '%" . $filter_name . "%' ");
                    }
                    if ($filter_last_name != "") {
                        array_push($cond_arr, "last_name LIKE '%" . $filter_last_name . "%' ");
                    }
                    $condString = implode(" AND ", $cond_arr);
                    $whereString = "WHERE " . $condString;
                }
            }

            $sqlSelect = "SELECT actor_id, first_name, last_name FROM actor " . $whereString .  " ORDER BY actor_id DESC LIMIT 10";

            echo $sqlSelect;

            $result = $conn->query($sqlSelect);

            if ($result->num_rows > 0) {

                $rows = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($rows as $row) { ?>
                    <tr>
                        <td><?php echo $row['actor_id']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row["last_name"]; ?></td>
                        <td></td>
                    </tr>
            <?php }
            } ?>
        </table><br>

    </form>

</body>

</html>