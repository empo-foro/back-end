<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 5/12/18
 * Time: 12:16
 */

class Centro extends Tabla
{

    private $idcentro; //set y get
    private $nif; //set y get
    private $nombre; //set y get
    private $password; //set
    private $biografia; //set y get
    private $descripcion; //set y get
    private $imagen_personal; //set y get
    private $email; //set y get
    private $num_fields = 8;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("centro", "idcentro", $fields);
    }



    /**
     * Esta función nos devuelve la fila de la tabla que tenga esta nif
     * @param string $nif NIF por el que estamos buscado
     * @return
     */
    function getByNif($nif)
    {
        try {

            $resultado = self::$conn->query("select * from " . $this->table . " where nif " . " = " . "$nif");
            return $resultado->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {

            return $ex->getMessage();

        }


    }

    /**
     * Función que nos devuelve un registro si el usuario se ha encontrado dentro del la base de datos
     * @param $nif
     * @param $password
     * @return string
     */
    function logIn($nif, $password)
    {
        try {

            $resultado = self::$conn->query("select * from " . $this->table . " where nif " . " = " . $nif);
            return $resultado->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {

            return $ex->getMessage();

        }
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    function delete()
    {
        if (!empty($this->idcentro)) {
            $this->deleteById($this->idcentro);
            $this->idcentro = null;
            $this->nombre = null;
        } else {
            throw new Exception("No hay registro para borrar");
        }
    }

    /**
     * Función que nos devuelve un registro de la base de datos si conicide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    function loadById($id)
    {
        $centro = $this->getById($id);

        if (!empty($centro)) {
            $this->idcentro = $id;
            $this->nombre = $centro["nombre"];
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
     * Función que modifica o inserta un registro, dependiendo de la variable idcentro de la clase
     */
    function updateOrInsert()
    {
        $centro = $this->valores();
        unset($centro['idcentro']);
        if (empty($this->idcentro)) { //Si el idcentro de la clase esta vacío, insertaremos un nuevo registro en la base de datos
            $this->insert($centro);
            $this->idcentro = self::$conn->lastInsertId();
        } else { //Si el idcentro de la clase tiene un valor, modificaremos el registro que coincida con el id de la clase en la base de datos
            $this->update($this->idcentro, $centro);
        }
    }


}