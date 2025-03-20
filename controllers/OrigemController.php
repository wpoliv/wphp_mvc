<?php

class OrigemController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new OrigemDAO();
        $this->_dialog = new Message();
        $this->_dialog->setTitle("Alerta");
    }
    function index(): object
    {
        $this->authenticate("admtable");
        
        $model = (object) $this->_dao->list();
        
        return $this->view($model);
    }
    
    function insert(): object
    {
        $this->authenticate("admtable");
        
        $origem = new Origem();
        $origem->setCodigo(0);  
        
        $validator = OrigemValidator::instance($origem);
        
        if ($_POST)
        {
            //getting values from form
            $this->fromForm($_POST, $origem);
                       
            if ($validator->validate($origem) && !$this->contains($origem))
            {
                $this->save($origem, true);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "origem" => $origem);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        $origem = $this->getById($id);     
        if ($_POST)
        {
            $update = new Origem();
            $update->setCodigo($origem->getCodigo());
            $this->fromForm($_POST, $update);
            
            $validator = OrigemValidator::instance($update);
            if ($validator->validate($update) && !$this->contains($update))
            {                 
                $this->save($update, false);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
            $origem = $update;
        }
        $model = (object) array("message" => $this->_dialog->render(), "origem" => $origem);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Origem");}
        
        $origem = $this->getById($id);   
        if ($origem->getCodigo() == 0)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            $this->deleteObject($origem->getCodigo());
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "origem" => $origem);
        return $this->view($model);         
    }
    
    private function getById($id): ?Origem
    {
        if ($id == 0) 
        { 
            $this->action("Origem");
        }
        else
        {
            try
            {
                $origem = $this->_dao->get($id);
                return $origem;
            }
            catch (Exception $e)
            {
                $this->_dialog->setMessage($e->getMessage());         
                return null;
            }
        }

    }
    
    private function save(Origem $origem, bool $new): void
    {
        try
        {           
            if ($new)
            {               
                $this->_dao->insert($origem);
            }
            else
            {
                $this->_dao->update($origem);
            }
            $this->action("Origem");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }      
    }
    
    private function fromForm($values, Origem &$origem): void
    {
       //getting values from form
       $origem->setNome(trim($values["txtnmorigem"]));
       $origem->setDescricao(trim($values["txtdsorigem"]));   
    }
    
    private function contains(Origem $compare) : bool
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
            $this->action("Origem");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }        
    } 
}
