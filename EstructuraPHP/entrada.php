<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>

<?php
require_once 'Tabla.php';
session_start();
$cursos_tabla = new BaseDatos("curso", "id_curso", "*");
$usuarios_tabla = new BaseDatos("usuario", "id_usuario", "*");
$alumnos_tabla = new BaseDatos("alumno", "id_alumno", "*");
?>
Crear alumnos
<form method="post" enctype="multipart/form-data">
    Cruso de los alumnos: <select name="curso-alumnos">
        <?php
        $cursos = $cursos_tabla->getAll();
        foreach ($cursos as $curso) {
            ?>
            <option value="<?= $curso["id_curso"] ?>">
                <?= $curso["nombre"] ?>
            </option>
            <?php
        }
        ?>
    </select> <br/>
    Subir archivo: <input type="file" name="fichero-alumnos" accept=".csv">
    <input type="submit">
</form>

<?php
try {

    $curso_alumnos = filter_input(INPUT_POST, "curso-alumnos", FILTER_SANITIZE_STRING);
    $fichero_alumnos = filter_input(INPUT_POST, "fichero-alumnos", FILTER_SANITIZE_STRING);

    if (isset($curso_alumnos) && $_FILES["fichero-alumnos"]["size"] != 0) { //Tengo que comprobar que el fichero sea tipo csv o xlsx

        if (is_uploaded_file($_FILES["fichero-alumnos"]["tmp_name"])) {

            $array = file($_FILES["fichero-alumnos"]["tmp_name"]);
            foreach ($array as $datos) {

                $dato = explode(";", $datos);
                $nif_alumno = $dato[0];
                $nombre_alumno = $dato[1];
                $password_alumno = $dato[2];
                $lastID = $usuarios_tabla->insert(["nif" => $nif_alumno, "nombre" => $nombre_alumno, "password" => $password_alumno, "id_centro" => $_SESSION["id"]]);
                $alumnos_tabla->insert(["id_usuario" => $lastID, "id_curso" => $curso_alumnos]);
            }
            ?>
            <div>
                <strong>Alumnos creados!</strong>
            </div>
            <?php
        }
    }
} catch (Exception $ex) {

    return $ex;
}
?>
<br/>
Crear curso:
<form>
    Nombre del curso: <input type="text" name="nombre-curso"/>
    <input type="submit">
</form>

<?php
try {

    $nombre_curso = filter_input(INPUT_GET, "nombre-curso", FILTER_SANITIZE_STRING);
    $id_centro = $_SESSION["id"];

    if (!empty($nombre_curso) && !empty($id_centro)) {

        print_r($cursos_tabla->insert(["nombre" => $nombre_curso, "id_centro" => $id_centro]));
        ?>

        <div>
            <strong>Curso creado correctamente!</strong>
        </div>

        <?php
    }
} catch (Exception $ex) {

    return $ex->getMessage();
}
?>

Crear módulos para un curso:
<form method="post" enctype="multipart/form-data">
    Crusos: <select name="curso-alumnos">
        <?php
        $cursos = $cursos_tabla->getAll();
        foreach ($cursos as $curso) {
            ?>
            <option value="<?= $curso["id_curso"] ?>">
                <?= $curso["nombre"] ?>
            </option>
            <?php
        }
        ?>
    </select> <br/>
    Subir archivo: <input type="file" name="fichero-cursos" accept=".csv">
    <input type="submit">
</form>

</body>
</html>