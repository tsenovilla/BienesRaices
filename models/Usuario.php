<?php

    namespace Model;

    class Usuario extends BienesRaicesDB
    {
        // Nombre de tabla y columnas
        protected static $table_name = "USUARIOS";
        protected static $columns = ["id","email","password","office_timezone"];

        // Atributos de objeto
        protected $id;
        protected $email;
        protected $password;
        protected $office_timezone;

        public function __construct($args=[])
        {
            $this->id = $args["id"] ?? ""; 
            $this->email = $args["email"] ?? ""; 
            $this->password = $args["password"] ?? ""; 
            $this->office_timezone = $args["office_timezone"] ?? "";
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
        public function get_email()
        {
            return htmlspecialchars($this->email);
        }
        public function set_email($new_email)
        {
            $this->email = $new_email;
        }
        public function get_password()
        {
            return htmlspecialchars($this->password);
        }
        public function set_password($new_password)
        {
            $this->password = $new_password;
        }
        public function get_office_timezone()
        {
            return htmlspecialchars($this->office_timezone);
        }
        public function set_office_timezone($new_office_timezone)
        {
            $this->office_timezone = $new_office_timezone;
        }

        // Esta función sirve para verificar que el usuario y password son correctos.
        public function validate_data():int
        {
            $lang = $_SESSION["lang"];
            $admin_registrado = self::find("email",$this->email);
            if(!$admin_registrado){
                KO_alert(["en"=>"Introduce a valid admin e-mail","es"=>"Introduzca un email correspondiente a un administrador"][$lang]);
                return 1;
            }
            else
            {
                if(!password_verify($this->password,$admin_registrado[0]->get_password()))
                {
                    KO_alert(["en"=>"The password is not correct","es"=>"La contraseña no es correcta"][$lang]);
                    return 2;
                }
            }
            return 0;
        }

        protected static function newObject($row)
        {
            return new self($row);
        }
    }