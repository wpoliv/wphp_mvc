<?php

class CategoriaController extends Controller
{
    private $_dao;
    private $_dialog;
    
    public function __construct()
    {
        $this->authenticate("admtable");
        
        $this->_dao = new CategoriaDAO();
        $this->_dialog = new Message();
        $this->_dialog->setTitle("Alerta");
    }
    function index(): object
    {           
        $model = (object) $this->_dao->list();
        
        return $this->view($model);
    }
    
    function insert(): object
    {       
        $categoria = new Categoria();       
        $validator = CategoriaValidator::instance($categoria);
        $categoria->setCodigo(0);  
        
        if ($_POST)
        {
            //getting values from form
            $this->getFromForm($categoria);
              
            try
            {
                if ($validator->validate($categoria))
                {
                    if (!$this->contains($categoria))
                    {
                        $this->save($categoria, true);
                    }
                    else
                    {
                        $this->_dialog->setMessage("O nome '{$categoria->getNome()}' já existe."); 
                    }
                }
                else
                {
                    $this->_dialog->setMessage($validator->get());
                }
            }
            catch (Exception $e)
            {
                $this->_dialog->setMessage($e->getMessage());
            }
        }
           
        $model = (object) array("message" => $this->_dialog->render(), "categoria" => $categoria);
        return $this->view($model);
    }
    
    function edit(?int $id = 0): object
    {
        try
        {
            $categoria = $this->_dao->get($id);     
            if ($_POST)
            {
                $update = new Categoria();
                $update->setCodigo($categoria->getCodigo());
                $this->getFromForm($update);

                $validator = CategoriaValidator::instance($update);
                if ($validator->validate($update))
                {
                    if (!$this->contains($update))
                    {
                        $this->save($update, false);
                    }
                    else
                    {
                        $this->_dialog->setMessage("O nome '{$update->getNome()}' já existe."); 
                    }
                }
                else
                {
                    $this->_dialog->setMessage($validator->get());
                }
                $categoria = $update;
            }
        }
        catch(Exception $e)
        {
            $this->_dialog->setMessage($e->getMessage());
        }
        
        $model = (object) array("message" => $this->_dialog->render(), "categoria" => $categoria);
        return $this->view($model);    
        
    } 
    
    function delete(?int $id = 0): object
    { 
        if ($id == 0) { $this->action("Categoria");}
        
        $categoria = $this->_dao->get($id);   
        if ($categoria->getCodigo() == 0)
        {
            $this->action("Error", "show", "error=" . $this->_dialog->render());
        }  
        
        if ($_POST)
        {
            try
            {
                $this->_dao->delete($id);
                $this->action("Categoria");
            }
            catch (Exception $e)
            {
                $this->_dialog->setMessage($e->getMessage());
            }  
        }
        //saving values to database
        $model = (object) array("message" => $this->_dialog->render(), "categoria" => $categoria);
        return $this->view($model);         
    }
    
    private function save(Categoria $categoria, bool $new): void
    {       
        if ($new)
        {               
            $this->_dao->insert($categoria);
        }
        else
        {
            $this->_dao->update($categoria);
        }
        $this->action("Categoria");     
    }
    
    private function getFromForm(Categoria &$categoria): void
    {
        //getting values from form
        if ($_POST)
        {
            $categoria->setNome(trim(trim($_POST["txtnmcategoria"])));
            $categoria->setDescricao(trim($_POST["txtdscategoria"])); 
        }
    }
    
    private function contains(Categoria $compare) : bool
    { 
        $ret = false;     

        $search = $this->_dao->getByName($compare->getNome());

        if ($search)
        {
            $ret = ($search->getCodigo() != $compare->getCodigo() ? true : false);              
        }
        return $ret;
    }
}
