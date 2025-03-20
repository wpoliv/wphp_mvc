<?php


class AccessDAO
{
    function insert(int $roleId, int $actionId): void
    {
        $sql = "INSERT INTO ACCESS (ROLEID, ACTIONID) VALUES (:ROLEID, :ACTIONID)";
        $params = array(":ROLEID" => $roleId, ":ACTIONID" => $actionId);
        Database::open();
        Database::execute($sql, $params);
    }
      
    function delete(int $roleId, int $actionId) : void
    {
        $sql = "DELETE FROM ACCESS WHERE ROLEID = :ROLEID AND ACTIONID = :ACTIONID";
        $params = array(":ROLEID" => $roleId, ":ACTIONID" => $actionId);
        Database::open();
        Database::execute($sql, $params);          
    }

    function get(int $roleId, int $actionId): ?Action
    {
        $sql =  "SELECT ACTIONID, NMACTION, DSACTION FROM ACCESS " .
                "INNER JOIN ACTION ON ACTION.ID = ACCESS.ACTIONID " . 
                "WHERE ROLEID = :ROLEID AND ACTIONID = :ACTIONID";
        $params = array(":ROLEID" => $roleId,  ":ACTIONID" => $actionId);
        
        Database::open();
        $item = Database::get($sql, $params);
        $action = null;
        if ($item)
        {
            $action = new Action();
            $action->setId($item["ACTIONID"]);
            $action->setName($item["NMACTION"]);
            $action->setDescription($item["DSACTION"]);
            return $action;
        } 
        return $action;
    }
    
    function getByName(int $roleId, string $name): ?Action
    {
        $sql =  "SELECT ACTIONID, NMACTION, DSACTION FROM ACCESS " .
                "INNER JOIN ACTION ON ACTION.ID = ACCESS.ACTIONID " . 
                "WHERE ROLEID = :ROLEID AND NMACTION = :NMACTION";
        $params = array(":ROLEID" => $roleId,  ":NMACTION" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $action = null;
        if ($item)
        {
            $action = new Action();
            $action->setId($item["ACTIONID"]);
            $action->setName($item["NMACTION"]);
            $action->setDescription($item["DSACTION"]);
            return $action;
        } 
        return $action;
    }
     
    function list(Role $role) : ?Role
    {
        $sql =  "SELECT ACTIONID, NMACTION, DSACTION FROM ACCESS " .
                "INNER JOIN ACTION ON ACTION.ID = ACCESS.ACTIONID " .
                "WHERE ROLEID = :ROLEID";
        $params = array(":ROLEID" => $role->getId());
        
        Database::open();
        $record =  Database::query($sql, $params);
        
        foreach($record as $item)
        {
            $action = new Action();
            $action->setId($item["ACTIONID"]);
            $action->setName($item["NMACTION"]);
            $action->setDescription($item["DSACTION"]);
            $role->addAction($action);
        }                 
        return  $role;
    }
}
