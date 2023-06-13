<?php

    namespace MVC;

    use Controllers\AdminController;
    use Controllers\LoginController;
    use Controllers\UserController;
    use Model\BienesRaicesDB;
    if(!isset($_SESSION))
    {
        session_start();
    }

    class Router
    {
        // Aquí almacenamos los idiomas admitidos, que serán incluidos en todas las rutas
        protected static $languages = ["es","en"];
        protected static $GET_routes = [
            "/"=>[UserController::class, "index"],
            "/nosotros"=>[UserController::class, "nosotros"],
            "/anuncios"=>[UserController::class, "anuncios"],
            "/anuncio"=>[UserController::class, "anuncio"],
            "/blog"=>[UserController::class, "blog"],
            "/entrada"=>[UserController::class, "entrada"],
            "/contacto"=>[UserController::class, "contacto_GET"],
            "/login"=>[LoginController::class, "login_GET"],
            "/logout"=>[LoginController::class, "logout"],
            "/admin"=>[AdminController::class,"index"],
            "/admin/anuncio/crear"=>[AdminController::class,"crear_anuncio_GET"],
            "/admin/anuncio/actualizar"=>[AdminController::class,"actualizar_anuncio_GET"],
            "/admin/anuncio/borrar"=>[AdminController::class,"borrar_anuncio"],
            "/admin/blog"=>[AdminController::class,"admin_blog"],
            "/admin/blog/crear"=>[AdminController::class,"crear_blog_GET"],
            "/admin/blog/actualizar"=>[AdminController::class,"actualizar_blog_GET"],
            "/admin/blog/borrar"=>[AdminController::class,"borrar_blog"],
            "/admin/vendedores"=>[AdminController::class,"gestor_vendedores_GET"],
            "/admin/solicitudes"=>[AdminController::class,"solicitudes_GET"]
        ];
        protected static $POST_routes = [
            "/contacto"=>[UserController::class, "contacto_POST"],
            "/login"=>[LoginController::class, "login_POST"],
            "/admin/anuncio/crear"=>[AdminController::class,"crear_anuncio_POST"],
            "/admin/anuncio/actualizar"=>[AdminController::class,"actualizar_anuncio_POST"],
            "/admin/blog/crear"=>[AdminController::class,"crear_blog_POST"],
            "/admin/blog/actualizar"=>[AdminController::class,"actualizar_blog_POST"],
            "/admin/vendedores"=>[AdminController::class,"gestor_vendedores_POST"],
            "/admin/solicitudes"=>[AdminController::class,"solicitudes_POST"]
        ];

        // Rutas de administrador
        protected static $admin_routes = [
            "/admin", 
            "/admin/anuncio/crear",
            "/admin/anuncio/actualizar",
            "/admin/anuncio/borrar",
            "/admin/vendedores",
            "/admin/blog",
            "/admin/blog/crear",
            "/admin/blog/actualizar",
            "/admin/blog/borrar",
            "/logout",
            "/admin/solicitudes"
        ];

        public static function checkRoute()
        {
            $path = $_SERVER["REQUEST_URI"];
            $path = substr($path,0,strpos($path,"?") ? strpos($path,"?") : null); // Quitamos los argumentos de GET del path, ya que la ruta definida no lleva estos argumentos. De esta forma, /url?x=y llama a la ruta definida /url, pero sigue teniendo definidos los argumentos GET para su uso. Si se mete info maliciosa después del ? no hay problema, en páginas que no tengan peligro para la BD simplemente se ignorará y en las que se maneja la BD los controladores se ocupan de que los datos pasados por GET sean de confianza  
            if ($path=="/"){ // Si llamamos al index sin idioma, cargamos el index en inglés
                break_and_redirect("/en/");
            }  
            preg_match("/^\/[a-z]+\//",$path,$lang); // Separamos el idioma del path. Ojo, así devolvemos un array y nos llevamos también las dos /, así que hay que filtrarlo
            $lang = substr($lang[0]??"",1,-1); // Recuperamos el idioma
            $route = substr($path,strlen($lang)+1); // Recuperamos la ruta del path
            if(!in_array($lang,self::$languages)) 
            {
                    include __DIR__."/views/404.html";
                    exit;
            }
            if(in_array($route,self::$admin_routes))
            {
                if(!$_SESSION["auth"])
                {
                    $_SESSION = [];
                    break_and_redirect("/$lang/");
                }
                if((time()-$_SESSION["start"])/60>20) // Expirado de la sesión tras 20 minutos
                {
                    $_SESSION["expired"] = true;
                    break_and_redirect("/$lang/");
                }
            }
            $method = $_SERVER["REQUEST_METHOD"];
            if($method == "GET")
            {
                $controller = self::$GET_routes[$route] ?? null;   
            }
            else
            {
                $controller = self::$POST_routes[$route] ?? null;
            }
            
            if($controller)
            {
                $_SESSION["lang"] = $lang;
                call_user_func($controller);
            }
            else
            {
                include __DIR__."/views/404.html";
            }
        }

        public static function render($view, $data = [])
        {
            foreach($data as $key=>$value)
            {
                $$key=$value;
            }
            ob_start();
            include __DIR__."/views/$view.php";
            $page_content = ob_get_clean(); // Limpiamos el buffer y guardamos la vista en la variable que usa el layout
            include __DIR__."/views/layout.php";
            // Cerramos la conexión.
            BienesRaicesDB::closeDB();
        }
    }