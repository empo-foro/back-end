<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 25/1/19
 * Time: 12:16
 */
require_once 'Tabla.php';

class Respuesta_reportada extends Tabla
{
    private $id_reporte;
    private $tipo_reporte;
    private $descripcion;
    private $respuesta;
    private $usuario;
    private $num_fields=5;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Respuesta_reportada", "id_reporte", $fields);

    }

    /* Getters y Setters */

    public function getId_Reporte()
    {
        return $this->id_reporte;
    }

    public function setId_Reporte($id_reporte): void
    {
        $this->id_reporte = $id_reporte;
    }

    public function getTipo_Reporte()
    {
        return $this->tipo_reporte;
    }

    public function setTipo_Reporte($tipo_reporte): void
    {
        $this->tipo_reporte = $tipo_reporte;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getRespuesta()
    {
        return $this->respuesta;
    }

    public function setRespuesta($respuesta): void
    {
        $this->respuesta = $respuesta;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
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
     * Función que carga los datos de un registro dentro del objeto
     * @param $id Id del registro que queremos recoger
     * @throws Exception Lanza un expeción si no encuentra un registro con el id indicado
     */
    function loadById($id)
    {
        $respuesta_reportada = $this->getById($id);

        if(!empty($respuesta_reportada)) {

            $this->id_reporte = $id;
            $this->tipo_reporte = $respuesta_reportada['tipo_reporte'];
            $this->descripcion = $respuesta_reportada['descripcion'];

            $respuesta = new Respuesta();
            $respuesta->loadById($respuesta_reportada['id_respuesta']);
            $this->respuesta = $respuesta;

            $usuario = new Usuario();
            $usuario->loadById($respuesta_reportada['id_usuario']);
            $this->usuario = $usuario;

        } else {

            throw new Exception("No exite un registro con esa id");

        }
    }

    /**
     * Función qe nos devuelve un array asociativo con los datos del objeto de la clase
     */
    function valores()
    {
        $valores = array_map(function ($v) {
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

    function updateOrInsert()
    {
        $respuesta_reportada = $this->valores();
        unset($respuesta_reportada['id_respuesta']);

        $this->respuesta->updateOrInsert();
        $respuesta_reportada['id_respuesta'] = $this->respuesta->id_respuesta;
        unset($respuesta_reportada['respuesta']);

        $this->usuario->updateOrInsert();
        $respuesta_reportada['id_usuario'] = $this->usuario->id_usuario;
        unset($respuesta_reportada['usuario']);

        if(empty($this->id_reporte)) {
            $this->insert($respuesta_reportada);
            $this->id_reporte == self::lastInsertId();
        } else {
            $this->update($this->id_reporte, $respuesta_reportada);
        }

    }

    function delete()
    {
        if(!empty($this->id_reporte)) {
            $this->deleteById($this->id_reporte);

            foreach ($this->fields as $field) {
                $this->$field = null;
            }

        } else {
            throw new Exception("La respuesta reportada que quieres borrar no existe");
        }
    }

}