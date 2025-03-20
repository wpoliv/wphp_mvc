<?php

class RoleController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new RoleDAO();
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
        
        $role = new Role();        
        $validator = RoleValidator::instance($role);
        $role->setId(0); 
        
        if ($_POST)
        {
            //getting values from form
            $this->getFromForm($role);
                       
            if ($validator->validate($role))
            {
                if (!$this->contains($role))
                {
                    try
                    {
                        $this->save($role, true);
                    }
                    catch(Exception $e)
                    {
                        $this->_dialog->setMessage($e->getMessage());
                    }
                }
                else
                {
                    $this->_dialog->setMessage("O nome '{$role->getName()}' já existe.");  
                }
            }
            else
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "role" => $role);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        try
        {
            $role = $this->_dao->get($id);
            
            if ($role == null)
            {
                $this->action("Role");
            }
            
            if ($_POST)
            {
                $update = new Role();
                $update->setId($role->getId());
                $this->getFromForm($update);

                $validator = RoleValidator::instance($update);
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
                $role = $update;
            }
        }
        catch(Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        
        $model = (object) array("message" => $this->_dialog->render(), "role" => $role);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        if ($id == 0) 
        { 
            $this->action("Role");          
        }
        
        try
        {
            $role = $this->_dao->get($id);   
            if ($role == null)
            {
                $this->action("Error", "show", "error=" . $this->_dialog->render());
            }  

            if ($_POST)
            {
                $this->_dao->delete($id);
                $this->action("Role");
            }
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        
        $model = (object) array("message" => $this->_dialog->render(), "role" => $role);
        return $this->view($model);         
    }
      
    private function save(Role $role, bool $new): void
    {      
        if ($new)
        {               
            $this->_dao->insert($role);
        }
        else
        {
            $this->_dao->update($role);
        }
        $this->action("Role");     
    }
    
    private function getFromForm(Role &$role): void
    {
       //getting values from form
        if ($_POST)
        {
            $role->setName(trim($_POST["txtnmrole"]));
            $role->setDescription(trim($_POST["txtdsrole"]));  
        }
    }
    
    private function contains(Role $compare) : bool
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
