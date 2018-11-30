<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 28/11/18
 * Time: 12:54
 */

class BaseDatos
{

    static $server = "localhost";
    static $user = "root";
    static $password = "";
    static $database = "empo";

    private $table; //Nombre de la tabla
    private $idField; //Nombre de la primary key de la tabla
    private $fields; //Array con los nombres de los campos de la tabla (opcional)
    private $showFields; //

    static private $conn;


    /**
     * El constructor necesita el nombre de la tabla y el nombre del campo clave
     * Opcionalmente podemos indicar los campos que tiene la tabla y aquellos que queremos mostrar
     * Cuando se haga una selección
     * @param type $table nombre de la tabla
     * @param type $idField nombre de la primary key de la tabla
     * @param type $fields (opcional) campos de la tabla
     * @param type $showFields (opcional) campos que queremos mostrar de la tabla
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

            self::$conn = new PDO("mysql:host=" . self::$server . ":dbname =" . self::$database, self::$user, self::$password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAME 'utf8'"]);

            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $ex) {

            echo $ex->getMessage();

        }
    }

    /**
     * Función para los recoger los valores de las propiedades
     * @param $name nombre de la tabla que queremos
     * @return mixed
     */
    public function __get($name)
    {

        if (property_exists(this, $name)) {

            return $this->$name;

        }

    }

    /**
     * Función para cambiar las propiedades
     * @param $name propiedad que queremos cambiar
     * @param $value nuevo valor para la propiedad
     * @throws Exception si hay algun error lanzaremos esta excepcion
     */
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        if (property_exists($this, $name) && !empty($value)) {

            $this->$name = $value;

        } else {

            throw new Exception("Error: datos incorrectos");
        }
    }

    /*A partir de aquí estaran las funciones CRUD*/

    /**
     * Función que nos devuelve todos los registros, pero podemo poner una condición
     * @param string $condicion (opcional) si queremos poner alguna condición
     * @param bool $completo
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

        $res = self::$conn->query("select " . $campos . " from " . $this->table . $where);
        return $res->fetchAll(PDO::FETCH_ASSOC);

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

        $sentencia = self::$conn->prepare("select " . $campos . " from " . $this->table . $where);
        $sentencia->execute($condicion);

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);

    }


}