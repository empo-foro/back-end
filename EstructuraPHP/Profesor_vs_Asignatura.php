<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 18/1/19
 * Time: 11:57
 */

require_once 'Tabla.php';

class Profesor_vs_Asignatura extends Tabla
{
    private $id;
    private $id_profesor;
    private $id_asignatura;
    private $num_fields=3;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("profesor_vs_asignatura", "id", $fields);
    }

    /* Setters y Getters */

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId_Profesor()
    {
        return $this->id_profesor;
    }

    public function setId_Profesor($id_profesor): void
    {
        $this->id_profesor = $id_profesor;
    }

    public function getId_Asignatura()
    {
        return $this->id_asignatura;
    }

    public function setId_Asignatura($id_asignatura): void
    {
        $this->id_asignatura = $id_asignatura;
    }

    public function getNum_Fields()
    {
        return $this->num_fields;
    }

    public function setNum_Fields($num_fields): void
    {
        $this->num_fields = $num_fields;
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

    function loadById($id)
    {
        $Profesor_vs_Asignatura=$this->getById($id);

        if (!empty($Profesor_vs_Asignatura)){

            $this->id = $id;

            $profesor = new Profesor();
            $profesor->loadById($Profesor_vs_Asignatura['id_profesor']);
            $this->profesor = $profesor;

            $asignatura = new Asignatura();
            $asignatura->loadById($Profesor_vs_Asignatura['id_asignatura']);
            $this->asignatura = $asignatura;

            }else{
            throw new Exception("No existe ese registro");
        }
    }

    private function valores()
    {
        $valores = array_map(function ($v){
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

    function updateOrInsert()
    {
        $profesor_vs_asignatura=$this->valores();

        unset($profesor_vs_asignatura['id']);
        if(empty($this->id)){
            $this->insert($profesor_vs_asignatura);
            $this->id=self::$conn->lastInsertId();
        }else{
            $this->update($this->id, $profesor_vs_asignatura);
        }
    }

    function delete()
    {
        if (!empty($this->id_profesor_vs_asignatura)){
            $this->deleteById($this->id_profesor_vs_asignatura);
            $this->id_profesor_vs_asignatura=null;
            $this->profesor=null;
            $this->asignatura=null;
        }else{
            throw new Exception("No hay registro para borrar");
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