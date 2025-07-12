<?php
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mi Proyecto PHP</title>
</head>
<body>
    <h1>¡Hola! Bienvenido a mi proyecto PHP. <?php phpinfo(); ?></h1>
</body>
</html>

