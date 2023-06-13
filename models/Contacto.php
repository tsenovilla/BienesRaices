<?php
    namespace Model;
    use Exception;
    use Datetime;
    use DateTimeZone;
    class Contacto extends BienesRaicesDB
    {

        // Nombre de tabla y columnas
        protected static $table_name = "CONTACTO";
        protected static $columns = ["id","nombre","apellido","motivo_contacto","presupuesto","mensaje","preferencia_contacto","email","telefono","fecha","hora"];
        // Atributos de objeto
        protected $id;
        protected $nombre;
        protected $apellido;
        protected $motivo_contacto;
        protected $presupuesto;
        protected $mensaje;
        protected $preferencia_contacto;
        protected $email;
        protected $telefono;
        protected $fecha;
        protected $hora;

        public function __construct($args=[])
        {
            $this->id = $args["id"] ?? "";
            $this->nombre = $args["nombre"] ?? "";
            $this->apellido = $args["apellido"] ?? "";
            $this->motivo_contacto = $args["motivo_contacto"] ?? "";
            $this->presupuesto = $args["presupuesto"] ?? "";
            $this->mensaje = $args["mensaje"] ?? "";
            $this->preferencia_contacto = $args["preferencia_contacto"] ?? "";
            $this->email = $args["email"] ?? "";
            $this->telefono = $args["telefono"] ?? "";
            $this->fecha = $args["fecha"] ?? "";
            $this->hora = $args["hora"] ?? "";
        }

        // Getters y setters

        public function get_id()
        {
            return htmlspecialchars($this->id);
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
        public function get_motivo_contacto()
        {
            return htmlspecialchars($this->motivo_contacto);
        }
        public function set_motivo_contacto($new_motivo_contacto)
        {
            $this->motivo_contacto = $new_motivo_contacto;
        }
        public function get_presupuesto()
        {
            return htmlspecialchars($this->presupuesto);
        }
        public function set_presupuesto($new_presupuesto)
        {
            $this->presupuesto = $new_presupuesto;
        }
        public function get_mensaje()
        {
            return htmlspecialchars($this->mensaje);
        }
        public function set_mensaje($new_mensaje)
        {
            $this->mensaje = $new_mensaje;
        }
        public function get_preferencia_contacto()
        {
            return htmlspecialchars($this->preferencia_contacto);
        }
        public function set_preferencia_contacto($new_preferencia_contacto)
        {
            $this->preferencia_contacto = $new_preferencia_contacto;
        }
        public function get_email()
        {
            return htmlspecialchars($this->email);
        }
        public function set_email($new_email)
        {
            $this->email = $new_email;
        }
        public function get_telefono()
        {
            return htmlspecialchars($this->telefono);
        }
        public function set_telefono($new_telefono)
        {
            $this->telefono = $new_telefono;
        }
        public function get_fecha()
        {
            return $this->fecha;
        }
        public function set_fecha($new_fecha)
        {
            $this->fecha = $new_fecha;
        }
        public function get_hora()
        {
            return $this->hora;
        }
        public function set_hora($new_hora)
        {
            $this->hora = $new_hora;
        }

        // Validamos los datos, y en caso de que haya error guardamos la alerta y devolvemos el número de error. Si devolvemos 0, es que no hay ninguno
        public function validate_data(?string $timezone, ?string $prefijo): int
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
            if(!in_array($this->motivo_contacto,["Compra","Vende"]))
            {
                KO_alert(["en"=>"Select why you hace chosen us from the list","es"=>"Seleccione el motivo de su contacto de la lista"][$lang]);
                return 3;
            }
            if(!preg_match("/^\d+$/",$this->presupuesto))
            {
                KO_alert(["en"=>"The budget must be an integer number","es"=>"El presupuesto debe ser un número entero"][$lang]);
                return 4;
            }
            if(!$this->mensaje)
            {
                KO_alert(["en"=>"Explain why you are contacting us","es"=>"Explique porque ha elegido contactarnos"][$lang]);
                return 5;
            }
            switch($this->preferencia_contacto)
            {
                case "telefono":
                    if(!$timezone)
                    {
                        KO_alert(["en"=>"Select your location (approximately). We need to know your time zone to be able to call you at the selected time","es"=>"Seleccione su ubicación (de forma aproximada). Necesitamos conocer su zona horaria para poder llamarle a la hora seleccionada"][$lang]);
                        return 7;
                    }
                    if(!$prefijo)
                    {
                        KO_alert(["en"=>"Choose your international phone prefix","es"=>"Eliga su prefijo telefónico internacional"][$lang]);
                        return 8;
                    }
                    // Comprobamos que el número sin el prefijo
                    if(!preg_match("/^\d+$/",substr($this->telefono,strlen($prefijo))))
                    {
                        KO_alert(["en"=>"Introduce a valid phone number","es"=>"Introduzca un número de teléfono válido"][$lang]);
                        return 9;
                    }
                    // Cogemos el momento exacto del registro en hora local del usuario, ya que sus inputs son en hora local y por tanto las comparaciones deben ser en hora local.
                    $now = new Datetime("now", new DateTimeZone($timezone)); 
                    $now_unix_time = $now->getTimestamp(); 
                    if(!$this->fecha) // Empezamos verificando que se introdujo una fecha
                    {
                        KO_alert(["en"=>"Select a valid date to be contacted. We do not work on weekends","es"=>"Seleccione una fecha válida para que le llamemos. No trabajamos los fines de semana."][$lang]);
                        return 10;
                    }
                    // Seleccionamos el final del día para comprobar que la fecha no es de un día pasado.
                    $fecha = new Datetime($this->fecha." 23:59", new DateTimeZone($timezone)); 
                    $fecha_unix_time = $fecha->getTimestamp();
                    if(date("N",$fecha_unix_time)>=6 || $fecha_unix_time<$now_unix_time) // Verificamos que no es fin de semana y no es el pasado
                    {
                        KO_alert(["en"=>"Select a valid date to be contacted. We do not work on weekends","es"=>"Seleccione una fecha válida para que le llamemos. No trabajamos los fines de semana."][$lang]);
                        return 10;
                    }
                    // Una vez que hemos pasado la comprobación del día, miramos la cita completa
                    if(!$this->hora) // Miramos primero que la hora esté seleccionada
                    {
                        KO_alert(["en"=>"Select a valid time to be contacted","es"=>"Seleccione una hora válida para que le llamemos"][$lang]);
                        return 11;
                    }
                    $cita = new DateTime($this->fecha." ".$this->hora, new DateTimeZone($timezone));
                    $cita_unix_time = $cita->getTimestamp();
                    if($cita_unix_time<$now_unix_time) // Comprobamos que no sea el pasado
                    {
                        KO_alert(["en"=>"Select a valid time to be contacted","es"=>"Seleccione una hora válida para que le llamemos"][$lang]);
                        return 11;
                    }
                    break;
                case "email":
                    if(!filter_var($this->email,FILTER_VALIDATE_EMAIL))
                    {
                        KO_alert(["en"=>"Introduce a valid e-mail","es"=>"Introduzca un e-mail válido"][$lang]);
                        return 12;
                    }
                    // En el caso de que estemos en email, hay que convertir en null estos dos atributos. Esto es porque el POST siempre va a traer un string vacío y en la base de datos no podemos meter como fecha u hora un string vacío, pero si null.
                    $this->fecha = null;
                    $this->hora = null;
                    break;
                default:
                    KO_alert(["en"=>"Select a contact method within the proposed ones","es"=>"Seleccione un método de contacto de entre los propuestos."][$lang]);
                    return 6;
                    break;
            }
            // Si no hay problemas, ayudamos a la base de datos con la conversión de los datos de tipo entero, y convertimos la fecha en hora UTC para el almacenamiento.
            $this->presupuesto = intval($this->presupuesto);
            if($cita??null)
            {
                $cita = $cita->setTimezone(new DateTimeZone("UTC"));
                $this->fecha = $cita->format("Y-m-d");
                $this->hora = $cita->format("H:i");
            }
            return 0;
        }

        protected static function newObject($row)
        {
            return new self($row);
        }

        // Reescritura de los métodos del CRUD para ajustarlos a la clase Contacto
        public function insert():bool
        {
            $lang = $_SESSION["lang"];
            try
            {
                parent::insert();
                OK_alert(["en"=>"We have received correctly your contact request","es"=>"Su solicitud de contacto ha sido registrada correctamente"][$lang]);
                return true;
            }
            catch(Exception)
            {     
                KO_alert(["en"=>"There was an issue with your request. Try it again in a few minutes","es"=>"Hubo un problema con su solicitud, inténtelo de nuevo en unos minutos"][$lang]);;
                return false;
            }
        }

        public function delete()
        {
            $lang = $_SESSION["lang"];
            try
            {
                parent::delete();
            }
            catch(Exception)
            {
                KO_alert(["en"=>"There was an error deleting the contact request. Try it again in a few minutes or contact to support","es"=>"Error en la supresión de la solicitud de contacto. Inténtelo de nuevo en unos minutos o contacte con soporte"][$lang]);
            }
        }
    }
?>