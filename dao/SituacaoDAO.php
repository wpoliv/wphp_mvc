<?php


class SituacaoDAO
{
    function insert(Situacao $situacao): Situacao
    {
        $sql = "INSERT INTO ADM_SITUACAO (NMSITUACAO, DSSITUACAO) VALUES (:NMSITUACAO, :DSSITUACAO)";
        $params = array(":NMSITUACAO" => $situacao->getNome(), ":DSSITUACAO" => $situacao->getDescricao());
        Database::open();
        Database::execute($sql, $params);
        $situacao->setCodigo(Database::getNewId());
        return $situacao;
    }
    
    function update(Situacao $situacao) : void
    {
        $sql = "UPDATE ADM_SITUACAO SET NMSITUACAO = :NMSITUACAO, DSSITUACAO = :DSSITUACAO WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $situacao->getCodigo(), ":NMSITUACAO" => $situacao->getNome(), ":DSSITUACAO" => $situacao->getDescricao());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ADM_SITUACAO WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Situacao
    {
        $sql = "SELECT CODIGO, NMSITUACAO, DSSITUACAO FROM ADM_SITUACAO WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $situacao = new Situacao();
            $situacao->setCodigo($item["CODIGO"]);
            $situacao->setNome($item["NMSITUACAO"]);
            $situacao->setDescricao($item["DSSITUACAO"]);
            return $situacao;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Situacao
    {
        $sql = "SELECT CODIGO, NMSITUACAO, DSSITUACAO FROM ADM_SITUACAO WHERE NMSITUACAO = :NMSITUACAO";
        $params = array(":NMSITUACAO" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $situacao = null;
        if ($item)
        {
            $situacao = new Situacao();
            $situacao->setCodigo($item["CODIGO"]);
            $situacao->setNome($item["NMSITUACAO"]);
            $situacao->setDescricao($item["DSSITUACAO"]);
            return $situacao;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT CODIGO, NMSITUACAO, DSSITUACAO FROM ADM_SITUACAO";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $situacao = new Situacao();
            $situacao->setCodigo($item["CODIGO"]);
            $situacao->setNome($item["NMSITUACAO"]);
            $situacao->setDescricao($item["DSSITUACAO"]);
            $items[] = $situacao;
        }    
                
        return  $items;
    }
}
