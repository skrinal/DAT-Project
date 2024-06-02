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
    
    // Vloženie hlavičky stránky
    require_once("header.php");

    // Premenné pre sledovanie chýb
    $errName = false;
    $errCountry = false;

    // Kontrola, či bol formulár odoslaný
    if (isset($_POST['addCityBtn'])) {
        // Získanie hodnôt z formulára
        $nazov = $_POST['nazov'];
        $country_id = $_POST['country_id'];

        // Kontrola, či bolo zadané meno mesta
        if ($nazov == "") {
            $errName = true;
        } 
        // Kontrola, či bol vybraný štát
        else if ($country_id == "-1") {
            $errCountry = true;
        } 
        // Ak sú všetky údaje zadané, vykoná sa SQL dotaz
        else {
            $sql = "INSERT INTO city (city, country_id) VALUES ('$nazov', '$country_id')";
            $isInserted = $conn->query($sql);  // Spustenie SQL dotazu
            if ($isInserted) {
                header("Location: index2.php");  // Presmerovanie na hlavnú stránku po úspešnom vložení
                exit();  // Ukončenie skriptu po presmerovaní
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);  // Vypísanie chybovej správy
            }
        }
    }
    ?>

    <!-- Formulár na pridanie nového mesta -->
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
        <input type="text" id="nazov" name="nazov"><br><br>

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
