<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 25/1/19
 * Time: 12:18
 */

require_once 'Tabla.php';

class Post_vs_Tema extends Tabla
{
    private $id_post_vs_tema;
    private $id_post;
    private $id_tema;
    private $num_fields;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Post_vs_Tema", "id_post_vs_tema", $fields);
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

    public function getId_Post_Vs_Tema()
    {
        return $this->id_post_vs_tema;
    }

    public function setId_Post_Vs_Tema($id_post_vs_tema): void
    {
        $this->id_post_vs_tema = $id_post_vs_tema;
    }

    public function getId_Post()
    {
        return $this->id_post;
    }

    public function setId_Post($id_post): void
    {
        $this->id_post = $id_post;
    }

    public function getId_Tema()
    {
        return $this->id_tema;
    }

    public function setId_Tema($id_tema): void
    {
        $this->id_tema = $id_tema;
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
        $Post_vs_Tema = $this->getById($id);

        if (!empty($Post_vs_Tema)) {
            $this->id_post_vs_tema = $id;
            $this->id_post = $Post_vs_Tema["post"];
            $this->id_tema = $Post_vs_Tema = ["tema"];
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
        $Post_vs_Tema = $this->valores();

        unset($Post_vs_Tema['id_post_vs_tema']);
        if (empty($this > $this->id_post_vs_tema)) {
            $this->insert($Post_vs_Tema);
            $this->id_post_vs_tema = self::$conn->lastInertId();
        } else {
            $this->update($this->id_post_vs_tema, $Post_vs_Tema);
        }
    }

    function delete()
    {
        if (!empty($this->id_post_vs_tema)) {
            $this->deleteById($this->id_post_vs_tema);
            $this->id_post_vs_tema = null;
            $this->post = null;
            $this->tema = null;
        } else {
            throw new Exception("No hay registro para borrar");
        }
    }


}