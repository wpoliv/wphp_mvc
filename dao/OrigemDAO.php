<?php


class OrigemDAO
{
    function insert(Origem $origem): Origem
    {
        $sql = "INSERT INTO ADM_ORIGEM (NMORIGEM, DSORIGEM) VALUES (:NMORIGEM, :DSORIGEM)";
        $params = array(":NMORIGEM" => $origem->getNome(), ":DSORIGEM" => $origem->getDescricao());
        Database::open();
        Database::execute($sql, $params);
        $origem->setCodigo(Database::getNewId());
        return $origem;
    }
    
    function update(Origem $origem) : void
    {
        $sql = "UPDATE ADM_ORIGEM SET NMORIGEM = :NMORIGEM, DSORIGEM = :DSORIGEM WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $origem->getCodigo(), ":NMORIGEM" => $origem->getNome(), ":DSORIGEM" => $origem->getDescricao());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ADM_ORIGEM WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Origem
    {
        $sql = "SELECT CODIGO, NMORIGEM, DSORIGEM FROM ADM_ORIGEM WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $origem = new Origem();
            $origem->setCodigo($item["CODIGO"]);
            $origem->setNome($item["NMORIGEM"]);
            $origem->setDescricao($item["DSORIGEM"]);
            return $origem;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Origem
    {
        $sql = "SELECT CODIGO, NMORIGEM, DSORIGEM FROM ADM_ORIGEM WHERE NMORIGEM = :NMORIGEM";
        $params = array(":NMORIGEM" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $origem = null;
        if ($item)
        {
            $origem = new Origem();
            $origem->setCodigo($item["CODIGO"]);
            $origem->setNome($item["NMORIGEM"]);
            $origem->setDescricao($item["DSORIGEM"]);
            return $origem;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT CODIGO, NMORIGEM, DSORIGEM FROM ADM_ORIGEM";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $origem = new Origem();
            $origem->setCodigo($item["CODIGO"]);
            $origem->setNome($item["NMORIGEM"]);
            $origem->setDescricao($item["DSORIGEM"]);
            $items[] = $origem;
        }    
                
        return  $items;
    }
}
