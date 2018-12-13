<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 13/12/18
 * Time: 12:25
 */

require_once 'Tabla.php';
class Alumno extends Tabla
{
    private $id_alumno;
    private $id_usuario;
    private $id_curso;
    private $num_fields=3;

    public function __construct(){
        $fields=array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("alumno", "id_alumno", $fields);
    }

    //SETTERS Y GETTERS
    /**
     * @return mixed
     */
    public function getIdAlumno()
    {
        return $this->id_alumno;
    }

    /**
     * @param mixed $id_alumno
     */
    public function setIdAlumno($id_alumno): void
    {
        $this->id_alumno = $id_alumno;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setIdUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getIdCurso()
    {
        return $this->curso;
    }

    /**
     * @param mixed $curso
     */
    public function setIdCurso($curso): void
    {
        $this->curso = $curso;
    }

    /**
     *Getter
     * @param nombre $name nombre del campo
     * @return mixed valor del campo
     * @throws Exception
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
     * Setter
     * @param propiedad $name nombre del campo
     * @param nuevo $value nuevo valor para el campo
     * @throws Exception
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
     * Función que nos devuelve un registro de la base de datos si conicide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    public function loadById($id)
{
    $alumno=$this->getById($id);
    if(!empty($alumno)){
        $this->id_alumno=$id;
        $this->usuario=$id;
        $this->curso=$id;
    }else{
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
     * Función que modifica o inserta un registro, dependiendo de la variable idcentro de la clase
     */
    public function updateOrInsert()
    {
        $alumno=$this->valores();
        unset($alumno['id_alumno']);
        $this->usuario->updateOrInsert();
        $alumno["id_usuario"]=$this->usuario->id_usuario;
        unset($alumno["usuario"]);
        if(empty($this->id_alumno)){
            $this->insert($alumno);
            $this->id_alumno=self::$conn->lastInsertId();
        }else{
            $this->update($this->id_alumno, $alumno);
        }
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    public function delete()
    {
        if(!empty($this->id_alumno)){
            $this->deleteById($this->id_alumno);
            $this->id_alumno=null;
            $this->usuario=null;
        }else{
            throw new Exception("No existe ese registro para borrar");
        }
    }
}