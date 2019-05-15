<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 25/1/19
 * Time: 11:36
 */
require_once 'Tabla.php';
require_once 'Post.php';

class Respuesta extends Tabla
{
    private $id_respuesta;
    private $asunto;
    private $texto;
    private $fecha;
    private $post;
    private $usuario;
    private $respuesta_padre;
    private $num_fields = 7;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("respuesta", "id_respuesta", $fields);

    }

    /* Getters y Setters */

    public function getId_Respuesta()
    {
        return $this->id_respuesta;
    }

    public function setId_Respuesta($id_respuesta): void
    {
        $this->id_respuesta = $id_respuesta;
    }

    public function getAsunto()
    {
        return $this->asunto;
    }

    public function setAsunto($asunto): void
    {
        $this->asunto = $asunto;
    }

    public function getTexto()
    {
        return $this->texto;
    }

    public function setTexto($texto): void
    {
        $this->texto = $texto;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post): void
    {
        $this->post = $post;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getRespuesta_Padre()
    {
        return $this->respuesta_padre;
    }

    public function setRespuesta_Padre($respuesta_padre): void
    {
        $this->respuesta_padre = $respuesta_padre;
    }

  public function setId_Post($id): void
  {
    $post = new Post();
    $post->loadById($id);
    $this->post = $post;
  }

  public function setId_Usuario($id): void
  {
    $usuario = new Usuario();
    $usuario->loadById($id);
    $this->usuario = $usuario;
  }
  public function setId_Respuesta_Padre($id): void
  {
    $respuesta = new Respuesta();
    $respuesta->loadById($id);
    $this->respuesta_padre = $respuesta;
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
        $respuesta = $this->getById($id);

        if (!empty($respuesta)) {

            $this->id_respuesta = $id;
            $this->asunto = $respuesta['asunto'];
            $this->texto = $respuesta['texto'];
            $this->fecha = $respuesta['fecha'];

            $post = new Post();
            $post->loadById($this->post['id_post']);
            $this->post = $post;

            $usuario = new Usuario();
            $usuario->loadById($this->usuario['id_usuario']);
            $this->usuario = $usuario;

            if(!empty($this->respuesta_padre['id_respuesta_padre'])) {
                $respuesta_padre = new Respuesta();
                $respuesta_padre->loadById($this->respuesta_padre['id_respuesta_padre']);
                $this->respuesta_padre = $respuesta_padre;
            } else {
                $this->respuesta_padre = null;
            }

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
     * Función que inserta y actualiza en la base de datos.
     */
    function updateOrInsert()
    {
        $respuesta = $this->valores();
        unset($respuesta['id_respuesta']);

        //$this->post->updateOrInsert();
        $respuesta['id_post'] = $this->post->id_post;
        unset($respuesta['post']);

        //$this->usuario->updateOrInsert();
        $respuesta['id_usuario'] = $this->usuario->id_usuario;
        unset($respuesta['usuario']);

        //$this->respuesta_padre->updateOrInsert();
        if(!empty($respueta['id_respuesta_pade'])) {
            $respuesta['id_respuesta_padre'] = $this->respuesta_padre->id_respuesta;
        }
        unset($respuesta['respuesta_padre']);
        
        if (empty($this->id_respuesta)) {
            $lastId = $this->insert($respuesta);
            $this->id_respuesta = $lastId;
        } else {
            $this->update($this->id_respuesta, $respuesta);
        }
    }

    /**
     * Función que elimina un registro, si el campo id del objeto coincide con el id de un registro en la base de datos
     */
    function delete()
    {
        if (!empty($this->id_respuesta)) {
            $this->deleteById($this->id_respuesta);

            foreach ($this->fields as $field) {
                $this->$field = null;
            }

        } else {
            throw new Exception("La respuesta que quieres borrar no existe");
        }
    }

    function getUserRespuestasByToken($id_token){

        $resultado = self::$conn->query("SELECT respuesta.* FROM respuesta inner join 
        post on respuesta.id_post = post.id_post inner join
        alumno on post.id_alumno = alumno.id_alumno inner join 
        usuario on alumno.id_usuario = usuario.id_usuario where usuario.id_token = '" . $id_token . "'");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }


    function comentariosPost ($id){

        $resultado = self::$conn->query("select * from respuesta where id_post = '". $id ."'");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize() {
    return $this->valores();
    }

}
