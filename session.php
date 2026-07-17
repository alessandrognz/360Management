<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/session.css" />
    <link rel="icon" type="image/png" href="img/logo-favi.png" />
    <title>Inicio</title>
</head>
<body>
    <?php 
        require 'includes/nav.php';
        require 'includes/db.php';

        echo $_SESSION['nombre'], 'ha iniciado sesión.';

    
    ?>
</body>
</html>