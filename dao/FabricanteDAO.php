<?php

class FabricanteDAO
{
    function insert(Fabricante $fabricante): Fabricante
    {
        $sql = "INSERT INTO ADM_FABRICANTE (NMFABRICANTE, DSFABRICANTE) VALUES (:NMFABRICANTE, :DSFABRICANTE)";
        $params = array(":NMFABRICANTE" => $fabricante->getNome(), ":DSFABRICANTE" => $fabricante->getDescricao());
        Database::open();
        Database::execute($sql, $params);
        $fabricante->setCodigo(Database::getNewId());
        return $fabricante;
    }
    
    function update(Fabricante $fabricante) : void
    {
        $sql = "UPDATE ADM_FABRICANTE SET NMFABRICANTE = :NMFABRICANTE, DSFABRICANTE = :DSFABRICANTE WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $fabricante->getCodigo(), ":NMFABRICANTE" => $fabricante->getNome(), ":DSFABRICANTE" => $fabricante->getDescricao());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ADM_FABRICANTE WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Fabricante
    {
        $sql = "SELECT CODIGO, NMFABRICANTE, DSFABRICANTE FROM ADM_FABRICANTE WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $fabricante = new Fabricante();
            $fabricante->setCodigo($item["CODIGO"]);
            $fabricante->setNome($item["NMFABRICANTE"]);
            $fabricante->setDescricao($item["DSFABRICANTE"]);
            return $fabricante;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Fabricante
    {
        $sql = "SELECT CODIGO, NMFABRICANTE, DSFABRICANTE FROM ADM_FABRICANTE WHERE NMFABRICANTE = :NMFABRICANTE";
        $params = array(":NMFABRICANTE" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $fabricante = null;
        if ($item)
        {
            $fabricante = new Fabricante();
            $fabricante->setCodigo($item["CODIGO"]);
            $fabricante->setNome($item["NMFABRICANTE"]);
            $fabricante->setDescricao($item["DSFABRICANTE"]);
            return $fabricante;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT CODIGO, NMFABRICANTE, DSFABRICANTE FROM ADM_FABRICANTE";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $fabricante = new Fabricante();
            $fabricante->setCodigo($item["CODIGO"]);
            $fabricante->setNome($item["NMFABRICANTE"]);
            $fabricante->setDescricao($item["DSFABRICANTE"]);
            $items[] = $fabricante;
        }    
                
        return  $items;
    }
}
