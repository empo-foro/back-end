<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 28/11/18
 * Time: 12:54
 */

abstract class Tabla
{

    static $server = "localhost";
    static $user = "root";
    static $passwd = "";
    static $database = "empo";

    protected $table; //Nombre de la tabla
    protected $idField; //Nombre de la primary key de la tabla
    protected $fields; //Array con los nombres de los campos de la tabla (opcional)
    protected $showFields; //

    static protected $conn;


    /**
     * El constructor necesita el nombre de la tabla y el nombre del campo clave
     * Opcionalmente podemos indicar los campos que tiene la tabla y aquellos que queremos mostrar
     * Cuando se haga una selección
     * @param String $table nombre de la tabla
     * @param String $idField nombre de la primary key de la tabla
     * @param mixed $fields (opcional) campos de la tabla
     * @param mixed $showFields (opcional) campos que queremos mostrar de la tabla
     */

    public function __construct($table, $idField, $fields = "", $showFields = "")
    {

        $this->table = $table;
        $this->idField = $idField;
        $this->fields = $fields;
        $this->showFields = $showFields;
        self::conectar();

    }


    /**
     * Función para conectar a la base de datos
     */
    static function conectar()
    {
        try {

            self::$conn = new PDO("mysql:host=" . self::$server . ";dbname=" . self::$database, self::$user, self::$passwd, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $ex) {

            echo $ex->getMessage();

        }
    }

    /**
     * Función para los recoger los valores de las propiedades
     * @param String $name nombre de la tabla que queremos
     * @return mixed
     */
    function __get($name)
    {

        if (property_exists($this, $name)) {

            return $this->$name;

        }

    }

    /**
     * Función para cambiar las propiedades
     * @param String $name propiedad que queremos cambiar
     * @param String $value nuevo valor para la propiedad
     * @throws Exception si hay algún error lanzaremos esta excepción
     */
    function __set($name, $value)
    {

        if (property_exists($this, $name) && !empty($value)) {

            $this->$name = $value;

        } else {

            throw new Exception("Error: datos incorrectos");
        }

    }

    /*A partir de aquí estarán las funciones CRUD*/

    /**
     * Función que nos devuelve todos los registros, como parámetro opcional podemos añadir una condición
     * @param string $condicion (opcional) si queremos poner alguna condición
     * @param bool $completo
     * @return mixed
     */
    function getAll($condicion = "", $completo = true)
    {

        $where = "";
        $campos = " * ";

        if (!empty($condicion)) {

            $where = " where 1 = 1";

            foreach ($condicion as $clave => $valor) {

                $where .= " and " . $clave . " = '" . $valor . "' ";

            }
        }

        if (!$completo && !empty($this->showFields)) {

            $campos = implode(",", $this->showFields);
        }
        $resultado = self::$conn->query("select " . $campos . " from " . $this->table . $where);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * La función tiene el mismo comentido que la anterior pero usando prepare
     * @param array $condicion
     * @param bool $completo
     * @return mixed
     */
    function getAllPrepare($condicion = [], $completo = true)
    {

        $where = "";
        $campos = " * ";

        if (!empty($condicion)) {

            $where = " where " . join(" and ", array_map(function ($valor) {
                    return $valor . "=:" . $valor;
                }, array_keys($condicion)));

        }

        if (!completo && !empty($this->showFields)) {

            $campos = implode(",", $this->showFields);

        }

        $resultado = self::$conn->prepare("select " . $campos . " from " . $this->table . $where);
        $resultado->execute($condicion);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Esta función nos devuelve la fila de la tabla que tenga este id
     * @param int $id valor del campo clave que queremos recoger
     * @return devuelve los datos del campo recogido de la base de datos
     */
    protected function getById($id)
    {

        $resultado = self::$conn->query("select * from " . $this->table . " where " . $this->idField . " = " . $id);
        return $resultado->fetch(PDO::FETCH_ASSOC);

    }

    /**
     * Esta función toma como parámetro un array associativo y nos inserta en la tabla
     * un registro donde la clave del array hace referencia al campo de la tabla y
     * el valor del array al valor de la tabla
     * @param array $valores
     * @return
     */
    protected function insert($valores)
    {

        try {

            $campos = join(",", array_keys($valores));
            $parametros = ":" . join(",:", array_keys($valores));
            $sql = "insert into " . $this->table . "($campos) values ($parametros)";
            $resultado = self::$conn->prepare($sql);
            $resultado->execute($valores);
            return self::$conn->lastInsertId(); //Añadido para que nos devuelva el id del registro creado

        } catch (Exception $ex) {

            echo $ex->getMessage();

        }
    }

    /**
     * Esta función modifica el elemento de la base de datos con el id que pasamos
     * con los valores del array asociativo
     * @param int $id id del elemento a modificar
     * @param array $valores Array asociativo con los valores a modificar
     */
    protected function update($id, $valores)
    {

        try {

            $campos = join(",", array_map(function ($v) {
                return $v . "=:" . $v;
            }, array_keys($valores)));

            $sql = "update " . $this->table . " set " . $campos . " where " . $this->idField . " = " . $id;
            $resultado = self::$conn->prepare($sql);
            $resultado->execute($valores);

        } catch (Exception $ex) {

            echo $ex->getMessage();

        }

    }

    /**
     * Elimina el registro que tenga el id que le pasamos
     * @param $id
     */
    protected function deleteById($id)
    {
        try {

            self::$conn->exec("delete from " . $this->table . " where " . $this->idField . " = " . $id);

        } catch (Exception $ex) {

            echo $ex->getMessage();

        }
    }

    abstract function loadById($id);

    abstract function updateOrInsert();

    abstract function delete();
}
