<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 19/12/18
 * Time: 13:01
 */

require_once 'Tabla.php';

class Tema extends Tabla
{

    private $id_tema;
    private $nombre;
    private $num_fields = 2;

    /**
     * Tema constructor.
     * @param $id_tema
     * @param $nombre
     * @param int $num_fields
     */

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("tema", "id_tema", $fields);
    }

    //GETTER Y SETTERS
    public function getIdTema()
    {
        return $this->id_tema;
    }

    public function setIdTema($id_tema): void
    {
        $this->id_tema = $id_tema;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getNumFields(): int
    {
        return $this->num_fields;
    }

    public function setNumFields(int $num_fields): void
    {
        $this->num_fields = $num_fields;
    }

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
     * Función que nos devuelve un registro de la base de datos si coincide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    public function loadById($id)
    {
        $tema = $this->getById($id);

        if (!empty($tema)) {
            $this->id_tema = $id;
            $this->nombre = $tema["nombre"];
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
        $valores = array_map(function ($v) {
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

    /**
     * Función que modifica o inserta un registro
     */
    function updateOrInsert(){
        $tema=$this->valores();

        unset($tema['id_tema']);
        if(empty($this->id_tema)){
            $this->insert($tema);
            $this->id_tema=self::$conn->lastInsertId();
        }else{
            $this->update($this->id_tema, $tema);
        }
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    public function delete()
    {
        if (!empty($this->id_tema)){
            $this->deleteById($this->id_tema);
            $this->id_tema=null;
            $this->nombre=null;
        }else{
            throw new Exception("No hay registro para borrar");
        }
    }

}