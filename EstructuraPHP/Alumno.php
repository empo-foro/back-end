<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 13/12/18
 * Time: 12:25
 */

require_once 'Tabla.php';
require_once 'Usuario.php';
require_once 'Curso.php';

class Alumno extends Tabla
{
    private $id_alumno;
    private $usuario;
    private $curso;
    private $num_fields = 3;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("alumno", "id_alumno", $fields);
    }

    /* Setters y Getters */

    public function getId_Alumno()
    {
        return $this->id_alumno;
    }

    public function setId_Alumno($id_alumno): void
    {
        $this->id_alumno = $id_alumno;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getCurso()
    {
        return $this->curso;
    }

    public function setCurso($curso): void
    {
        $this->curso = $curso;
    }

    public function setId_Usuario($id): void
    {
        $alumno = new Usuario();
        $alumno->loadById($id);
        $this->usuario = $alumno;
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
    public function loadById($id)
    {

        $alumno = $this->getById($id);

        if (!empty($alumno)) {

            $this->id_alumno = $id;

            $usuario = new Usuario();
            $usuario->loadById($alumno['id_usuario']);
            $this->usuario = $usuario;

            $curso = new Curso();
            $curso->loadById($alumno['id_curso']);
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
    public function updateOrInsert()
    {

        $alumno = $this->valores();

        unset($alumno['id_alumno']);

        $this->usuario->updateOrInsert();
        $alumno["id_usuario"] = $this->usuario->id_usuario;

        unset($alumno["usuario"]);

        $this->curso->updateOrInsert();
        $alumno['id_curso'] = $this->curso->id_curso;

        unset($alumno['curso']);

        if (empty($this->id_alumno)) {

            $this->insert($alumno);
            $this->id_alumno = self::$conn->lastInsertId();
            return $alumno;

        } else {

            $this->update($this->id_alumno, $alumno);
            return $alumno;

        }
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    public function delete()
    {
        if (!empty($this->id_alumno)) {
            $this->deleteById($this->id_alumno);
            $this->id_alumno = null;
            $this->usuario = null;
            $this->curso = null;
        } else {
            throw new Exception("No existe este registro");
        }
    }

    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize() {
        return $this->valores();
    }


}