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
        parent::__construct("Tema", "id_tema", $fields);
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

    private function valores()
    {
        $valores = array_map(function ($v) {
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

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

    public function delete()
    {
        // TODO: Implement delete() method.
    }

}