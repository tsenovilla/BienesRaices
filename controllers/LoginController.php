<?php
    namespace Controllers;
    use MVC\Router;
    use Model\Usuario;
    if(!isset($_SESSION))
    {
        session_start();
    }
    class LoginController
    {
        public static function login_GET()
        {
            $lang = $_SESSION["lang"];
            $data = 
            [
                "title"=>"Bienes Raíces - Login",
                "usuario"=>new Usuario,
                "alert"=>null,
                "error"=>null,
                "lang"=>$lang,
                "route"=>"/login"
            ];
            Router::render("login",$data);
        }

        public static function login_POST()
        {
            $lang = $_SESSION["lang"];
            $usuario = new Usuario($_POST);            
            $error = $usuario->validate_data();
            if(!$error)
            {
                $_SESSION["auth"] = true;
                $_SESSION["start"] = time();
                // Para recuperar el timezone de la oficina registrada, debemos recuperar de la base de datos
                $usuario = Usuario::find("email",$usuario->get_email())[0];
                $_SESSION["office_timezone"] = $usuario->get_office_timezone();
                break_and_redirect("/".$lang."/admin");
            }
            $alert = $_SESSION["KO_alert"];
            unset($_SESSION["KO_alert"]);
            $data = 
            [
                "title"=>"Bienes Raíces - Login",
                "usuario"=>$usuario,
                "alert"=>$alert,
                "error"=>$error,
                "lang"=>$lang,
                "route"=>"/login"
            ];
            Router::render("login",$data);
        }

        public static function logout()
        {
            $lang = $_SESSION["lang"];
            $_SESSION = [];
            break_and_redirect("/$lang/");
        }
    }
