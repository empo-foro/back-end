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
     * Función para los Get de las propiedades
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {

        if(property_exists(this, $name)) {

            return $this->$name;

        }

    }

}