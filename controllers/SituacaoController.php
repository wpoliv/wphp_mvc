<?php

class SituacaoController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new SituacaoDAO();
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
        
        $situacao = new Situacao();
        $situacao->setCodigo(0);  
        
        $validator = SituacaoValidator::instance($situacao);
        
        if ($_POST)
        {
            //getting values from form
            $this->fromForm($_POST, $situacao);
                       
            if ($validator->validate($situacao) && !$this->contains($situacao))
            {
                $this->save($situacao, true);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "situacao" => $situacao);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        $situacao = $this->getById($id);     
        if ($_POST)
        {
            $update = new Situacao();
            $update->setCodigo($situacao->getCodigo());
            $this->fromForm($_POST, $update);
            
            $validator = SituacaoValidator::instance($update);
            if ($validator->validate($update) && !$this->contains($update))
            {                 
                $this->save($update, false);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
            $situacao = $update;
        }
        $model = (object) array("message" => $this->_dialog->render(), "situacao" => $situacao);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Situacao");}
        
        $situacao = $this->getById($id);   
        if ($situacao->getCodigo() == 0)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            $this->deleteObject($situacao->getCodigo());
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "situacao" => $situacao);
        return $this->view($model);         
    }
    
    private function getById($id): ?Situacao
    {
        if ($id == 0) 
        { 
            $this->action("Situacao");
        }
        else
        {
            try
            {
                $situacao = $this->_dao->get($id);
                return $situacao;
            }
            catch (Exception $e)
            {
                $this->_dialog->setMessage($e->getMessage());         
                return null;
            }
        }

    }
    
    private function save(Situacao $situacao, bool $new): void
    {
        try
        {           
            if ($new)
            {               
                $this->_dao->insert($situacao);
            }
            else
            {
                $this->_dao->update($situacao);
            }
            $this->action("Situacao");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }      
    }
    
    private function fromForm($values, Situacao &$situacao): void
    {
       //getting values from form
       $situacao->setNome(trim($values["txtnmsituacao"]));
       $situacao->setDescricao(trim($values["txtdssituacao"]));   
    }
    
    private function contains(Situacao $compare) : bool
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
            $this->action("Situacao");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }        
    } 
}
