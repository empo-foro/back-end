<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 17/1/19
 * Time: 12:35
 */
require_once 'Tabla.php';
require_once 'Curso.php';

class Asignatura extends Tabla
{
    private $id_asignatura;
    private $nombre;
    private $curso;
    private $num_fields = 3;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("asignatura", "id_asignatura", $fields);
    }

    /* Setters y Getters */

    public function getId_Asignatura()
    {
        return $this->id_asignatura;
    }

    public function setId_Asignatura($id_asignatura): void
    {
        $this->id_asignatura = $id_asignatura;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getCurso()
    {
        return $this->curso;
    }

    public function setCurso($curso): void
    {
        $this->curso = $curso;
    }

    public function setId_Curso($id): void
    {
        $curso = new Curso();
        $curso->loadById($id);
        $this->curso = $curso;
    }

    /**
     * Esta función nos devolvera el valor de una propiedad pedida si existe el Getter de esa propiedad
     * @param nombre $name Nombre de la propiedad que queremos recoger
     * @return mixed Nos devuelve el metódo Getter de la propiedad pedida
     * @throws Exception Lanza una excepción si no encuentra el Getter de la propiedad pedida
     */
    function __get($name)
    {
        $metodo = "get$name";
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        } else {
            throw new Exception("Propiedad desconocida");
        }
    }

    /**
     * Esta función cambiara el valor de una propiedad pedida si existe el Setter de esa propiedad
     * @param propiedad $name Nombre de la propiedad que queremos cambiar
     * @param nuevo $value Nuevo valor para la propiedad a cambiar
     * @return Carga la función si existe dentro de la clase
     * @throws Exception Lanza una expeción si no encuentra el Setter de la propiedad pedida
     */
    function __set($name, $value)
    {
        $metodo = "set$name";
        if (method_exists($this, $metodo)) {
            return $this->$metodo($value);
        } else {
            throw new Exception("Propiedad desconocida");
        }
    }

    /**
     * Función que nos devuelve un registro de la base de datos si coincide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    function loadById($id)
    {

        $asignatura = $this->getById($id);

        if(!empty($asignatura)) {

            $this->id_asignatura = $id;
            $this->nombre = $asignatura['nombre'];

            $curso = new Curso();
            $curso->loadById($asignatura['id_curso']);
            $this->curso = $curso;

        } else {
            throw new Exception("No existe ese registro");
        }
    }

    /**
     * Función que nos devuelve un array associativo, con los datos del objeto de la clase
     * @return array
     */
    private function valores()
    {
        $valores = array_map(function ($v) { //If v es un objeto tenemos que sacar el valor id de ese objeto
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

    /**
     * Función que modifica o inserta un registro
     */
    function updateOrInsert()
    {

        $asignatura = $this->valores();
        unset($asignatura['id_asignatura']);

        $this->curso->updateOrInsert();
        $asignatura['id_curso'] = $this->curso->id_curso;
        unset($asignatura['curso']);

        if(empty($this->id_asignatura)) {

            $this->insert($asignatura);
            $this->id_asignatura = self::$conn->lastInsertId();

        } else {

            $this->update($this->id_asignatura, $asignatura);

        }

    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    function delete()
    {

        if(!empty($this->id_asignatura)) {
            $this->deleteById($this->id_asignatura);
            $this->id_asignatura = null;
            $this->nombre = null;
            $this->curso = null;
        } else {
            throw new Exception("No existe este registro");
        }

    }

    /***
     * Función que te devuelve las asignaturas de un usuario.
     * @return mixed
     */
    function getUserAsignaturaByToken($id_token, $tipo){
        if($tipo=="Alumno"){
            $resultado = self::$conn->query("select asignatura.* from asignatura 
            inner join curso on curso.id_curso=asignatura.id_curso 
            inner join alumno on curso.id_curso=alumno.id_curso 
            inner join usuario on alumno.id_usuario=usuario.id_usuario 
            where usuario.id_token = '". $id_token . "'");
        } elseif ($tipo=="Profesor"){
            $resultado = self::$conn->query("SELECT asignatura.* FROM asignatura 
            INNER JOIN profesor_vs_asignatura on asignatura.id_asignatura=profesor_vs_asignatura.id_asignatura 
            INNER JOIN profesor on profesor.id_profesor=profesor_vs_asignatura.id_profesor 
            INNER JOIN usuario on usuario.id_usuario=profesor.id_usuario 
            WHERE usuario.id_token = '" . $id_token . "'");
        } else {
            throw new Exception("Tipo de Usuario incorrecto");
        }
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize() {
        return $this->valores();
    }
}
