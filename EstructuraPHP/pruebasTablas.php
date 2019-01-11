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
require_once 'Alumno.php';
require_once 'Curso.php';


$centro = new Centro();

$centro->loadById(1);
/*
$profesor = new Profesor();
$profesor->loadById(2);
//Te necesito Juan Pablo, necesito borrar el profesor pero este no tiene el mismo id que el usuario
$profesor->delete();
*/


/*
$u=new Usuario();
$u->loadById(7);

$alu = new Alumno();
$alu->usuario = $u;
$alu->updateOrInsert();
*/

/*
$profesor = new Profesor();
$profesor->usuario = $usuario;
$profesor->updateOrInsert();
var_dump($profesor);
*/




?>
</body>
</html>