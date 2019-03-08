<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 25/1/19
 * Time: 12:33
 */

require_once 'Tabla.php';

class Respuesta_Guardada extends Tabla
{
    private $id_guardado;
    private $id_respuesta;
    private $id_usuario;
    private $num_fields=3;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Respuesta_Guardada", "id_guardado", $fields);
    }

    /* Setters y Getters */
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

    public function getId_Guardado()
    {
        return $this->id_guardado;
    }

    public function setId_Guardado($id_guardado): void
    {
        $this->id_guardado = $id_guardado;
    }

    public function getId_Respuesta()
    {
        return $this->id_respuesta;
    }

    public function setId_Respuesta($id_respuesta): void
    {
        $this->id_respuesta = $id_respuesta;
    }

    public function getId_Usuario()
    {
        return $this->id_usuario;
    }

    public function setId_Usuario($id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNum_Fields()
    {
        return $this->num_fields;
    }

    public function setNum_Fields($num_fields): void
    {
        $this->num_fields = $num_fields;
    }

    function loadById($id)
    {
        $Respuesta_Guardada = $this->getById($id);

        if (!empty($Respuesta_Guardada)) {
            $this->id_guardado = $id;
            $this->id_usuario = $Respuesta_Guardada["usuario"];
            $this->id_respuesta = $Respuesta_Guardada = ["respuesta"];
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

    function updateOrInsert()
    {
        $Respuesta_Guardada = $this->valores();

        unset($Respuesta_Guardada['id_guardado']);
        if (empty($this > $this->id_guardado)) {
            $this->insert($Respuesta_Guardada);
            $this->id_post_vs_tema = self::$conn->lastInertId();
        } else {
            $this->update($this->id_guardado, $Respuesta_Guardada);
        }
    }

    function delete()
    {
        if (!empty($this->id_guardado)) {
            $this->deleteById($this->id_guardado);
            $this->id_respuesta_guardada = null;
            $this->usuario = null;
            $this->respuesta = null;
        } else {
            throw new Exception("No hay registro para borrar");
        }
    }

}