<?php


class TipoDAO
{
    function insert(Tipo $tipo): Tipo
    {
        $sql = "INSERT INTO ADM_TIPOMATERIAL (CDCATEGORIA, NMTIPOMATERIAL, DSTIPOMATERIAL) VALUES (:CDCATEGORIA, :NMTIPOMATERIAL, :DSTIPOMATERIAL)";
        $params = array(":CDCATEGORIA" => $tipo->getCategoria()->getCodigo(), ":NMTIPOMATERIAL" => $tipo->getNome(), ":DSTIPOMATERIAL" => $tipo->getDescricao());
        Database::open();
        Database::execute($sql, $params);
        $tipo->setCodigo(Database::getNewId());
        return $tipo;
    }
    
    function update(Tipo $tipo) : void
    {
        $sql = "UPDATE ADM_TIPOMATERIAL SET CDCATEGORIA = :CDCATEGORIA, NMTIPOMATERIAL = :NMTIPOMATERIAL, DSTIPOMATERIAL = :DSTIPOMATERIAL WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $tipo->getCodigo(), "CDCATEGORIA" => $tipo->getCategoria()->getCodigo() , ":NMTIPOMATERIAL" => $tipo->getNome(), ":DSTIPOMATERIAL" => $tipo->getDescricao());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ADM_TIPOMATERIAL WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Tipo
    {
        $sql =  "SELECT ADM_TIPOMATERIAL.CODIGO, CDCATEGORIA, NMCATEGORIA, NMTIPOMATERIAL, DSTIPOMATERIAL FROM ADM_TIPOMATERIAL " .
                "INNER JOIN ADM_CATEGORIA ON ADM_CATEGORIA.CODIGO = ADM_TIPOMATERIAL.CDCATEGORIA " .
                "WHERE ADM_TIPOMATERIAL.CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        $tipo = null;
        if ($item)
        {
            $tipo = new Tipo();
            $tipo->setCodigo($item["CODIGO"]);
            $tipo->setNome($item["NMTIPOMATERIAL"]);
            $tipo->setDescricao($item["DSTIPOMATERIAL"]);
            $tipo->setCategoria(new Categoria());
            $tipo->getCategoria()->setCodigo($item["CDCATEGORIA"]);
            $tipo->getCategoria()->setNome($item["NMCATEGORIA"]);
        } 
        return $tipo;
    }
    
    function getByName(int $cdcategoria, string $nmtipo): ?Tipo
    {
        $sql =  "SELECT ADM_TIPOMATERIAL.CODIGO, CDCATEGORIA, NMCATEGORIA, NMTIPOMATERIAL, DSTIPOMATERIAL FROM ADM_TIPOMATERIAL " .
                "INNER JOIN ADM_CATEGORIA ON ADM_CATEGORIA.CODIGO = ADM_TIPOMATERIAL.CDCATEGORIA " .
                "WHERE CDCATEGORIA = :CDCATEGORIA AND NMTIPOMATERIAL = :NMTIPOMATERIAL";
        $params = array("CDCATEGORIA" => $cdcategoria, ":NMTIPOMATERIAL" => $nmtipo);
        
        Database::open();
        $item = Database::get($sql, $params);
        $tipo = null;
        if ($item)
        {
            $tipo = new Tipo();
            $tipo->setCodigo($item["CODIGO"]);
            $tipo->setNome($item["NMTIPOMATERIAL"]);
            $tipo->setDescricao($item["DSTIPOMATERIAL"]);
            $tipo->setCategoria(new Categoria());
            $tipo->getCategoria()->setCodigo($item["CDCATEGORIA"]);
            $tipo->getCategoria()->setNome($item["NMCATEGORIA"]);
        } 
        return $tipo;
    }
     
    function list() : ?array
    {
        $sql =  "SELECT ADM_TIPOMATERIAL.CODIGO, CDCATEGORIA, ADM_CATEGORIA.NMCATEGORIA, NMTIPOMATERIAL, DSTIPOMATERIAL FROM ADM_TIPOMATERIAL " .
                "INNER JOIN ADM_CATEGORIA ON ADM_CATEGORIA.CODIGO = ADM_TIPOMATERIAL.CDCATEGORIA ";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $tipo = new Tipo();           
            $tipo->setCodigo($item["CODIGO"]);
            $tipo->setNome($item["NMTIPOMATERIAL"]);
            $tipo->setDescricao($item["DSTIPOMATERIAL"]);
            $tipo->setCategoria(new Categoria());
            $tipo->getCategoria()->setCodigo($item["CDCATEGORIA"]);
            $tipo->getCategoria()->setNome($item["NMCATEGORIA"]);
            $items[] = $tipo;
        }                  
        return  $items;
    }
}
