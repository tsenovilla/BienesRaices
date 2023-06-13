<?php
    namespace Model;

    use Exception;
    use Intervention\Image\ImageManagerStatic as Image;

    class Propiedad extends BienesRaicesDB
    {

        // Nombre de tabla y columnas
        protected static $table_name = "PROPIEDADES";
        protected static $columns = ["id","titulo_espanol","titulo_english","precio","imagen","descripcion_espanol","descripcion_english","dormitorios","wc","aparcamientos","fecha_publicacion","id_vendedor"];

        // Atributos de los objetos de tipo Propiedad.
        protected $id;
        protected $titulo_espanol;
        protected $titulo_english;
        protected $precio;
        protected $imagen;
        protected $descripcion_espanol;
        protected $descripcion_english;
        protected $dormitorios;
        protected $wc;
        protected $aparcamientos;
        protected $fecha_publicacion;
        protected $id_vendedor;


        public function __construct($image=null, $args = [])
        {
            $this->id = $args['id'] ?? '';
            $this->titulo_espanol = $args['titulo_espanol'] ?? '';
            $this->titulo_english = $args['titulo_english'] ?? '';
            $this->precio = $args['precio'] ?? '';
            if(is_array($image)) // Si hemos cargado la imagen con un imput, es un array
            {
                $this->imagen = $image["name"] ?? '';
                self::$tmp_img = $image["tmp_name"] ?? '';
            }
            else
            {
                $this->imagen = $image ?? '';
            }
            $this->descripcion_espanol = $args['descripcion_espanol'] ?? '';
            $this->descripcion_english = $args['descripcion_english'] ?? '';
            $this->dormitorios = $args['dormitorios'] ?? '';
            $this->wc = $args['wc'] ?? '';
            $this->aparcamientos = $args['aparcamientos'] ?? '';
            $this->fecha_publicacion = $args["fecha_publicacion"] ?? date('Y/m/d');
            $this->id_vendedor = $args['id_vendedor'] ?? '';
        }

        // Getters y setters
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
        public function get_precio()
        {
            return htmlspecialchars($this->precio);
        }
        public function set_precio($new_precio)
        {
            $this->precio = $new_precio;
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
        public function get_descripcion_espanol() 
        {
            return htmlspecialchars($this->descripcion_espanol);
        }
        public function set_descripcion_espanol($new_descripcion_espanol)
        {
            $this->descripcion_espanol = $new_descripcion_espanol;
        }
        public function get_descripcion_english() 
        {
            return htmlspecialchars($this->descripcion_english);
        }
        public function set_descripcion_english($new_descripcion_english)
        {
            $this->descripcion_english = $new_descripcion_english;
        }
        public function get_dormitorios()
        {
            return htmlspecialchars($this->dormitorios);
        }
        public function set_dormitorios($new_dormitorios)
        {
            $this->dormitorios = $new_dormitorios;
        }
        public function get_wc()
        {
            return htmlspecialchars($this->wc);
        }
        public function set_wc($new_wc)
        {
            $this->wc = $new_wc;
        }
        public function get_aparcamientos()
        {
            return htmlspecialchars($this->aparcamientos);
        }
        public function set_aparcamientos($new_aparcamientos)
        {
            $this->aparcamientos = $new_aparcamientos;
        }
        public function get_fecha_publicacion()
        {
            return $this->fecha_publicacion;
        }
        public function set_fecha_publicacion()
        {
            $this->fecha_publicacion = date("Y/m/d");
        }
        public function get_id_vendedor()
        {
            return htmlspecialchars($this->id_vendedor);
        }
        public function set_id_vendedor($new_id_vendedor)
        {
            $this->id_vendedor = $new_id_vendedor;
        }

        
        // Esta función comprueba que los datos del objeto son correctos y sino alimenta el array de errores que tiene asociado el objeto. Si devuelve 0 es que no hay error, sino, devuelve el número de error
        public function validate_data():int
        {
            $lang = $_SESSION["lang"];
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+)(\s(?1))*$/",$this->titulo_espanol))
            {
                KO_alert(["en"=>"You must introduce a title in Spanish","es"=>"Debe introducir un título en español"][$lang]);
                return 1;
            }
            if(!preg_match("/^([A-Za-z]+'{0,1}[A-Za-z]*)(\s(?1))*$/",$this->titulo_english))
            {
                KO_alert(["en"=>"You must introduce a title in English","es"=>"Debe introducir un título en inglés"][$lang]);
                return 2;
            }
            if(!preg_match("/^\d+\.{0,1}\d*$/",$this->precio))
            {
                KO_alert(["en"=>"The price must be a number","es"=>"El precio debe ser un número"][$lang]);
                return 3;
            }
            if(!$this->imagen)
            {
                KO_alert(["en"=>"Select a image to add to the ad. The admitted formats are JPEG, PNG and WEBP","es"=>"Seleccione una imagen para añadirla al anuncio. Los formatos admitidos son JPEG, PNG y WEBP"][$lang]);
                return 4;
            }
            if($this->descripcion_espanol xor $this->descripcion_english)
            {
                KO_alert(["en"=>"If the seller wants to include a description to the add, it should be included in both languages, English and Spanish","es"=>"Si el vendedor quiere añadir una descripción al anuncio, esta debe ser incluida en ambos idiomas, español e inglés"][$lang]);
                return 5;
            }
            if(!preg_match("/^[0-9]{1,2}$/",$this->dormitorios))
            {
                KO_alert(["en"=>"Introduce the number of bedrooms available in the property","es"=>"Indique el número de dormitorios con los que cuenta la propiedad"][$lang]);
                return 6;
            }
            if(!preg_match("/^[0-9]{1,2}$/",$this->wc))
            {
                KO_alert(["en"=>"Introduce the number of WCs available in the property","es"=>"Indique el número de baños con los que cuenta la propiedad"][$lang]);
                return 7;
            }
            if(!preg_match("/^[0-9]{1,2}$/",$this->aparcamientos))
            {
                KO_alert(["en"=>"Introduce the number of parking places available in the property","es"=>"Indique el número de aparcamientos con los que cuenta la propiedad"][$lang]);
                return 8;
            }
            if(!Vendedor::find("id",$this->id_vendedor))
            {
                KO_alert(["en"=>"Choose a seller from the proposed ones","es"=>"Seleccione un vendedor de entre los propuestos"][$lang]);
                return 9;
            }
            // Si hasta aquí no hay errores convertimos los valores numéricos a número. Al hacer esta conversión, ahorramos tiempo a la hora de insertar en la base de datos. 
            $this->precio = floatval($this->precio);
            $this->dormitorios = intval($this->dormitorios);
            $this->wc = intval($this->wc);
            $this->aparcamientos = intval($this->aparcamientos);
            $this->aparcamientos = intval($this->aparcamientos);
            $this->id_vendedor = intval($this->id_vendedor);
            return 0;
        }

        // Este método hace el insert a la base de datos
        public function insert():bool
        {
            $lang = $_SESSION["lang"];
            $this->imagen= md5(uniqid("",true)).strrchr($this->imagen,"."); // Asignamos un nombre único a la imagen antes de hacer deploy
            try
            {
                self::$db->beginTransaction(); // Desactivamos el autocommit ya que hay que actualizar el servidor de forma simultánea
                parent::insert(); // Llamamos al insert
                $this->deploy_resized_image(); // Si insertamos bien, movemos la imagen al servidor
                // Una vez que la imagen se ha guardado bien en el servidor, podemos hacer el commit a la base de datos
                self::$db->commit();
                OK_alert(["en"=>"The real state ad has been correctly created","es"=>"El anuncio ha sido creado correctamente"][$lang]);
                return true;
            }
            catch (Exception)
            {
                self::$db->rollBack();
                KO_alert(["en"=>"Error creating the real state ad. Try it again in a few minutes or contact to support.","es"=>"Error creando el anuncio. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
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
                OK_alert(["en"=>"The real state ad has been correctly updated","es"=>"El anuncio ha sido actualizado correctamente"][$lang]);
                return true;
            }
            catch(Exception)
            {
                self::$db->rollBack();
                KO_alert(["en"=>"Error updating the real state ad. Try it again in a few minutes or contact to support.","es"=>"Error actualizando el anuncio. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
                return false;
            }
        }

        // Delete de la linea selecctionada
        public function delete()
        {
            $lang = $_SESSION["lang"];
            try // Intentamos hacer el delete
            {
                parent::delete();
                // Si todo ha ido bien, borramos la imagen del servidor
                if(is_file(DEPLOYED_IMAGES_URL.$this->imagen))
                {
                    unlink(DEPLOYED_IMAGES_URL.$this->imagen);
                }
                OK_alert(["en"=>"The real state ad has been correctly deleted","es"=>"El anuncio ha sido eliminado correctamente"][$lang]);
            }
            catch(Exception)
            {
                KO_alert(["en"=>"Error deleting the real state ad. Try it again in a few minutes or contact to support.","es"=>"Error eliminando el anuncio. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
            }
        }

        // Implementación del método newObject
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