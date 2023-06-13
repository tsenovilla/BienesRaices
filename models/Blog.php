<?php

    namespace Model;
    use Intervention\Image\ImageManagerStatic as Image;
    use Exception;

    class Blog extends BienesRaicesDB
    {
        // Nombre de tabla y columnas
        protected static $table_name = "BLOG";
        protected static $columns = ["id","titulo_espanol","titulo_english","fecha","autor","resumen_espanol","resumen_english","imagen","texto_espanol","texto_english"];

        // Atributos de objeto
        protected $id;
        protected $titulo_espanol;
        protected $titulo_english;
        protected $fecha;
        protected $autor;
        protected $resumen_espanol;
        protected $resumen_english;
        protected $imagen;
        protected $texto_espanol;
        protected $texto_english;

        public function __construct($image=null, $args=[])
        {
            $this->id = $args["id"] ?? ""; 
            $this->titulo_espanol = $args["titulo_espanol"] ?? ""; 
            $this->titulo_english = $args["titulo_english"] ?? ""; 
            $this->fecha = $args["fecha"] ?? date("Y/m/d");
            $this->autor = $args["autor"] ?? "";
            $this->resumen_espanol = $args["resumen_espanol"] ?? "";
            $this->resumen_english = $args["resumen_english"] ?? "";
            if(is_array($image)) 
            {
                $this->imagen = $image["name"] ?? '';
                self::$tmp_img = $image["tmp_name"] ?? '';
            }
            else
            {
                $this->imagen = $image ?? '';
            }
            $this->texto_espanol = $args["texto_espanol"] ?? "";
            $this->texto_english = $args["texto_english"] ?? "";
        }

        //Getters y setters
        public function get_id()
        {
            return $this->id;
        }
        public function set_id($new_id)
        {
            $this->id = $new_id;
        }
        public function get_titulo_espanol()
        {
            return htmlspecialchars($this->titulo_espanol);
        }
        public function set_titulo_espanol($new_titulo_espanol)
        {
            $this->titulo_espanol = $new_titulo_espanol;
        }
        public function get_titulo_english()
        {
            return htmlspecialchars($this->titulo_english);
        }
        public function set_titulo_english($new_titulo_english)
        {
            $this->titulo_english = $new_titulo_english;
        }
        public function get_fecha()
        {
            return htmlspecialchars($this->fecha);
        }
        public function set_fecha()
        {
            $this->fecha = date("Y/m/d");
        }
        public function get_autor()
        {
            return htmlspecialchars($this->autor);
        }
        public function set_autor($new_autor)
        {
            $this->autor = $new_autor;
        }
        public function get_resumen_espanol()
        {
            return htmlspecialchars($this->resumen_espanol);
        }
        public function set_resumen_espanol($new_resumen_espanol)
        {
            $this->resumen_espanol = $new_resumen_espanol;
        }
        public function get_resumen_english()
        {
            return htmlspecialchars($this->resumen_english);
        }
        public function set_resumen_english($new_resumen_english)
        {
            $this->resumen_english = $new_resumen_english;
        }
        public function get_imagen()
        {
            return $this->imagen;
        }
        public function set_imagen($new_imagen) // Si cambiamos la imagen del objeto, actualizamos las variables auxiliares de la clase para la gestion de imagenes
        {
            self::$old_image_name = $this->imagen;
            $this->imagen = md5(uniqid("",true)).strrchr($new_imagen["name"],"."); // Generamos el nombre único para la nueva imagen
            self::$tmp_img = $new_imagen["tmp_name"]; // Almacenamos la ubicación temporal
        }
        public function get_texto_espanol()
        {
            return htmlspecialchars($this->texto_espanol);
        }
        public function set_texto_espanol($new_texto_espanol)
        {
            $this->texto_espanol = $new_texto_espanol;
        }
        public function get_texto_english()
        {
            return htmlspecialchars($this->texto_english);
        }
        public function set_texto_english($new_texto_english)
        {
            $this->texto_english = $new_texto_english;
        }

        // Esta función sirve para verificar que el usuario y password son correctos.
        public function validate_data():int
        {
            $lang = $_SESSION["lang"];
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+)(\s(?1))*$/",$this->titulo_espanol))
            {
                KO_alert(["en"=>"You must introduce a title in Spanish. This is important to allow people to read our blog in Spanish","es"=>"Introduzca un título en español. Esto es importante para que nuestro blog pueda ser leído en español"][$lang]);
                return 1;
            }
            if(!preg_match("/^([A-Za-z]+['|\-]{0,1}[A-Za-z]*)(\s(?1))*$/",$this->titulo_english))
            {
                KO_alert(["en"=>"You must introduce a title in English. This is important to allow people to read our blog in English","es"=>"Introduzca un título en inglés. Esto es importante para que nuestro blog pueda ser leído en inglés"][$lang]);
                return 2;
            }
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+'{0,1}[A-Za-zÑñÁáÉéÍíÓóÚú]*)(\s(?1))*$/",$this->autor))
            {
                KO_alert(["en"=>"Introduce a valid author name","es"=>"Introduzca un nombre de autor válido"][$lang]);
                return 3;
            }
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+'{0,1}[A-Za-zÑñÁáÉéÍíÓóÚú]*)([\s|,|\.|\n]*(?1)*)*$/",$this->resumen_espanol))
            {
                KO_alert(["en"=>"You must introduce a summary in Spanish. This is important to allow people to read our blog in Spanish","es"=>"Introduzca un resumen en español. Esto es importante para que nuestro blog pueda ser leído en español"][$lang]);
                return 4;
            }
            if(!preg_match("/^([A-Za-z]+['|\-]{0,1}[A-Za-z]*)([\s|,|\.|\n]*(?1)*)*$/",$this->resumen_english))
            {
                KO_alert(["en"=>"You must introduce a summary in English. This is important to allow people to read our blog in English","es"=>"Introduzca un resumen en inglés. Esto es importante para que nuestro blog pueda ser leído en inglés"][$lang]);
                return 5;
            }
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+)([\s|,|\.|\n]*(?1)*)*$/",$this->texto_espanol))
            {
                KO_alert(["en"=>"You must introduce the blog text in Spanish. This is important to allow people to read our blog in Spanish","es"=>"Introduzca el cuerpo de la entrada en español. Esto es importante para que nuestro blog pueda ser leído en español"][$lang]);
                return 6;
            }
            if(!preg_match("/^([A-Za-z]+['|\-]{0,1}[A-Za-z]*)([\s|,|\.|\n]*(?1)*)*$/",$this->texto_english))
            {
                KO_alert(["en"=>"You must introduce the blog text in English. This is important to allow people to read our blog in English","es"=>"Introduzca el cuerpo de la entrada en inglés. Esto es importante para que nuestro blog pueda ser leído en inglés"][$lang]);
                return 7;
            }
            return 0;
        }

        // Métodos del CRUD
        // Este método hace el insert a la base de datos
        public function insert():bool
        {
            $lang = $_SESSION["lang"];
            if($this->imagen)
            {
                $this->imagen= md5(uniqid("",true)).strrchr($this->imagen,"."); // La imagen no es obligatoria, así que no hacemos nada con ella si no se ha proporcionado
            }
            try
            {
                self::$db->beginTransaction(); // Desactivamos el autocommit ya que hay que actualizar el servidor de forma simultánea
                parent::insert(); // Llamamos al insert
                if($this->imagen)
                {
                    $this->deploy_resized_image();
                }
                self::$db->commit();
                OK_alert(["en"=>"The blog entry has been correctly created","es"=>"La entrada del blog ha sido creada correctamente"][$lang]);
                return true;
            }
            catch (Exception)
            {
                self::$db->rollBack();                
                KO_alert(["en"=>"Error creating the blog entry. Try it again in a few minutes or contact to support.","es"=>"Error creando la entrada del blog. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
                return false;
            }
        }

        public function update():bool
        {
            $lang = $_SESSION["lang"];
            try
            {  
                // Como hay que actualizar el servidor además de la base de datos, desactivamos el autocommit, así, si no van bien todos los cambios, no desincronizamos BD y servidor
                self::$db->beginTransaction();
                parent::update();
                // Si la imagen del objeto es nueva, no existe en el servidor, por lo que intentamos cargarla
                if(!file_exists(DEPLOYED_IMAGES_URL.$this->imagen))
                {
                    $this->deploy_resized_image();
                }
                // Una vez que la imagen que hemos referenciado en la base de datos está cargada en el servidor, podemos hacer commit del update
                self::$db->commit();
                // Una vez que el servidor y la base de datos están correctamente actualizados, si tenemos imagen vieja en el servidor, la machacamos
                if(is_file(DEPLOYED_IMAGES_URL.self::$old_image_name))
                {
                    unlink(DEPLOYED_IMAGES_URL.self::$old_image_name);
                }
                OK_alert(["en"=>"The blog entry has been correctly updated","es"=>"La entrada del blog ha sido actualizada correctamente"][$lang]);
                return true;
            }
            catch(Exception)
            {
                self::$db->rollBack();
                KO_alert(["en"=>"Error updating the blog entry. Try it again in a few minutes or contact to support.","es"=>"Error actualizando la entrada del blog. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
                return false;
            }
        }

        // Delete de la linea seleccionada
        public function delete()
        {
            $lang = $_SESSION["lang"];
            try // Intentamos hacer el delete
            {
                parent::delete();
                if(is_file(DEPLOYED_IMAGES_URL.$this->imagen))
                {
                    unlink(DEPLOYED_IMAGES_URL.$this->imagen);
                }
                OK_alert(["en"=>"The blog entry has been correctly deleted","es"=>"La entrada del blog ha sido eliminada correctamente"][$lang]);
            }
            catch(Exception)
            {
                KO_alert(["en"=>"Error deleting the blog entry. Try it again in a few minutes or contact to support.","es"=>"Error eliminando la entrada del blog. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
            }
        }

        protected static function newObject($row)
        {
            return new self($row["imagen"],$row);
        }

        // PRIVATE FUNCTIONS
        // Esta función sirve para desplegadar imagenes en el servidor siendo todas del mismo tamaño, en nuestro caso 800x600. No admitimos AVIF porque Intervention Image no tiene soporte
        private function deploy_resized_image()
        {
            if(!is_dir(DEPLOYED_IMAGES_URL)) // Nos aseguramos de que exista el directorio, sino lo creamos
            {
                mkdir(DEPLOYED_IMAGES_URL);
            }
            $imagen = Image::make(self::$tmp_img)->fit(self::$img_width,self::$img_height);
            $imagen->save(DEPLOYED_IMAGES_URL.$this->imagen);
        }
    }