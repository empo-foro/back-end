<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
<?php
require_once 'Centro.php';
require_once 'Usuario.php';
require_once 'Profesor.php';

$id = 1;
$centro = new Centro();

$centro->loadById($id);
echo $centro->getNif();

$usuario = new Usuario();
$usuario->loadById($id);
echo $usuario->getNif();

$profesor = new Profesor();
$profesor->usuario = $usuario;
$profesor->updateOrInsert();
?>
</body>
</html>