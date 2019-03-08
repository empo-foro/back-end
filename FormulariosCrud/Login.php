<html>
<head>
    <meta http-equiv="Content-Type" content="ISO-8859-1">
    <title>Document</title>
</head>
<body>
<form method="post">
    Email: <input type="email" pattern="[^ @]*@[^ @]*" name="email"/> <br/>
    Password: <input type="password" name="password"> <br/>
    Centro <input type="radio" name="tipo" value="centro">
    Usuario <input type="radio" name="tipo" vale="usuario">
    <input type="submit">
</form>

<?php
require_once '../EstructuraPHP/Centro.php';
require_once '../EstructuraPHP/Usuario.php';

$usuarios_tabla = new Centro ("centro", "id_centro", "*");
$usuarios_tabla = new Usuario ("usuario" , "id_usuario", "*");

try {

    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    if(tipo==centro){
        if (!empty($email) && !empty($password)) {
            if (!empty($usuarios = $usuarios_tabla->getAll(["email" => $email, "password" => $password]))) {
                session_start();
                foreach ($usuarios as $usuario) {
                    $_SESSION["id"] = $usuario["id_centro"];

                    var_dump($centro);
                }

            } else {
                throw new Exception("Email o contraseña incorrecto");
            }
        }
    }else{
        if (!empty($email) && !empty($password)) {
            if (!empty($usuarios = $usuarios_tabla->getAll(["email" => $email, "password" => $password]))) {
                session_start();
                foreach ($usuarios as $usuario) {
                    $_SESSION["id"] = $usuario["id_usuario"];

                    var_dump($usuario);
                }
            } else {
                throw new Exception("Email o contraseña incorrecto");
            }
        }
    }

} catch (Exception $ex) {

    echo $ex->getMessage();

}
?>
</body>
</html>