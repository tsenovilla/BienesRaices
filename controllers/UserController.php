<?php
    namespace Controllers;
    use MVC\Router;
    use Model\Propiedad;
    use Model\Vendedor;
    use Model\Contacto;
    use Model\Blog;
    use PHPMailer\PHPMailer\PHPMailer;
    if(!isset($_SESSION))
    {
        session_start();
    }

    class UserController
    {
        public static function index()
        {
            $lang = $_SESSION["lang"];
            $expired = $_SESSION["expired"] ?? null;
            
            if($expired) // Si la sesión ha expirado, lo indicamos
            {
                $alert_messages = ["es"=>"Su sesión ha expirado","en"=>"Your session has expired."];
                echo "<script>alert('".$alert_messages[$lang]."');</script>";
                $_SESSION = []; // Limpiamos completamente la sesión
            }
            $anuncios = Propiedad::get(3);
            $entradas = Blog::get(2);
            $data = [
                "title"=>"Bienes Raíces",
                "inicio"=>true,
                "anuncios"=>$anuncios,
                "entradas"=>$entradas,
                "lang"=>$lang,
                "route"=>"/"
            ];
            Router::render("index",$data);
        }

        public static function nosotros()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"About us","es"=>"Nosotros"];
            $data = 
            [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "lang"=>$lang,
                "route"=>"/nosotros"
            ];
            Router::render("nosotros",$data);
        }

        public static function anuncios()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"On sale","es"=>"Anuncios"];
            $anuncios = Propiedad::all();            
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "anuncios"=>$anuncios,
                "lang"=>$lang,
                "route"=>"/anuncios"
            ];
            Router::render("anuncios",$data);
        }

        public static function anuncio()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"On sale","es"=>"Anuncios"];
            $id = $_GET["id"] ?? 0;
            $anuncio = Propiedad::find("id",$id)[0];
            break_and_redirect("/$lang/",$anuncio);
            $vendedor = Vendedor::find("id",$anuncio->get_id_vendedor())[0];
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "anuncio"=>$anuncio,
                "vendedor"=>$vendedor,
                "lang"=>$lang,
                "route"=>"/anuncio?id=".$id
            ];
            Router::render("anuncio",$data);
        }

        public static function blog()
        {
            $lang = $_SESSION["lang"];
            $entradas = Blog::all();
            $data = [
                "title"=>"Bienes Raíces - Blog",
                "entradas"=>$entradas,
                "lang"=>$lang,
                "route"=>"/blog"
            ];
            Router::render("blog",$data);
        }

        public static function entrada()
        {
            $lang = $_SESSION["lang"];
            $id = intval($_GET["id"] ?? 0);
            $entrada = Blog::find("id",$id)[0];
            break_and_redirect("/$lang/",$entrada);
            $data = [
                "title"=>"Bienes Raíces - Blog",
                "entrada"=>$entrada,
                "lang"=>$lang,
                "route"=>"/entrada?id=".$id
            ];
            Router::render("entrada",$data);
        }

        // En el caso de contacto, como el POST se realiza via Fetch API, para poder mostrar mensajes de alerta, ya sea OK o KO, hay que redireccionar a GET. 
        public static function contacto_GET()
        {  
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Contact","es"=>"Contacto"];
            $OK_alert = $_SESSION["OK_alert"] ?? null;
            // Como el timezone y el prefijo no son parte del objeto que se inserta en la BD, no se incluyen en el objeto de contacto, y por tanto deben ser transmitidos a parte con la sesión. Importante borrarlos si ha habido un insert correcto a la base de datos, o si no está definido contacto (ie, no llegamos a esta URL como redireccionamiento de POST)
            if($OK_alert || !($_SESSION["contacto"] ?? null))
            {
                unset($_SESSION["timezone"]);
                unset($_SESSION["prefijo"]);
                unset($_SESSION["OK_alert"]);
            }
            $KO_alert = $_SESSION["KO_alert"] ?? null;
            unset($_SESSION["KO_alert"]);
            $error = intval($_SESSION["error"] ?? 0);
            unset($_SESSION["error"]);
            $contacto = $_SESSION["contacto"] ?? new Contacto;
            unset($_SESSION["contacto"]); 
            $timezone = $_SESSION["timezone"] ?? null;          
            $prefijo = $_SESSION["prefijo"] ?? "";                      
            if($timezone)
            {
                $timezone = $timezone["abbreviation"]." (".$timezone["countryName"].")";
            }
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "contacto"=>$contacto,
                "error"=>$error,
                "timezone"=>$timezone ?? null,
                "prefijo"=>$prefijo,
                "OK_alert"=>$OK_alert,
                "KO_alert"=>$KO_alert,
                "lang"=>$lang,
                "route"=>"/contacto"
            ];
            Router::render("contacto",$data);
        }

        public static function contacto_POST()
        {   
            // El post de contacto viene por medio de Fetch API como un JSON, así que tenemos que leer los datos conforme a esta manera.
            // Recuperamos el JSON de Fetch API y lo decodificamos
            $jsonData = file_get_contents("php://input");
            $post = json_decode($jsonData, true);
            // El campo submit del post indica si el formulario se debe enviar. Hay dos variantes de POST en esta URL: 
            //  1. La que manda el formulario completo al servidor para ser validado e insertado en la base de datos.
            //  2. La variante que no envía el formulario se produce cuando el usuario selecciona una ubicación en el mapa. Fetch API llama mediante POST al servidor y le envía las coordenadas seleccionadas.El servidor llama a la API de TimeZoneDB para encontrar el país seleccionado, y lo devuelve a Fetch API. De esta manera, el cliente puede asignar automáticamente un prefijo telefónico al formulario, así como mostrar el nombre de la zona horaria seleccionada por pantalla
            if($post["submit"])
            {
                // Le asignamos el prefijo al teléfono, en caso de que llegue vacío no se asigna nada, pero el modelo de contacto controlará este error.
                $post["telefono"] = $post["prefijo"].$post["telefono"];
                // Recuperamos la zona horaria si se ha seleccionado un lugar en el mapa y la agregamos al post. Antes de eso, recuperamos la zona horaria de la sesión, ya que puede ser que el usuario la haya seleccionado anteriormente y esté registrada en la sesión.
                $timezone = $_SESSION["timezone"] ?? null;
                if($post["latitud"] ?? null)
                {
                    $timezone = self::get_timezone($post["latitud"],$post["longitud"]);
                }   
                $contacto = new Contacto($post);            
                $error = $contacto->validate_data($timezone["zoneName"] ?? null, $post["prefijo"]??null); // La validación del contacto necesita pasarle el timezone si se seleccionó zona horaria y el prefijo, para separar en la validación el teléfono del prefijo
                // Si no hay error, intentamos la inserción
                if(!$error)
                {
                    $insert_succeed = $contacto->insert();
                    if($insert_succeed)
                    {
                        if($contacto->get_preferencia_contacto()==="email")
                        {
                            self::send_mail($post);
                        }
                        exit;
                    }
                }
                // Si llegamos aquí, es que ha habido un error. Almacenamos el contacto, timezone y prefijo en la sesión para recuperarlo desde la página GET, y así poder mostrar el formulario tal como el usuario lo dejo
                // Recordemos que el teléfono lleva el prefijo, así que hay que quitarselo para que no se muestre en el formulario. No es problema ya que esta instancia de contacto no nos sirve para mucho más
                $contacto->set_telefono(substr($contacto->get_telefono(),strlen($post["prefijo"])));
                $_SESSION["contacto"] = $contacto;
                $_SESSION["error"] = $error;
                $_SESSION["timezone"] = $timezone ?? null;
                $_SESSION["prefijo"] = $post["prefijo"] ?? null;
            }
            else
            {
                // Envio a fetch API del nombre del país seleccionado y la abreviación del nombre de la zona horaria
                $timezone = self::get_timezone($post["latitud"],$post["longitud"]); 
                echo json_encode(["countryName"=>$timezone["countryName"],"abbreviation"=>$timezone["abbreviation"],"redirect"=>false]);
            }
        }

        /**
         * Esta función sirve para realizar el envío de mails confirmando que el contacto se recibió correctamente
         */
        private static function send_mail($posted)
        {   
            $lang = $_SESSION["lang"];
            foreach($posted as $key=>$value)
            {
                $$key=$value;
            }
            ob_start();
            include __DIR__."/../includes/templates/mail.php";
            $contenido = ob_get_clean();
            // Conexión al servidor de mails de mailtrap
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = $_ENV["PHP_MAILER_HOST"];
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = intval($_ENV["PHP_MAILER_PORT"]);
            $phpmailer->Username = $_ENV["PHP_MAILER_USERNAME"];
            $phpmailer->Password = $_ENV["PHP_MAILER_PASS"];
            $phpmailer->SMTPSecure = "tls";

            // Configuración del mail
            $phpmailer->setFrom("do-not-reply@bienesraices.com","Bienes Raíces"); 
            $phpmailer->addAddress($email);//Receiver
            $phpmailer->Subject=["en"=>"Contact request received","es"=>"Solicitud de contacto recibida correctamente."][$lang];
            $phpmailer->isHTML(true);
            $phpmailer->CharSet = "UTF-8";
            $phpmailer->Body = $contenido;
            $phpmailer->AltBody = ["en"=>"This message has been automatically sent, please, do not reply to it.\nHi $nombre!\nWe have received correctly your contact request. One of our agents will contact you as soon as possible.\nBest regards,\nThe team of Bienes Raíces", "es"=>"Este mensaje ha sido generado automáticamente, por favor no responda a esta dirección de correo.\n¡Hola $nombre!\nHemos recibido correctamente tu solicitud de contacto con nuestra empresa. A la mayor brevedad posible, uno de nuestros agentes se pondrá en contacto contigo.\nAtentamente,\nEl equipo de Bienes Raíces."][$lang];
            $phpmailer->send();
        }

        /**
         * Esta función sirve para recuperar el timezone del usuario mediante una llamada a la API de TimeZoneDB, en el caso de que se haya seleccionado un lugar
         */
        private static function get_timezone($latitud, $longitud)
        {
            $lang = $_SESSION["lang"];
            $apiKey = $_ENV["TIMEZONEDB_KEY"]; 
            // Construimos la URL de la API de TimeZoneDB con nuestra clave y el lugar del cual queremos obtener la información. 
            $url = "http://api.timezonedb.com/v2.1/get-time-zone?key=$apiKey&format=json&by=position&lat=$latitud&lng=$longitud";
            // La petición HTTP la haremos mediante cURL
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url); // Añadimos la URL
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Pedimos que la respuesta se recupere como cadena de texto en lugar de imprimirse
            // Realizamos la solicitud HTTP
            $response = curl_exec($curl);        
            // Verificamos que no haya errores
            if (curl_errno($curl)) {
                KO_alert(["en"=>"There was a problem with your request. Try it again in a few minutes or switch to email communication","es"=>"Hubo un problema procesando su solicitud. Íntentelo de nuevo en unos minutos o cambie el método de contacto a email."][$lang]);
            }
            // Cerramos la conexión cURL y recuperamos la información en un array asociativo
            curl_close($curl);
            $result = json_decode($response, true);  
            // Si la solicitud fue bien, devolvemos la zona horaria del usuario.
            if ($result['status'] == 'OK') 
            {
                return $result;
            } 
            else 
            {
                KO_alert(["en"=>"There was a problem with your request. Try it again in a few minutes or switch to email communication","es"=>"Hubo un problema procesando su solicitud. Íntentelo de nuevo en unos minutos o cambie el método de contacto a email."][$lang]);
            }
        }
    }
?>