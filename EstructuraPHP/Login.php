<html>
<head>
    <meta http-equiv="Content-Type" content="ISO-8859-1">
    <title>Document</title>
</head>
<body>
<form method="post">
    NIF: <input type="text" maxlength="9" minlength="8" name="nif"/> <br/>
    Password: <input type="password" name="password"> <br/>
    <input type="submit">
</form>

<?php
require_once 'Tabla.php';
$usuarios_tabla = new BaseDatos("centro", "id_centro", "*");

try {

    $nif = filter_input(INPUT_POST, "nif", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    if (!empty($nif) && !empty($password)) {
        if (!empty($usuarios = $usuarios_tabla->getAll(["nif" => $nif, "password" => $password]))) {
            session_start();
            foreach ($usuarios as $usuario) {
                $_SESSION["id"] = $usuario["id_centro"];
            }
            header("Location: entrada.php");
        } else {
            throw new Exception("NIF o contraseña incorrecto");
        }
    }
} catch (Exception $ex) {

    echo $ex->getMessage();

}
?>
</body>
</html>