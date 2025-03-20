<?php

class ContactController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new ContactDAO();
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
        
        $contact = new Contact();
        $contact->setId(0);  
        
        $validator = ContactValidator::instance($contact);
        
        if ($_POST)
        {
            //getting values from form
            $this->fromForm($_POST, $contact);
                       
            if ($validator->validate($contact))
            {
                $this->save($contact, true);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "contact" => $contact);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        $contact = $this->getById($id);     
        if ($_POST)
        {
            $update = new Contact();
            $update->setId($contact->getId());
            $this->fromForm($_POST, $update);
            
            $validator = ContactValidator::instance($update);
            if ($validator->validate($update))
            {                 
                $this->save($update, false);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
            $contact = $update;
        }
        $model = (object) array("message" => $this->_dialog->render(), "contact" => $contact);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        //$this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Contact");}
        
        $contact = $this->getById($id);   
        if ($contact->getId() == 0)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            $this->deleteObject($contact->getId());
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "contact" => $contact);
        return $this->view($model);         
    }
    
    private function getById($id) : ?Contact
    {
        if ($id == 0) 
        { 
            $this->action("Contact");
        }
        else
        {
            try
            {
                $contact = $this->_dao->get($id);
                return $contact;
            }
            catch (Exception $e)
            {
                $this->_dialog->setMessage($e->getMessage());         
                return null;
            }
        }

    }
    
    private function save(Contact $contact, bool $new): void
    {
        try
        {           
            if ($new)
            {               
                $this->_dao->insert($contact);
            }
            else
            {
                $this->_dao->update($contact);
            }
            $this->action("Contact");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }      
    }
    
    private function fromForm($values, Contact &$contact): void
    {
       //getting values from form
       $contact->setFirstName(trim($values["txtfirstname"]));
       $contact->setLastName(trim($values["txtlastname"]));  
    }
        
    private function deleteObject($id): void
    {
        try
        {
            $this->_dao->delete($id);
            $this->action("Contact");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }        
    } 
}
