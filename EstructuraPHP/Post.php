<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 18/1/19
 * Time: 12:10
 */
require_once "Tabla.php";
require_once "Alumno.php";

class Post extends Tabla
{
    private $id_post;
    private $titulo;
    private $cuerpo;
    private $fecha;
    private $cerrado;
    private $alumno;
    private $id_asignatura;
    private $num_fields = 7;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Post", "id_post", $fields);

    }

    /* Getters y Setters */

    public function getId_Asignatura(){
        return $this->id_asignatura;
    }

    public function setId_Asignatura($id_asignatura):void {
        $this->id_asignatura=$id_asignatura;
    }

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
            $this->id_asignatura = $post["id_asignatura"];

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

    /**
     * Función que inserta o modifica un registro
     */
    function updateOrInsert()
    {
        $post = $this->valores();
        unset($post["id_post"]);

        $this->alumno->updateOrInsert();
        $post['id_alumno'] = $this->alumno->id_alumno;
        unset($post['alumno']);

        if(empty($this->id_post)) {
            $this->insert($post);
            $this->id_post == self::lastInsertId();
        } else {
            $this->update($this->id_post, $post);
        }

    }

    /**
     * Función que elimina un registro, si el campo id del objeto coincide con el id de un registro en la base de datos
     */
    function delete()
    {
        if(!empty($this->id_post)) {
            $this->deleteById($this->id_post);
            $this->id_post = null;
            $this->titulo = null;
            $this->cuerpo = null;
            $this->fecha = null;
            $this->cerrado = null;
            $this->alumno = null;
            $this->id_asignatura = null;
        } else {
            throw new Exception("El post que quieres borrar no existe");
        }
    }

    /**
     * Función que te devuelve todos los post de una asignatura en función a su id
     */
    function asignaturaPost($id){

        $resultado = self::$conn->query("select * from post where id_asignatura = '" . $id ."'");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize()
    {
        return $this->valores();
    }
}