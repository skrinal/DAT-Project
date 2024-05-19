<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Databaza</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php

    print_r($_GET);
    
    if (isset($_GET['ID'])) {
        ?>

            <h1>
                    
               ID na update / delete je:  <?php echo $_GET['ID']; ?>

            </h1>

        <?php
    }

    ?>


</body>

</html>