<?php

class AccessController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new AccessDAO();
        $this->_dialog = new Message();
        $this->_dialog->setTitle("Alerta");
        $this->_dialog->setCloseButton(false);
    }
    
    function index(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        if ($id == 0) 
        {
            $this->action("Role");          
        }
        
        try
        {
            $dao = new RoleDAO();
            $role = $dao->get($id);
            if (!$role)
            {
                $this->action("Role");
            }
            $role = $this->_dao->list($role);
            
            $txtaction = "";
            
            if ($_POST)
            {
                $txtaction = $_POST["txtaction"]; 
                                
                $this->save($id, $txtaction);          
            }          
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        $model = (object)array("role" => $role, "txtaction"  => $txtaction, "message" => $this->_dialog->render());      
        return $this->view($model);
    }

    function delete(?int $id = 0) : object
    {
        //$this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Role");}
        
        $actionId = 0;
        if ($_GET)
        {
            if (key_exists("action", $_GET))
            {
                $actionId = $_GET["action"];
            }
        }
        if ($actionId == 0)
        { 
            $this->action("Access", "index", $id);
            
        }
        
        $action = $this->_dao->get($id, $actionId);
        
        if ($action)
        {
            $this->_dao->delete($id, $actionId);
        }
        $this->action("Access/index/{$id}");      
    }
    
    private function save(int $id, string $txtaction): void
    {
        $action = $this->searchAction($txtaction);
        if ($action)
        {
            if (!$this->_dao->get($id, $action->getId()))
            {
                $this->_dao->insert($id, $action->getId());
                $this->action("Access", "index", $id);
            }
            else
            {
                $this->_dialog->setCloseButton(true);
                $this->_dialog->setMessage("A Ação '{$action->getName()}' já esta associada a esse perfil!");
            }
        }           
    }
    
    private function searchAction($search) : ?Action
    {
        $action = null;
        $this->_dialog->setCloseButton(true);
        if (!empty($search))
        {
            //search role by id
            $dao = new ActionDAO();        
            $action = $dao->get($search);        
            if (!$action)
            {
                //search  role by name
                $action = $dao->getByName($search);
            }
            if (!$action) 
            {
                $this->_dialog->setMessage("Ação não localizada!");                    
            }
        }
        else
        {        
            $this->_dialog->setMessage("Este campo deve ser preenchido!");    
        }
        
        return $action;
    }
}
