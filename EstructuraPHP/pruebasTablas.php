<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
<?php
require_once 'Centro.php';
$id = 1;
$centro = new Centro();
$centro->loadById($id);
echo $centro->getNif();
$centro->nombre = "Pedritos";
$centro->updateOrInsert();
echo $centro->getNombre();
?>
</body>
</html>