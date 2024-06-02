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

    // Po kliknutí na tlačidlo delete sa zoberie hodnota hidden inputu, teda ID nejakého mesta a to sa zmaže
    if (isset($_POST['deleteBtn'])) {
        $delete_id = $_POST["deleteCityId"];
        // SQL dotaz na zmazanie mesta podľa jeho ID
        $sql = "DELETE FROM city WHERE city_id = $delete_id;";
        $isDeleted = $conn->query($sql);
        // Ak je zmazanie úspešné, presmeruje na stránku s mestami
        if ($isDeleted) {
            header("Location: index2.php");
            // dávam link na index2.php, lebo to je zobrazenie miest, nech to ide na to isté, aby to lepšie vyzeralo
            exit();
        } else {
            // Vypísanie chybovej správy v prípade neúspechu
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    ?>

    <!-- Tabuľka na zobrazenie miest -->
    <table border="1">
        <tr>
            <th>Id</th>
            <th>Mesto</th>
            <th>Krajina</th>
            <th>Funckie</th>
        </tr>

        <?php
        // SQL dotaz na získanie posledných 10 miest a ich priradených krajín, zoradených 
        // zostupne podľa ID
        $sqlSelect = "SELECT * FROM city JOIN country ON city.country_id = country.country_id ORDER BY city_id DESC LIMIT 10";
        $resultCities = $conn->query($sqlSelect);  // Spustenie SQL dotazu

        // Kontrola, či dotaz vrátil nejaké výsledky
        if ($resultCities->num_rows > 0) {
            // Vytvorenie asociatívneho poľa zo SELECTU
            $rows = $resultCities->fetch_all(MYSQLI_ASSOC);
        ?>
            <!-- Prechádzanie výsledkov a ich zobrazovanie v riadkoch tabuľky -->
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo $row['city_id']; ?></td>
                    <td><?php echo $row['city']; ?></td>
                    <td><?php echo $row['country']; ?></td>
                    <td>
                        <!-- Odkaz na stránku pre úpravu mesta -->
                        <a href="updateCity.php?ID=<?php echo $row['city_id']; ?>">Update</a>
                        <!-- Formulár na zmazanie mesta -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="deleteCityId" value="<?php echo $row['city_id']; ?>">
                            <button type="submit" name="deleteBtn">Delete</button>
                        </form>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo "Žiadne mestá";
        }
        ?>
    </table>
</body>

</html>
