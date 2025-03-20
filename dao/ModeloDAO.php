<?php


class ModeloDAO
{
    function insert(Modelo $modelo): Modelo
    {
        $sql = "INSERT INTO ADM_MODELO (CDTIPOMATERIAL, CDFABRICANTE, NMMODELO, NUMODELO, DSMODELO) VALUES (:CDTIPOMATERIAL, :CDFABRICANTE, :NMMODELO, :NUMODELO, :DSMODELO)";
        $params = array(":CDTIPOMATERIAL" => $modelo->getTipo()->getCodigo(),
                        "CDFABRICANTE" => $modelo->getFabricante()->getCodigo(), 
                        ":NMMODELO" => $modelo->getNome(),
                        ":NUMODELO" => $modelo->getNumero(),
                        ":DSMODELO" => $modelo->getDescricao());
        Database::open();
        Database::execute($sql, $params);
        $modelo->setCodigo(Database::getNewId());
        return $modelo;
    }
    
    function update(Modelo $modelo) : void
    {
        $sql =  "UPDATE ADM_MODELO SET CDTIPOMATERIAL = :CDTIPOMATERIAL, " .
                "CDFABRICANTE = :CDFABRICANTE, NUMODELO = :NUMODELO, " .
                "NMMODELO = :NMMODELO, DSMODELO = :DSMODELO WHERE CODIGO = :CODIGO";
        
        $params = array(":CODIGO" => $modelo->getCodigo(), 
            "CDTIPOMATERIAL" => $modelo->getTipo()->getCodigo() , 
            "CDFABRICANTE" => $modelo->getFabricante()->getCodigo() , 
            ":NUMODELO" => $modelo->getNumero(), 
            ":NMMODELO" => $modelo->getNome(), 
            ":DSMODELO" => $modelo->getDescricao());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ADM_MODELO WHERE CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Modelo
    {
        $sql =  "SELECT ADM_MODELO.CODIGO, CDTIPOMATERIAL, NMTIPOMATERIAL, " .
                "CDFABRICANTE, NMFABRICANTE, NMMODELO, NUMODELO, DSMODELO FROM ADM_MODELO " .
                "INNER JOIN ADM_TIPOMATERIAL ON ADM_TIPOMATERIAL.CODIGO = ADM_MODELO.CDTIPOMATERIAL " .
                "INNER JOIN ADM_FABRICANTE ON ADM_FABRICANTE.CODIGO = ADM_MODELO.CDFABRICANTE " .
                "WHERE ADM_MODELO.CODIGO = :CODIGO";
        $params = array(":CODIGO" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        $modelo = null;
        if ($item)
        {
            $modelo = new Modelo();
            $modelo->setCodigo($item["CODIGO"]);
            $modelo->setNome($item["NMMODELO"]);
            $modelo->setNumero($item["NUMODELO"]);
            $modelo->setDescricao($item["DSMODELO"]);
            $modelo->getTipo()->setCodigo($item["CDTIPOMATERIAL"]);
            $modelo->getTipo()->setNome($item["NMTIPOMATERIAL"]);
            $modelo->getFabricante()->setCodigo($item["CDFABRICANTE"]);
            $modelo->getFabricante()->setNome($item["NMFABRICANTE"]);
        } 
        return $modelo;
    }
    
    function getByName(int $cdtipo, int $cdfabricante, string $nmmodelo): ?Modelo
    {
        $sql =  "SELECT ADM_MODELO.CODIGO, CDTIPOMATERIAL, NMTIPOMATERIAL, " .
                "CDFABRICANTE, NMFABRICANTE, NMMODELO, NUMODELO, DSMODELO FROM ADM_MODELO " .
                "INNER JOIN ADM_TIPOMATERIAL ON ADM_TIPOMATERIAL.CODIGO = ADM_MODELO.CDTIPOMATERIAL " .
                "INNER JOIN ADM_FABRICANTE ON ADM_FABRICANTE.CODIGO = ADM_MODELO.CDFABRICANTE " .
                "WHERE CDTIPOMATERIAL = :CDTIPOMATERIAL AND CDFABRICANTE = :CDFABRICANTE AND NMMODELO = :NMMODELO";
        $params = array(":CDTIPOMATERIAL" => $cdtipo, 
            ":CDFABRICANTE" => $cdfabricante, 
            ":NMMODELO" => $nmmodelo);
        
        Database::open();
        $item = Database::get($sql, $params);
        $modelo = null;
        if ($item)
        {
            $modelo = new Modelo();
            $modelo->setCodigo($item["CODIGO"]);
            $modelo->setNome($item["NMMODELO"]);
            $modelo->setNumero($item["NUMODELO"]);
            $modelo->setDescricao($item["DSMODELO"]);
            $modelo->getTipo()->setCodigo($item["CDTIPOMATERIAL"]);
            $modelo->getTipo()->setNome($item["NMTIPOMATERIAL"]);
            $modelo->getFabricante()->setCodigo($item["CDFABRICANTE"]);
            $modelo->getFabricante()->setNome($item["NMFABRICANTE"]);
        } 
        return $modelo;
    }
     
    function list() : ?array
    {
        $sql =  "SELECT ADM_MODELO.CODIGO, CDTIPOMATERIAL, NMTIPOMATERIAL, " .
                "CDFABRICANTE, NMFABRICANTE, NMMODELO, NUMODELO, DSMODELO FROM ADM_MODELO " .
                "INNER JOIN ADM_TIPOMATERIAL ON ADM_TIPOMATERIAL.CODIGO = ADM_MODELO.CDTIPOMATERIAL " .
                "INNER JOIN ADM_FABRICANTE ON ADM_FABRICANTE.CODIGO = ADM_MODELO.CDFABRICANTE";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $modelo = new Modelo();
            $modelo->setCodigo($item["CODIGO"]);
            $modelo->setNome($item["NMMODELO"]);
            $modelo->setNumero($item["NUMODELO"]);
            $modelo->setDescricao($item["DSMODELO"]);
            $modelo->getTipo()->setCodigo($item["CDTIPOMATERIAL"]);
            $modelo->getTipo()->setNome($item["NMTIPOMATERIAL"]);
            $modelo->getFabricante()->setCodigo($item["CDFABRICANTE"]);
            $modelo->getFabricante()->setNome($item["NMFABRICANTE"]);
            
            $items[] = $modelo;
        }                  
        return  $items;
    }
}
