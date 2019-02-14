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
require_once 'Asignatura.php';
require_once 'Profesor_vs_Asignatura.php';

//Crear usuario
$usuario = new Usuario();
$usuario->nombre="Oscar";
$usuario->nif="12312121A";
$usuario->password="1234";
$usuario->updateOrInsert();
var_dump($usuario);
$usuario->loadById(1);

//Crear centro
$centro = new Centro();
$centro->nombre="Stucom";
$centro->updateOrInsert();
var_dump($centro);
$centro->loadById(1);
$centro->nif="12312121A";
$centro->password="1234";

//Crear profesor
$profesor = new Profesor();
$profesor->nombre="Marc";
$profesor->updateOrInsert();
var_dump($profesor);
$profesor->loadById(1);

//Crear alumno
$alumno = new Alumno();
$alumno->nombre="Esteban";
$alumno->updateOrInsert();
var_dump($alumno);
$alumno->loadById(1);

//Crear curso
$curso = new Curso();
$curso->nombre="DAW";
$curso->updateOrInsert();
var_dump($curso);
$curso->loadById(1);

//Crear asignatura
$asignatura = new Asignatura();
$asignatura->nombre="PHP";
$asignatura->updateOrInsert();
var_dump($asignatura);
$asignatura->loadById(1);

//Crear profesor vs asignatura
$profesor_vs_asignatura= new Profesor_vs_Asignatura();
$profesor_vs_asignatura->nombre="Juan Pablo PHP";
$profesor_vs_asignatura->updateOrInsert();
var_dump($profesor_vs_asignatura);
$profesor_vs_asignatura->loadById(1)

?>
</body>
</html>