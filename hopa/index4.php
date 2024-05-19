<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Databaza</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    require_once("connect.php");


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
                header("Location: index.php");      // refresh stránky, aby sa vymazali dáta z POST formulára
                exit();                             // ukončenie všetkého ďalšieho po refreshi
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    require_once("header.php");

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



    <?php

    $sqlSelect = "SELECT actor_id, first_name, last_name FROM actor ORDER BY actor_id DESC LIMIT 10";
    $resultActor = $conn->query($sqlSelect);           // spustenie príkazu $sql v databáze

    // echo "<pre>";
    // print_r($result);
    // echo "</pre>";

    if ($resultActor->num_rows > 0) {

        $rows = $resultActor->fetch_all(MYSQLI_ASSOC);       // vytvorenie asociatívneho poľa zo SELECTU

        // echo "<pre>";
        // print_r($rows);
        // echo "</pre>";
        
        // fname=John&lname=Doe 

        foreach ($rows as $row) {
            echo "Id : " . $row['actor_id'] . " Meno: " . $row["first_name"] . "  Priezvisko:" . $row["last_name"] . "<br>";
            ?>

                    <a href="index5.php?ID=<?php echo $row['actor_id'];?>">Update</a>
                    <a href="index5.php?ID=<?php echo $row['actor_id'];?>">Delete</a>
            <?php
            echo "--------------------------------------------------------------------------------------------------- <br>";
        }
    } else {
        echo "Žiadny herci";
    }


    ?>

</body>

</html>