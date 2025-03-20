<?php


class CategoriaDAO
{
    function insert(Categoria $categoria): Categoria
    {
        $sql = "INSERT INTO ADM_CATEGORIA (NMCATEGORIA, DSCATEGORIA) VALUES (:NMCATEGORIA, :DSCATEGORIA)";
        $params = array(":NMCATEGORIA" => $categoria->getNome(), ":DSCATEGORIA" => $categoria->getDescricao());
        Database::open();
        Database::execute($sql, $params);
        $categoria->setCodigo(Database::getNewId());
        return $categoria;
    }
    
    function update(Categoria $categoria) : void
    {
        $sql = "UPDATE ADM_CATEGORIA SET NMCATEGORIA = :NMCATEGORIA, DSCATEGORIA = :DSCATEGORIA WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $categoria->getCodigo(), ":NMCATEGORIA" => $categoria->getNome(), ":DSCATEGORIA" => $categoria->getDescricao());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ADM_CATEGORIA WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Categoria
    {
        $sql = "SELECT CODIGO, NMCATEGORIA, DSCATEGORIA FROM ADM_CATEGORIA WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $categoria = new Categoria();
            $categoria->setCodigo($item["CODIGO"]);
            $categoria->setNome($item["NMCATEGORIA"]);
            $categoria->setDescricao($item["DSCATEGORIA"]);
            return $categoria;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Categoria
    {
        $sql = "SELECT CODIGO, NMCATEGORIA, DSCATEGORIA FROM ADM_CATEGORIA WHERE NMCATEGORIA = :NMCATEGORIA";
        $params = array(":NMCATEGORIA" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $categoria = null;
        if ($item)
        {
            $categoria = new Categoria();
            $categoria->setCodigo($item["CODIGO"]);
            $categoria->setNome($item["NMCATEGORIA"]);
            $categoria->setDescricao($item["DSCATEGORIA"]);
            return $categoria;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT CODIGO, NMCATEGORIA, DSCATEGORIA FROM ADM_CATEGORIA";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $categoria = new Categoria();
            $categoria->setCodigo($item["CODIGO"]);
            $categoria->setNome($item["NMCATEGORIA"]);
            $categoria->setDescricao($item["DSCATEGORIA"]);
            $items[] = $categoria;
        }    
                
        return  $items;
    }
}
