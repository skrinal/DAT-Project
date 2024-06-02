<?php 

    /* Vytvorenie konekcie na databázu - > $conn */
    
    // Definovanie premenných pre pripojenie k databáze
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'sakila';

    // Vytvorenie konekcie na databázu pomocou mysqli_connect
    $conn = mysqli_connect($host, $username, $password, $database);

    // Kontrola, či sa pripojenie podarilo, ak nie, vypíše sa chybová správa a skript sa ukončí
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /* Konekcia na databázu bola úspešne vytvorená - > $conn */

    // Zobrazí obsah $_POST premenných v preformátovanom výstupe
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>"; 
