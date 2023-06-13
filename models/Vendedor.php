<?php
    namespace Model;

    use Exception; 

    class Vendedor extends BienesRaicesDB
    {
        // Nombre de tabla y columnas
        protected static $table_name = "VENDEDORES";
        protected static $columns = ["id","nombre","apellido","email"];
        // Atributos de los objetos de tipo Propiedad
        public int $id;
        public string $nombre;
        public string $apellido;
        public string $email;


        public function __construct($args = [])
        {
            $this->id = $args['id'] ?? 0;
            $this->nombre = $args['nombre'] ?? '';
            $this->apellido = $args['apellido'] ?? '';
            $this->email = $args['email'] ?? '';
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
        public function get_nombre()
        {
            return htmlspecialchars($this->nombre);
        }
        public function set_nombre($new_nombre)
        {
            $this->nombre = $new_nombre;
        }
        public function get_apellido()
        {
            return htmlspecialchars($this->apellido);
        }
        public function set_apellido($new_apellido)
        {
            $this->apellido = $new_apellido;
        }
        public function get_email()
        {
            return htmlspecialchars($this->email);
        }
        public function set_email($new_email)
        {
            $this->email = $new_email;
        }

        // Esta función comprueba que los datos obligatorios han sido introducidos para un insert o update, devolviendo un array vacío en caso afirmativo, sino devuelve un array con los datos erróneos.
        public function validate_data():int
        {
            $lang = $_SESSION["lang"];
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+'{0,1}[A-Za-zÑñÁáÉéÍíÓóÚú]*)(\s(?1))*$/",$this->nombre))
            {
                KO_alert(["en"=>"The introduced first name is not valid","es"=>"El nombre introducido no es válido"][$lang]);
                return 1;
            }
            if(!preg_match("/^([A-Za-zÑñÁáÉéÍíÓóÚú]+'{0,1}[A-Za-zÑñÁáÉéÍíÓóÚú]*)(\s(?1))*$/",$this->apellido))
            {
                KO_alert(["en"=>"The introduced last name is not valid","es"=>"Los apellidos introducidos no son válidos"][$lang]);
                return 2;
            }
            if(!filter_var($this->email,FILTER_VALIDATE_EMAIL))
            {
                KO_alert(["en"=>"Introduce a valid e-mail","es"=>"Introduzca un e-mail válido"][$lang]);
                return 3;
            }
            return 0;
        }

        // Reescritura de los métodos del CRUD para ajustarlos a la clase vendedor
        public function insert():bool
        {
            $lang = $_SESSION["lang"];
            try
            {
                parent::insert();
                OK_alert(["en"=>"The seller has been correctly created","es"=>"El vendedor ha sido creado correctamente"][$lang]);
                return true;
            }
            catch(Exception)
            {
                KO_alert(["en"=>"Error creating the seller. Try it again in a few minutes or contact to support.","es"=>"Error creando al vendedor. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
               return false;
            }
        }

        public function update()
        {
            $lang = $_SESSION["lang"];
            try
            {
                parent::update();
                OK_alert(["en"=>"The seller has been correctly updated","es"=>"El vendedor ha sido actualizado correctamente"][$lang]);
                return true;
            }
            catch(Exception)
            {
                KO_alert(["en"=>"Error updating the seller. Try it again in a few minutes or contact to support.","es"=>"Error actualizando al vendedor. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
                return false;
            }
        }

        public function delete()
        {
            $lang = $_SESSION["lang"];
            $propiedades = Propiedad::find("id_vendedor",$this->id);
            if($propiedades) // Si el vendedor tiene propiedades registradas, no permitimos su eliminación
            {
                KO_alert(["en"=>"Error deleting the seller. The seller has published ads inside our web, be sure to only delete those sellers whose ads have been completely manged","es"=>"Error eliminando al vendedor. El vendedor tiene anuncios publicados en nuestra web, asegúrese de eliminar solamente aquellos vendedores cuyos anuncios hayan sido completamente gestionados"][$lang]);

            }
            else
            {
                try
                {
                    parent::delete();
                    OK_alert(["en"=>"The seller has been correctly deleted","es"=>"El vendedor ha sido eliminado correctamente"][$lang]);
                }
                catch(Exception)
                {
                    KO_alert(["en"=>"Error deleting the seller. Try it again in a few minutes or contact to support.","es"=>"Error eliminando al vendedor. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
                }
            }
        }

        // Implementación de newObject
        protected static function newObject($row)
        {
            return new self($row);
        }

    }