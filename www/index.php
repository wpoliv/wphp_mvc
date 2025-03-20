<?php
final class Index
{
        const DEFAULT_TEMPLATE_NAME = "default";
        const DEFAULT_CONTROLLER_NAME = "Home";
        const DEFAULT_ACTION_NAME = "index";

        const CONTROLER_DIR = "../controllers/";
        const VIEW_DIR = "../views/";
        const TEMPLATE_DIR = "../templates/";
        const BASE_DIR = "../";

        //stores all classes from projects and its folder
        private static $CLASSES = array("Database" => "lib", 
            "Controller" => "lib",
            "Message" => "lib",
            "OrigemDAO" => "dao",
            "Origem" => "model",
            "OrigemValidator" => "validator",
            "CategoriaDAO" => "dao",
            "Categoria" => "model",
            "CategoriaValidator" => "validator",
            "FabricanteDAO" => "dao",
            "Fabricante" => "model",
            "FabricanteValidator" => "validator",
            "SituacaoDAO" => "dao",
            "Situacao" => "model",
            "SituacaoValidator" => "validator",
            "TipoDAO" => "dao",
            "Tipo" => "model",
            "TipoValidator" => "validator",
            "ModeloDAO" => "dao",
            "Modelo" => "model",
            "ModeloValidator" => "validator",
            "ActionDAO" => "dao",
            "Action" => "model/security",
            "ActionValidator" => "validator",
            "RoleDAO" => "dao",
            "Role" => "model/security",
            "RoleValidator" => "validator",
            "AccessDAO" => "dao",
            "ContactDAO" => "dao",
            "Contact" => "model",
            "ContactValidator" => "validator",
            "User" => "model/security",
            "UserDAO" => "dao",
            "Contact" => "model",
            "LoginViewModel" => "model",
            "UserViewModel" => "model",
            "LoginValidator" => "validator",
            "LoginViewModelValidator" => "validator",
            "UserViewModelValidator" => "validator");
    
    private static ?User $currentUser;
    
    public static function getCurrentUser(): ?User
    {
        self::$currentUser = null;
        if(key_exists("app_curuser", $_SESSION))
        {
            self::$currentUser = unserialize($_SESSION["app_curuser"]);
        }
        return self::$currentUser;
    }
    
    public static function setCurrentUser(User $user): void
    {
        $_SESSION["app_curuser"] = serialize($user);
    }

    public static function logout()
    {
        if (isset($_SESSION["app_curuser"]))
        {
            unset($_SESSION["app_curuser"]);
        }
        
    }

    public static function isAction(string $key): bool
    {
        foreach (self::$currentUser->getRole()->getActions() as $action)
        {
            if ($action->getName() == $key)
            {
                return true;
            }
        }
        return false;
    }
    
    function init(): void
    {
        mb_internal_encoding('UTF-8');
        //set_exception_handler([$this, 'handleException']);

        //create classes on instance create
        spl_autoload_register(function($name){
                if (!array_key_exists($name, self::$CLASSES)) {
                    die('Class "' . $name . '" not found.');
                }
                require_once self::BASE_DIR . self::$CLASSES[$name] . "/"  . $name . ".php";
            });
        session_start();    
        
    }
            
    function run()
    {
        $run = false;
        
        $router = $this->getRouter();
   
        $controller = $router->controller;
        $action = $router->action;
        $id = $router->id;
        
        $model = null;
        $template = self::DEFAULT_TEMPLATE_NAME;
        $class = "{$controller}Controller";
        $templatePath = self::TEMPLATE_DIR . "{$template}.phtml";       
        $content = ""; //content view file
        
        //execute script code from controller and its method 'action'
        $controllerPath = self::CONTROLER_DIR . "{$class}.php";
        if (file_exists($controllerPath))
        {
            require_once ($controllerPath);
            $newObject = new ($class);

            $newObject->setName($controller);
            $newObject->setAction($action);
            if (method_exists($newObject, $action)) 
            {
                $view = null;
                if (empty($id))
                {
                    $view = $newObject->$action();
                }
                else
                {
                    $view = $newObject->$action($id);
                }
                
                if ($view != null)
                {
                    $model = $view->model;
                    $content = $view->path;
                }
            }
            else
            {
                die("Action '{$action}' not found!");
            }
        }
        else
        {
            die("Controller: {$controller} not found!");
        }

        if (file_exists($content)) {$run = true;}

        if (file_exists($templatePath))
        {
            if ($run)
            {
                //renderize
                require ($templatePath);
            }
            else
            {
                die("View not found!");
            }
        }
        else
        {
            die("Template not found!");
        }
    }
    
    private function getRouter(): object
    {
        //patterns url: /{controller}/{action}/{id}
        $url = $_SERVER["REQUEST_URI"];
        $tmps = explode("?", $url);
        $router = explode("/", $tmps[0]);
        if (count($router) < 3) { $router[] = ""; }
        if (count($router) < 4) { $router[] = ""; }
        $array["controller"] = ($router[1] != "" ? $router[1] : self::DEFAULT_CONTROLLER_NAME);
        $array["action"] = ($router[2] != "" ? $router[2] : self::DEFAULT_ACTION_NAME);
        $array["id"] = ($router[3] != "" ? $router[3] : "");
        return (object)$array;
    }  
}

$page = new Index();
$page->init();
$page->run();