<?php
    namespace Controllers;

    use DateTime;
    use DateTimeZone;
    use MVC\Router;
    use Model\Propiedad;
    use Model\Vendedor;
    use Model\Contacto;
    use Model\Blog;
    if(!isset($_SESSION))
    {
        session_start();
    }

    class AdminController
    {
        public static function index()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Admin","es"=>"Administrador"];
            $id_vendedor = intval($_GET["id_vendedor"] ?? 0);
            $vendedores = Vendedor::all(); // Recuperamos los vendedores para la lista de vendedores
            if($id_vendedor>0)
            {
                $anuncios = Propiedad::find("id_vendedor",$id_vendedor); // Seleccionamos solo los anuncios del vendedor
                
                $vendedor_activo = Vendedor::find("id",$id_vendedor)[0]; // Seleccionamos al vendedor para mostrar en el menú que es la opción seleccionada
                break_and_redirect("/".$lang."/admin",$vendedor_activo);
            }
            else
            {
                $anuncios = Propiedad::all();
                $vendedor_activo = null;
            }
            // Recuperamos y limpiamos alertas si son necesarias
            $OK_alert = $_SESSION["OK_alert"] ?? null;
            $KO_alert = $_SESSION["KO_alert"] ?? null;
            unset($_SESSION["OK_alert"]);
            unset($_SESSION["KO_alert"]);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang], 
                "anuncios"=>$anuncios,
                "vendedores"=>$vendedores,
                "vendedor_activo"=>$vendedor_activo,
                "id_vendedor"=>$id_vendedor,
                "OK_alert"=>$OK_alert,
                "KO_alert"=>$KO_alert,
                "lang"=>$lang,
                "route"=>"/admin".($id_vendedor>0 ? "?id=$id_vendedor" : "")
            ];
            Router::render("admin/index",$data);
        }

        public static function crear_anuncio_GET()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"New ad","es"=>"Crear Anuncio"];
            $pages = ["en"=>"Create a new add", "es"=>"Crear un nuevo anuncio"];
            $propiedad = new Propiedad;
            $vendedores = Vendedor::all(); 
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Crear un nuevo anuncio",
                "propiedad"=>$propiedad,
                "vendedores"=>$vendedores,
                "KO_alert"=>null,
                "error"=>null,
                "page"=>$pages[$lang],
                "boton_text"=>$titles[$lang],
                "lang"=>$lang,
                "route"=>"/admin/anuncio/crear"
            ];
            Router::render("admin/gestor_anuncios",$data);
        }

        public static function crear_anuncio_POST()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"New ad","es"=>"Crear Anuncio"];
            $propiedad = new Propiedad($_FILES["imagen"],$_POST["posted"]);
            $pages = ["en"=>"Create a new add", "es"=>"Crear un nuevo anuncio"];
            $error = $propiedad->validate_data();
            if(!$error)
            {
                $inserted = $propiedad->insert();
                break_and_redirect("/".$lang."/admin",!$inserted);
            }
            $vendedores = Vendedor::all();
            $KO_alert = $_SESSION["KO_alert"]; //Si llegamos aquí y es porque hay errores
            unset($_SESSION["KO_alert"]);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Crear un nuevo anuncio",
                "propiedad"=>$propiedad,
                "vendedores"=>$vendedores,
                "KO_alert"=>$KO_alert,
                "page"=>$pages[$lang],
                "error"=>$error,
                "boton_text"=>$titles[$lang],
                "lang"=>$lang,
                "route"=>"/admin/anuncio/crear"
            ];
            Router::render("admin/gestor_anuncios",$data);
        }

        public static function actualizar_anuncio_GET()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Update ad","es"=>"Actualizar Anuncio"];
            $pages = ["en"=>"Update the add", "es"=>"Actualizar el anuncio"];
            $id = $_GET["id"] ?? 0;
            $propiedad = Propiedad::find("id",$id)[0];
            break_and_redirect("/".$lang."/admin",$propiedad);
            $vendedores = Vendedor::all(); 
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Actualizar el anuncio",
                "propiedad"=>$propiedad,
                "vendedores"=>$vendedores,
                "KO_alert"=>null,
                "error"=>null,
                "page"=>$pages[$lang],
                "boton_text"=>$titles[$lang],
                "lang"=>$lang,
                "route"=>"/admin/anuncio/actualizar?id=".$id
            ];
            Router::render("admin/gestor_anuncios",$data);
        }

        public static function actualizar_anuncio_POST()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Update ad","es"=>"Actualizar Anuncio"];
            $pages = ["en"=>"Update the add", "es"=>"Actualizar el anuncio"];
            $id = $_GET["id"] ?? 0;
            $propiedad = Propiedad::find("id",$id)[0];
            break_and_redirect("/".$lang."/admin",$propiedad);
            if($_FILES["imagen"]["name"]) // Sincronizamos la imagen si se ha cargado
            {
                $propiedad->set_imagen($_FILES["imagen"]);
            }
            $propiedad->sync($_POST["posted"]); // Sincronizamos los campos por teclado
            $propiedad->set_fecha_publicacion(); // Actualizamos a fecha de hoy
            $error = $propiedad->validate_data();
            if(!$error)
            {
                $updated = $propiedad->update();
                break_and_redirect("/".$lang."/admin",!$updated);
            }
            $vendedores = Vendedor::all();
            $KO_alert = $_SESSION["KO_alert"]; //Si llegamos aquí y es porque hay errores
            unset($_SESSION["KO_alert"]);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Actualizar el nuevo anuncio",
                "propiedad"=>$propiedad,
                "vendedores"=>$vendedores,
                "KO_alert"=>$KO_alert,
                "page"=>$pages[$lang],
                "error"=>$error,
                "boton_text"=>$titles[$lang],
                "lang"=>$lang,
                "route"=>"/admin/anuncio/actualizar?id=".$id
            ];
            Router::render("admin/gestor_anuncios",$data);
        }

        public static function borrar_anuncio()
        {
            $lang = $_SESSION["lang"];
            $id = intval($_GET["id"] ?? 0);
            $propiedad = Propiedad::find("id",$id)[0]; // Recuperamos la propiedad por el id
            break_and_redirect("/".$lang."/admin",$propiedad);
            $propiedad->delete();
            break_and_redirect("/".$lang."/admin");
        }

        public static function gestor_vendedores_GET()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Manage sellers","es"=>"Gestionar vendedores"];
            $id = $_GET["id"] ?? 0;
            $vendedores = Vendedor::all();
            $nuevo_vendedor = new Vendedor;
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "id"=>$id,
                "vendedores"=>$vendedores,
                "nuevo_vendedor"=>$nuevo_vendedor,
                "KO_alert"=>null,
                "submit_type"=>null,
                "lang"=>$lang,
                "route"=>"/admin/vendedores"
            ];
            Router::render("admin/gestor_vendedores",$data);
        }

        public static function gestor_vendedores_POST()
        {   
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Manage sellers","es"=>"Gestionar vendedores"];
            $id = $_GET["id"] ?? 0;
            $submit_type = $_POST["submit_type"]??null;
            $nuevo_vendedor = new Vendedor;
            $vendedor_to_update = null;
            switch($submit_type)
            {
                case ["en"=>"Create","es"=>"Crear"][$lang]:
                    $nuevo_vendedor = new Vendedor($_POST["posted"]);
                    $error = $nuevo_vendedor->validate_data();
                    if(!$error)
                    {
                        $inserted = $nuevo_vendedor->insert();
                        break_and_redirect("/".$lang."/admin",!$inserted);
                    }
                    break;
                case ["en"=>"Update","es"=>"Actualizar"][$lang]:
                    $vendedor_to_update = Vendedor::find("id",$id)[0];
                    break_and_redirect("/".$lang."/admin",$vendedor_to_update);
                    $vendedor_to_update->sync($_POST["posted"]); // Sincronizamos los campos por teclado
                    $error = $vendedor_to_update->validate_data(); // Validamos que los datos sean correctos 
                    if(!$error) //Si no hay errores, actualizamos en la base de datos
                    {   
                        $updated = $vendedor_to_update->update();
                        break_and_redirect("/".$lang."/admin",!$updated);
                    }
                    break;
                case ["en"=>"Delete","es"=>"Eliminar"][$lang]:
                    $vendedor = Vendedor::find("id",$id)[0];
                    break_and_redirect("/".$lang."/admin",$vendedor);
                    $vendedor->delete();
                    break_and_redirect("/".$lang."/admin",$_SESSION["KO_alert"]);
                    break;
                default:
                    // Si no se marca ninguno de los valores, no hacemos nada y redireccionamos
                    break_and_redirect("/".$lang."/admin");
            }
            $vendedores = Vendedor::all();
            $KO_alert = $_SESSION["KO_alert"];
            unset($_SESSION["KO_alert"]);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "id"=>$id,
                "vendedores"=>$vendedores,
                "nuevo_vendedor"=>$nuevo_vendedor,
                "vendedor_to_update"=>$vendedor_to_update,
                "KO_alert"=>$KO_alert,
                "error"=>$error,
                "submit_type"=>$submit_type,
                "lang"=>$lang,
                "route"=>"/admin/vendedores".($vendedor_to_update ? "?id=$id" : "")
            ];
            Router::render("admin/gestor_vendedores",$data);
        }

        public static function solicitudes_GET()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Contact requests","es"=>"Solicitudes de contacto"];
            $solicitudes = Contacto::all();
            $office_timezone = $_SESSION["office_timezone"]; // Siempre está definido como admin, ya que al autenticar la añadimos a la sesión                        
            foreach($solicitudes as $solicitud)
            {
                if($solicitud->get_preferencia_contacto()==="telefono") // Si ha pedido cita por teléfono, le mostramos al admin la cita en su hora local. Fecha y hora siempre están definidos porque sino el registro no entraría en la base de datos
                {
                    $unix_time_cita = new DateTime($solicitud->get_fecha()." ".$solicitud->get_hora(),new DateTimeZone("UTC"));
                    $office_time_cita = $unix_time_cita->setTimezone(new DateTimeZone($office_timezone));
                    $solicitud->set_fecha($office_time_cita->format("Y-m-d"));
                    $solicitud->set_hora($office_time_cita->format("H:i"));
                }
            }
            $KO_alert = $_SESSION["KO_alert"] ?? null;
            unset($_SESSION["KO_alert"]);
            $data = 
            [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "solicitudes"=>$solicitudes,
                "KO_alert"=>$KO_alert,
                "lang"=>$lang,
                "route"=>"/admin/solicitudes"
            ];
            Router::render("admin/solicitudes",$data);
        }

        public static function solicitudes_POST()
        {
            // Recuperamos del JSON enviado por Fetch API el ID de la solicitud a eliminar
            $jsonData = file_get_contents("php://input");
            $id = json_decode($jsonData, true)["id"];
            $solicitud = Contacto::find("id",$id)[0]; // recuperamos de la base de datos la solicitud
            $solicitud->delete(); // Y eliminamos la solicitud. Ya se encarga fetch API de redireccionar.    
        }

        public static function admin_blog()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Blog manager","es"=>"Administrador del blog"];
            $entradas = Blog::all();
            $OK_alert = $_SESSION["OK_alert"] ?? null;
            unset($_SESSION["OK_alert"]);
            $KO_alert = $_SESSION["KO_alert"] ?? null;
            unset($_SESSION["KO_alert"]);
            $data = 
            [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "entradas"=>$entradas,
                "OK_alert"=>$OK_alert,
                "KO_alert"=>$KO_alert,
                "lang"=>$lang,
                "route"=>"/admin/blog"
            ];
            Router::render("admin/admin_blog",$data);
        }

        public static function crear_blog_GET()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Create blog entry","es"=>"Crear entrada de blog"];
            $pages = ["en"=>"Create a new entry", "es"=>"Crear una nueva entrada"];
            $buttons = ["en"=>"Create entry","es"=>"Crear entrada"];
            $entrada = new Blog;
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Crear una entrada",
                "entrada"=>$entrada,
                "KO_alert"=>null,
                "error"=>null,
                "page"=>$pages[$lang],
                "boton_text"=>$buttons[$lang],
                "lang"=>$lang,
                "route"=>"/admin/blog/crear"
            ];
            Router::render("admin/gestor_blog",$data);
        }

        public static function crear_blog_POST()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Create blog entry","es"=>"Crear entrada de blog"];
            $pages = ["en"=>"Create a new entry", "es"=>"Crear una nueva entrada"];
            $buttons = ["en"=>"Create entry","es"=>"Crear entrada"];
            $entrada = new Blog($_FILES["imagen"],$_POST["posted"]);
            $error = $entrada->validate_data();
            if(!$error)
            {
                $inserted = $entrada->insert();
                break_and_redirect("/".$lang."/admin/blog",!$inserted);
            }
            $KO_alert = $_SESSION["KO_alert"]; //Si llegamos aquí y es porque hay errores
            unset($_SESSION["KO_alert"]);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Crear una entrada",
                "entrada"=>$entrada,
                "KO_alert"=>$KO_alert,
                "page"=>$pages[$lang],
                "error"=>$error,
                "boton_text"=>$buttons[$lang],
                "lang"=>$lang,
                "route"=>"/admin/blog/crear"
            ];
            Router::render("admin/gestor_blog",$data);
        }

        public static function actualizar_blog_GET()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Update blog entry","es"=>"Actualizar entrada de blog"];
            $pages = ["en"=>"Update entry", "es"=>"Actualizar la entrada"];
            $buttons = ["en"=>"Update entry","es"=>"Actualizar entrada"];
            $id = $_GET["id"] ?? 0;
            $entrada= Blog::find("id",$id)[0];
            break_and_redirect("/".$lang."/admin",$entrada);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Actualizar entrada",
                "entrada"=>$entrada,
                "KO_alert"=>null,
                "page"=>$pages[$lang],
                "error"=>null,
                "boton_text"=>$buttons[$lang],
                "lang"=>$lang,
                "route"=>"/admin/blog/actualizar?id=".$id
            ];
            Router::render("admin/gestor_blog",$data);
        }

        public static function actualizar_blog_POST()
        {
            $lang = $_SESSION["lang"];
            $titles = ["en"=>"Update blog entry","es"=>"Actualizar entrada de blog"];
            $pages = ["en"=>"Update entry", "es"=>"Actualizar la entrada"];
            $buttons = ["en"=>"Update entry","es"=>"Actualizar entrada"];
            $id = $_GET["id"] ?? 0;
            $entrada =Blog::find("id",$id)[0];
            break_and_redirect("/".$lang."/admin",$entrada);
            if($_FILES["imagen"]["name"]) // Sincronizamos la imagen si se ha cargado
            {
                $entrada->set_imagen($_FILES["imagen"]);
            }
            $entrada->sync($_POST["posted"]); // Sincronizamos los campos por teclado
            $entrada->set_fecha();
            $error = $entrada->validate_data();
            if(!$error)
            {
                $updated = $entrada->update();
                break_and_redirect("/".$lang."/admin/blog",!$updated);
            }
            $KO_alert = $_SESSION["KO_alert"]; //Si llegamos aquí y es porque hay errores
            unset($_SESSION["KO_alert"]);
            $data = [
                "title"=>"Bienes Raíces - ".$titles[$lang],
                "page"=>"Actualizar entrada",
                "entrada"=>$entrada,
                "KO_alert"=>$KO_alert,
                "page"=>$pages[$lang],
                "error"=>$error,
                "boton_text"=>$buttons[$lang],
                "lang"=>$lang,
                "route"=>"/admin/blog/actualizar?id=".$id
            ];
            Router::render("admin/gestor_blog",$data);
        }

        public static function borrar_blog()
        {
            $lang = $_SESSION["lang"];
            $id = intval($_GET["id"] ?? 0);
            $entrada = Blog::find("id",$id)[0]; 
            break_and_redirect("/".$lang."/admin",$entrada);
            $entrada->delete();
            break_and_redirect("/".$lang."/admin/blog");
        }
    }
?>