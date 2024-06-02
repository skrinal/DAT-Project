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

    // Premenná pre sledovanie chýb
    $err = 0;

    // Po kliknutí na tlačidlo delete sa zoberie hodnota hidden inputu, teda ID nejakého 
    // herca a ten sa zmaže
    // Pozor na FK (foreign key), keďže herec, ktorý hral v nejakých filmoch, 
    // nepôjde zmazať, berte to do úvahy aj pri vašich projektoch

    if (isset($_POST['deleteBtn'])) {
        $delete_id = $_POST["deleteActorId"];
        // SQL dotaz na zmazanie herca podľa jeho ID
        $sql = "DELETE FROM actor WHERE actor_id = $delete_id;";
        $isDeleted = $conn->query($sql);
        // Ak je zmazanie úspešné, presmeruje na hlavnú stránku
        if ($isDeleted) {
            header("Location: index.php");
            exit();
        } else {
            // Vypísanie chybovej správy v prípade neúspechu
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    ?>

    <!-- Tabuľka na zobrazenie hercov -->
    <table border="1">
        <tr>
            <th>Id</th>
            <th>Meno</th>
            <th>Priezvisko</th>
            <th>Funckie</th>
        </tr>

        <?php
        // SQL dotaz na získanie posledných 10 hercov, zoradených zostupne podľa ID
        $sqlSelect = "SELECT actor_id, first_name, last_name FROM actor ORDER BY actor_id DESC LIMIT 10";
        $resultActor = $conn->query($sqlSelect);  // Spustenie SQL dotazu

        // Kontrola, či dotaz vrátil nejaké výsledky
        if ($resultActor->num_rows > 0) {
            // Vytvorenie asociatívneho poľa zo SELECTU
            $rows = $resultActor->fetch_all(MYSQLI_ASSOC);
        ?>

            <!-- Prechádzanie výsledkov a ich zobrazovanie v riadkoch tabuľky -->
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo $row['actor_id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td>
                        <!-- Odkaz na stránku pre úpravu herca -->
                        <a href="updateActor.php?ID=<?php echo $row['actor_id']; ?>">Update</a>
                        <!-- Formulár na zmazanie herca -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="deleteActorId" value="<?php echo $row['actor_id']; ?>">
                            <button type="submit" name="deleteBtn">Delete</button>
                        </form>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo "Žiadni herci";
        }
        ?>
    </table>

</body>

</html>
