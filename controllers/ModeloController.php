<?php

class ModeloController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->_dao = new ModeloDAO();
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
        
        $tipos = (new TipoDAO())->list();
        $fabricantes = (new FabricanteDAO())->list();
        
        $modelo = new Modelo();
        $modelo->setCodigo(0);  
        $modelo->getTipo()->setCodigo(0);
        $modelo->getFabricante()->setCodigo(0);
        
        $validator = ModeloValidator::instance($modelo);
        
        if ($_POST)
        {
            //getting values from form
            $this->fromForm($_POST, $modelo);
                       
            if ($validator->validate($modelo) && !$this->contains($modelo))
            {
                $this->save($modelo, true);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "modelo" => $modelo, "tipos" => $tipos, "fabricantes" => $fabricantes);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        $tipos = (new TipoDAO())->list();
        $fabricantes = (new FabricanteDAO())->list();
        
        $modelo = $this->_dao->get($id);
        
        if(!$modelo)
        {
            $this->action("Modelo");
        }
        
        if ($_POST)
        {
            $update = new Modelo();
            $update->setCodigo($modelo->getCodigo());
            $update->getTipo()->setCodigo(0);
            $update->getFabricante()->setCodigo(0);
            $this->fromForm($_POST, $update);
            
            $validator = ModeloValidator::instance($update);
            if ($validator->validate($update) && !$this->contains($update))
            {                 
                $this->save($update, false);
            }
            if (!empty($validator->get()))
            {
                $this->_dialog->setMessage($validator->get());
            }
            $modelo = $update;
        }
        $model = (object) array("message" => $this->_dialog->render(), "modelo" => $modelo, "tipos" => $tipos, "fabricantes" => $fabricantes);
        return $this->view($model);    
    } 
    
    function delete(?int $id = 0): object
    {
        $this->authenticate("admtable");
        
        if ($id == 0) { $this->action("Modelo");}
        
        $modelo = $this->_dao->get($id);
        if (!$modelo)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            $this->deleteObject($modelo->getCodigo());
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "modelo" => $modelo);
        return $this->view($model);         
    }
    
    private function save(Modelo $modelo, bool $new): void
    {
        try
        {           
            if ($new)
            {               
                $this->_dao->insert($modelo);
            }
            else
            {
                $this->_dao->update($modelo);
            }
            $this->action("Modelo");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }      
    }
    
    private function fromForm($values, Modelo &$modelo): void
    {
       //getting values from form
       $modelo->setNome(trim($values["txtnmmodelo"]));
       $modelo->setNumero(trim($values["txtnumodelo"]));
       $modelo->setDescricao(trim($values["txtdsmodelo"]));
       $modelo->getTipo()->setCodigo($values["cbotipo"]);
       $modelo->getFabricante()->setCodigo($values["cbofabricante"]);
    }
    
    private function contains(Modelo $compare) : bool
    { 
        $ret = false;     
        try
        {
            $search = $this->_dao->getByName($compare->getTipo()->getCodigo(), $compare->getFabricante()->getCodigo(), $compare->getNome());
            
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
            $this->action("Modelo");
        }
        catch (Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }        
    } 
}
