<?php

class ActionController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new ActionDAO();
        $this->_dialog = new Message();
        $this->_dialog->setTitle("Alerta");
    }
    function index(): object
    {
        //$this->authenticate("admtable");
        
        $model = (object)$this->_dao->list();
        return $this->view($model);
    }
    
    function insert(): object
    {
        //$this->authenticate("admtable");
        
        $action = new Action();        
        $validator = ActionValidator::instance($action);
        $action->setId(0); 
        
        if ($_POST)
        {
            //getting values from form
            $this->getFromForm($action);
                       
            if ($validator->validate($action))
            {
                if (!$this->contains($action))
                {
                    try
                    {
                        $this->save($action, true);
                    }
                    catch(Exception $e)
                    {
                        $this->_dialog->setMessage($e->getMessage());
                    }
                }
                else
                {
                    $this->_dialog->setMessage("O nome '{$action->getName()}' já existe.");  
                }
            }
            else
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "action" => $action);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        try
        {
            $action = $this->_dao->get($id);
            
            if ($action == null)
            {
                $this->action("Action");
            }
            
            if ($_POST)
            {
                $update = new Action();
                $update->setId($action->getId());
                $this->getFromForm($update);

                $validator = ActionValidator::instance($update);
                if ($validator->validate($update))
                {
                    if (!$this->contains($update))
                    {
                        $this->save($update, false);
                    }
                    else
                    {
                        $this->_dialog->setMessage("O nome '{$update->getName()}' já existe.");
                    }
                }
                else
                {
                    $this->_dialog->setMessage($validator->get());
                }
                $action = $update;
            }
        }
        catch(Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        
        $model = (object) array("message" => $this->_dialog->render(), "action" => $action);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        if ($id == 0) 
        { 
            $this->action("Action");          
        }
        
        try
        {
            $action = $this->_dao->get($id);   
            if ($action == null)
            {
                $this->action("Error", "show", "error=" . $this->_dialog->render());
            }  

            if ($_POST)
            {
                $this->_dao->delete($id);
                $this->action("Action");
            }
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        
        $model = (object) array("message" => $this->_dialog->render(), "action" => $action);
        return $this->view($model);         
    }
      
    private function save(Action $action, bool $new): void
    {      
        if ($new)
        {               
            $this->_dao->insert($action);
        }
        else
        {
            $this->_dao->update($action);
        }
        $this->action("Action");     
    }
    
    private function getFromForm(Action &$action): void
    {
       //getting values from form
        if ($_POST)
        {
            $action->setName(trim($_POST["txtnmaction"]));
            $action->setDescription(trim($_POST["txtdsaction"]));  
        }
    }
    
    private function contains(Action $compare) : bool
    { 
        $ret = false;     
        $search = $this->_dao->getByName($compare->getName());

        if ($search)
        {
            $ret = ($search->getId() != $compare->getId() ? true : false);              
        }
        return $ret;
    }
}
