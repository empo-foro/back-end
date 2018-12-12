<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
<?php
require_once 'Centro.php';
require_once 'Usuario.php';

$id = 1;
$centro = new Centro();
$centro->loadById($id);
echo $centro->getNif();


$usuario = new Usuario();
$usuario->nombre = "Antonio";
$usuario->nif = "49900412M";
$usuario->password = "stucom";
$usuario->tipo = "Alumno";
$usuario->centro = $centro;
$usuario->updateOrInsert();
?>
</body>
</html>