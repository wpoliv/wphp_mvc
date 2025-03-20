<?php


class ActionDAO
{
    function insert(Action $action): Action
    {
        $sql = "INSERT INTO ACTION (NMACTION, DSACTION) VALUES (:NMACTION, :DSACTION)";
        $params = array(":NMACTION" => $action->getName(), ":DSACTION" => $action->getDescription());
        Database::open();
        Database::execute($sql, $params);
        $action->setId(Database::getNewId());
        return $action;
    }
    
    function update(Action $action) : void
    {
        $sql = "UPDATE ACTION SET NMACTION = :NMACTION, DSACTION = :DSACTION WHERE ID = :ID";
        $params = array(":ID" => $action->getId(), ":NMACTION" => $action->getName(), ":DSACTION" => $action->getDescription());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ACTION WHERE ID = :ID";
        $params = array(":ID" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Action
    {
        $sql = "SELECT ID, NMACTION, DSACTION FROM ACTION WHERE ID = :ID";
        $params = array(":ID" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $action = new Action();
            $action->setId($item["ID"]);
            $action->setName($item["NMACTION"]);
            $action->setDescription($item["DSACTION"]);
            return $action;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Action
    {
        $sql = "SELECT ID, NMACTION, DSACTION FROM ACTION WHERE NMACTION = :NMACTION";
        $params = array(":NMACTION" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $action = null;
        if ($item)
        {
            $action = new Action();
            $action->setId($item["ID"]);
            $action->setName($item["NMACTION"]);
            $action->setDescription($item["DSACTION"]);
            return $action;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT ID, NMACTION, DSACTION FROM ACTION";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $action = new Action();
            $action->setId($item["ID"]);
            $action->setName($item["NMACTION"]);
            $action->setDescription($item["DSACTION"]);
            $items[] = $action;
        }                 
        return  $items;
    }
}
