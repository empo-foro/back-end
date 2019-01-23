<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 18/1/19
 * Time: 12:10
 */
require_once "Tabla.php";

class Post extends Tabla
{
    private $id_post;
    private $titulo;
    private $cuerpo;
    private $fecha;
    private $cerrado;
    private $alumno;
    private $num_fields = 6;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Curso", "id_curso", $fields);

    }

    /* Getters y Setters */

    public function getId_Post()
    {
        return $this->id_post;
    }

    public function setId_Post($id_post): void
    {
        $this->id_post = $id_post;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    public function setCuerpo($cuerpo): void
    {
        $this->cuerpo = $cuerpo;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getCerrado()
    {
        return $this->cerrado;
    }

    public function setCerrado($cerrado): void
    {
        $this->cerrado = $cerrado;
    }

    public function getAlumno()
    {
        return $this->alumno;
    }

    public function setAlumno($alumno): void
    {
        $this->alumno = $alumno;
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
        $post = $this->getById($id);

        if (!empty($post)) {

            $this->id_post = $id;
            $this->titulo = $post['titulo'];
            $this->cuerpo = $post['cuerpo'];
            $this->fecha = $post['fecha'];
            $this->cerrado = $post['cerrado'];

            $alumno = new Alumno();
            $alumno->loadById($post['id_alumno']);
            $this->alumno = $alumno;

        } else {

            throw new Exception("No existe un registro con ese id");

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
        $post = $this->valores();
        unset($post["id_post"]);

        $this->
    }

    function delete()
    {
        // TODO: Implement delete() method.
    }
}