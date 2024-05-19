<?php 

    /* Vytvorenie konekcie na databázu - > $conn */

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'supermarket';

    $conn = mysqli_connect($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /* Vytvorenie konekcie na databázu - > $conn */

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>"; 
