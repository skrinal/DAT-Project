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

    // Pripojenie hlavičky stránky
    require_once("header.php");

    // Premenná na sledovanie chýb
    $err = 0;

    // Kontrola, či bol formulár odoslaný
    if (isset($_POST['addActorBtn'])) {
        // Získanie hodnôt z formulára
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];

        // Kontrola, či sú polia vyplnené
        if ($first_name == "" || $last_name == "") {
            // Nastavenie chybovej premennej, ak nie sú polia vyplnené
            $err = 1;
        } else {
            // SQL dotaz na vloženie nového herca do databázy
            $sql = "INSERT INTO actor (first_name, last_name) VALUES ('$first_name', '$last_name')";

            // Spustenie SQL dotazu
            $isInserted = $conn->query($sql);       
            
            // Kontrola, či bol záznam úspešne vložený
            if ($isInserted) {
                // Presmerovanie na hlavnú stránku, ak je vloženie úspešné
                header("Location: index.php");      
                exit();                             
            } else {
                // Vypísanie chybovej správy, ak nastane chyba
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
    ?>

    <!-- Formulár na pridanie nového herca -->
    <form method="post">
        <label for="first_name">Meno:</label>
        <input type="text" id="first_name" name="first_name"><br><br>
        <label for="last_name">Priezvisko:</label>
        <input type="text" id="last_name" name="last_name"><br><br>

        <?php
        // Zobrazenie chybovej správy, ak nie sú vyplnené povinné polia
        if ($err == 1) {
        ?>
            <p style='color:red'> Niečo ste nevyplnili </p>
        <?php
        }
        ?>
        <input type="submit" name="addActorBtn" value="Vytvoriť">
    </form>

</body>

</html>
