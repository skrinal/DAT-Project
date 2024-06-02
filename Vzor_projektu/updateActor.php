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

    // Premenná pre sledovanie chýb
    $err = 0;

    // Získanie ID herca z GET parametra
    $id = $_GET['ID'];

    // Po kliknutí na tlačidlo edit sa vyberú hodnoty z inputov
    if (isset($_POST['editActorBtn'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];

        // Kontrola, či sú polia vyplnené
        if ($first_name == "" || $last_name == "") {
            $err = 1;
        } else {
            // Ak sú polia vyplnené, aktualizuje sa záznam herca v databáze
            $sql = "UPDATE actor SET first_name='$first_name', last_name='$last_name' WHERE actor_id = $id";
            $isUpdated = $conn->query($sql);  // Spustenie SQL dotazu
            if ($isUpdated) {
                // Presmerovanie na hlavnú stránku po úspešnom update
                header("Location: index.php");
                exit();  // Ukončenie skriptu po presmerovaní
            } else {
                // Vypísanie chybovej správy v prípade neúspechu
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

    // SQL dotaz na získanie údajov o hercovi podľa ID
    $sqlSelect = "SELECT * FROM actor WHERE actor_id = $id";
    $resultActor = $conn->query($sqlSelect);

    // Načítanie údajov herca z databázy
    if ($resultActor->num_rows > 0) {
        $rows = $resultActor->fetch_all(MYSQLI_ASSOC);
        $first_name = $rows[0]['first_name'];
        $last_name = $rows[0]['last_name'];
    }

    // Vloženie hlavičky stránky
    require_once("header.php");
    ?>

    <!-- Formulár na úpravu herca -->
    <form method="post">
        <label for="first_name">Meno:</label>
        <!-- Tu vypisujem čo som dostal v SELECTE -->
        <input type="text" id="first_name" name="first_name" value="<?php echo $first_name ?>"><br><br>
        <label for="last_name">Priezvisko:</label>
        <!-- Tu vypisujem čo som dostal v SELECTE -->
        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name ?>"> <br><br>

        <?php
        // Zobrazenie chybovej správy, ak nie sú vyplnené povinné polia
        if ($err == 1) {
        ?>
            <p style='color:red'> Niečo ste nevyplnili </p>
        <?php
        }
        ?>

        <input type="submit" name="editActorBtn" value="Update">
    </form>

</body>

</html>
