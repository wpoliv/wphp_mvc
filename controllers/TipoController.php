<?php

class TipoController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new TipoDAO();
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
        
        $categorias = (new CategoriaDAO())->list();
        $tipo = new Tipo();
        $tipo->setCodigo(0);  
        $tipo->setCategoria(new Categoria());
        
        $validator = TipoValidator::instance($tipo);
        
        if ($_POST)
        {
            //getting values from form
            $this->fromForm($_POST, $tipo);
                       
            if ($validator->validate($tipo) && !$this->contains($tipo))
            {
                $this->save($tipo, true);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "tipo" => $tipo, "categorias" => $categorias);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        $categorias = (new CategoriaDAO())->list();
        $tipo = $this->_dao->get($id);
        
        if(!$tipo)
        {
            $this->action("Tipo");
        }
        
        if ($_POST)
        {
            $update = new Tipo();
            $update->setCodigo($tipo->getCodigo());
            $update->setCategoria(new Categoria());
            $this->fromForm($_POST, $update);
            
            $validator = TipoValidator::instance($update);
            if ($validator->validate($update) && !$this->contains($update))
            {                 
                $this->save($update, false);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
            $tipo = $update;
        }
        $model = (object) array("message" => $this->_dialog->render(), "tipo" => $tipo, "categorias" => $categorias);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Tipo");}
        
        $tipo = $this->_dao->get($id);
        if (!$tipo)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            $this->deleteObject($tipo->getCodigo());
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "tipo" => $tipo);
        return $this->view($model);         
    }
    
    
    private function save(Tipo $tipo, bool $new): void
    {
        try
        {           
            if ($new)
            {               
                $this->_dao->insert($tipo);
            }
            else
            {
                $this->_dao->update($tipo);
            }
            $this->action("Tipo");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }      
    }
    
    private function fromForm($values, Tipo &$tipo): void
    {
       //getting values from form
       $tipo->setNome(trim($values["txtnmtipo"]));
       $tipo->setDescricao(trim($values["txtdstipo"]));
       $tipo->getCategoria()->setCodigo($values["cbocategoria"]);
    }
    
    private function contains(Tipo $compare) : bool
    { 
        $ret = false;     
        try
        {
            $search = $this->_dao->getByName($compare->getCategoria()->getCodigo(), $compare->getNome());
            
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
            $this->action("Tipo");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }        
    } 
}
