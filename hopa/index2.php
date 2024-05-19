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

    $errName = false;
    $errCountry = false;

    if (isset($_POST['addCityBtn'])) {
        $nazov = $_POST['nazov'];
        $country_id = $_POST['country_id'];

        if ($nazov == "") {
            $errName = true;
        } else if ($country_id == "-1") {
            $errCountry = true;
        } else {
            $sql = "INSERT INTO city (city, country_id) VALUES ('$nazov', '$country_id')";
            // mysqli_query($conn, $sql)
            $isInserted = $conn->query($sql);        // spustenie príkazu $sql v databáze
            if ($isInserted) {
                header("Location: index2.php");   // refresh stránky, aby sa vymazali dáta z POST formulára
                exit();                         // ukončenie všetkého ďalšieho po refreshi
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    require_once("header.php");
    ?>

    <form method="post">
        <?php
        if ($errName == true) {
        ?>
            <p style='color:red'> Nezadali ste názov mesta </p>
        <?php
        }
        ?>
        <label for="nazov">Názov mesta:</label>
        <input type="text" id="nazov" name="nazov"><br><br>


        <?php
        if ($errCountry == true) {
        ?>
            <p style='color:red'> Nevybrali ste štát </p>
        <?php
        }
        ?>
        <label for="country">Štát:</label>
        <select name="country_id" id="country">
            <option value="-1"></option>
            <?php

            $sqlSelect = "SELECT country_id, country FROM country";
            $resultCountry = $conn->query($sqlSelect);           // spustenie príkazu $sql v databáze

            // echo "<pre>";
            // print_r($result);
            // echo "</pre>";

            if ($resultCountry->num_rows > 0) {

                $countries = $resultCountry->fetch_all(MYSQLI_ASSOC);       // vytvorenie asociatívneho poľa zo SELECTU

                // echo "<pre>";
                // print_r($countries);
                // echo "</pre>";
            }



            foreach ($countries as $country) {
            ?>
                <option value="<?php echo $country['country_id'] ?>">
                    <?php echo $country['country'] ?>
                </option>
            <?php
            }

            ?>
        </select>

        <br><br>
        <input type="submit" name="addCityBtn" value="Vytvoriť">


    </form>


</body>

</html>