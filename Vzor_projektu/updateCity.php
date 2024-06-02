<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Databaza</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    // Pripojenie k databáze
    require_once("connect.php");

    // Premenné pre sledovanie chýb
    $errName = false;
    $errCountry = false;

    $err = 0;

    // Získanie ID mesta z GET parametra
    $id = $_GET['ID'];

    // Po kliknutí na tlačidlo edit sa vyberú hodnoty z inputov
    if (isset($_POST['editCityBtn'])) {
        $city = $_POST['nazov'];
        $country_id = $_POST['country_id'];

        // Kontrola, či sú polia vyplnené
        if ($city == "" || $country_id == -1) {
            $err = 1;
        } else {
            // Ak sú polia vyplnené, aktualizuje sa záznam mesta v databáze
            $sql = "UPDATE city SET city='$city', country_id='$country_id' WHERE city_id = $id";
            $isUpdated = $conn->query($sql);  // Spustenie SQL dotazu
            if ($isUpdated) {
                // Presmerovanie na hlavnú stránku po úspešnom update
                header("Location: index2.php");
                exit();  // Ukončenie skriptu po presmerovaní
            } else {
                // Vypísanie chybovej správy v prípade neúspechu
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    // SQL dotaz na získanie údajov o meste podľa ID
    $sqlSelect = "SELECT * FROM city WHERE city_id = $id";
    $resultCity = $conn->query($sqlSelect);

    // Načítanie údajov mesta z databázy
    if ($resultCity->num_rows > 0) {
        $rows = $resultCity->fetch_all(MYSQLI_ASSOC);
        $city = $rows[0]['city'];
        $country_id = $rows[0]['country_id'];
    }

    // Vloženie hlavičky stránky
    require_once("header.php");
    ?>

    <!-- Formulár na úpravu mesta -->
    <form method="post">
        <?php
        // Zobrazenie chybovej správy, ak nebolo zadané meno mesta
        if ($errName == true) {
        ?>
            <p style='color:red'> Nezadali ste názov mesta </p>
        <?php
        }
        ?>
        <label for="nazov">Názov mesta:</label>
        <!-- Tu vypisujem čo som dostal v SELECTE -->
        <input type="text" id="nazov" name="nazov" value="<?php echo $city; ?>"><br><br>

        <?php
        // Zobrazenie chybovej správy, ak nebol vybraný štát
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
            // SQL dotaz na získanie zoznamu štátov
            $sqlSelect = "SELECT country_id, country FROM country";
            $resultCountry = $conn->query($sqlSelect);  // Spustenie SQL dotazu

            // Skontrolovanie, či dotaz vrátil nejaké výsledky
            if ($resultCountry->num_rows > 0) {
                // Vytvorenie asociatívneho poľa zo SELECTU
                $countries = $resultCountry->fetch_all(MYSQLI_ASSOC);
            }

            // Prechádzanie zoznamu štátov a ich vloženie do select
            foreach ($countries as $country) {
            ?>
                <option <?php if ($country_id == $country['country_id']) echo "selected" ?> value="<?php echo $country['country_id'] ?>">
                    <?php echo $country['country'] ?>
                </option>
            <?php
            }
            ?>
        </select>

        <br><br>
        <input type="submit" name="editCityBtn" value="Update">
    </form>

</body>

</html>
