<?php
class Controller
{
    private string $name = "";
    private string $action = "";
    private string $path = "";
    private ?object $model;
      
    function setName(string $name)
    {
        $this->name = $name;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function setAction(string $action)
    {
        $this->action = $action;
    }
    
    function getAction()
    {
        return $this->action;
    }

    
    function view(?object $model = null): object
    {
        $this->model = ($model != null ? $model : null);
        $this->path = Index::VIEW_DIR . $this->name . "/" . $this->action . ".phtml";
        
        $view = (object)array("path" => $this->path, "model" => $model);
        return $view;
    }
    
    function action($controller = "", $action = "", $id = "", $extras = ""): void
    {
        $url = "{$controller}";
        if (!empty($action))
        {
            $url .= "/{$action}";
        }
        
        if (!empty($id))
        {
            $url .= "/{$id}";
        }
        
        if (!empty($extras))
        {
           $url .= "?{$extras}";
        }
         header("Location: /{$url}");
         exit();
    }
    
    function renderize()
    {
        return $this->view;
    }
    
    function authenticate(?string $action = ""): void
    {
        if (Index::getCurrentUser() == null)
        {
            $this->action("Login", "login");
        }
        else
        {
            if (!Index::isAction($action))
            {
                $this->action("Login", "login");
            }
        }
    }
}
