<?php

class FabricanteController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new FabricanteDAO();
        $this->_dialog = new Message();
        $this->_dialog->setTitle("Alerta");
    }
    function index(): object
    {
        $this->authenticate("admtable");
        
        $model = (object)$this->_dao->list();
        return $this->view($model);
    }
    
    function insert(): object
    {
        $this->authenticate("admtable");
        
        $fabricante = new Fabricante();
        $fabricante->setCodigo(0);  
        
        $validator = FabricanteValidator::instance($fabricante);
        
        if ($_POST)
        {
            //getting values from form
            $this->fromForm($_POST, $fabricante);
                       
            if ($validator->validate($fabricante) && !$this->contains($fabricante))
            {
                $this->save($fabricante, true);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "fabricante" => $fabricante);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        $fabricante = $this->getById($id);     
        if ($_POST)
        {
            $update = new Fabricante();
            $update->setCodigo($fabricante->getCodigo());
            $this->fromForm($_POST, $update);
            
            $validator = FabricanteValidator::instance($update);
            if ($validator->validate($update) && !$this->contains($update))
            {                 
                $this->save($update, false);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
            $fabricante = $update;
        }
        $model = (object) array("message" => $this->_dialog->render(), "fabricante" => $fabricante);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Fabricante");}
        
        $fabricante = $this->getById($id);   
        if ($fabricante->getCodigo() == 0)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            $this->deleteObject($fabricante->getCodigo());
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "fabricante" => $fabricante);
        return $this->view($model);         
    }
    
    private function getById($id) : ?Fabricante
    {
        if ($id == 0) 
        { 
            $this->action("Fabricante");
        }
        else
        {
            try
            {
                $fabricante = $this->_dao->get($id);
                return $fabricante;
            }
            catch (Exception $e)
            {
                $this->_dialog->setMessage($e->getMessage());         
                return null;
            }
        }

    }
    
    private function save(Fabricante $fabricante, bool $new): void
    {
        try
        {           
            if ($new)
            {               
                $this->_dao->insert($fabricante);
            }
            else
            {
                $this->_dao->update($fabricante);
            }
            $this->action("Fabricante");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }      
    }
    
    private function fromForm($values, Fabricante &$fabricante): void
    {
       //getting values from form
       $fabricante->setNome(trim($values["txtnmfabricante"]));
       $fabricante->setDescricao(trim($values["txtdsfabricante"]));   
    }
    
    private function contains(Fabricante $compare) : bool
    { 
        $ret = false;     
        try
        {
            $search = $this->_dao->getByName($compare->getNome());
            
            if ($search)
            {
                $ret = ($search->getCodigo() != $compare->getCodigo() ? true : false);              
            }
            if ($ret)
            {
                $this->_dialog->setMessage("O nome '{$compare->getNome()}' já existe.");
            }
        } 
        catch (Exception $e)
        {

            $this->_dialog->setMessage($e->getMessage());
            $ret = true;
        }
        return $ret;
    }
    
    private function deleteObject($id): void
    {
        try
        {
            $this->_dao->delete($id);
            $this->action("Fabricante");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }        
    }  
}
