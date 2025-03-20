<?php


class RoleDAO
{
    function insert(Role $role): Role
    {
        $sql = "INSERT INTO ROLE (NMROLE, DSROLE) VALUES (:NMROLE, :DSROLE)";
        $params = array(":NMROLE" => $role->getName(), ":DSROLE" => $role->getDescription());
        Database::open();
        Database::execute($sql, $params);
        $role->setId(Database::getNewId());
        return $role;
    }
    
    function update(Role $role) : void
    {
        $sql = "UPDATE ROLE SET NMROLE = :NMROLE, DSROLE = :DSROLE WHERE ID = :ID";
        $params = array(":ID" => $role->getId(), ":NMROLE" => $role->getName(), ":DSROLE" => $role->getDescription());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM ROLE WHERE ID = :ID";
        $params = array(":ID" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Role
    {
        $sql = "SELECT ID, NMROLE, DSROLE FROM ROLE WHERE ID = :ID";
        $params = array(":ID" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $role = new Role();
            $role->setId($item["ID"]);
            $role->setName($item["NMROLE"]);
            $role->setDescription($item["DSROLE"]);
            return $role;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Role
    {
        $sql = "SELECT ID, NMROLE, DSROLE FROM ROLE WHERE NMROLE = :NMROLE";
        $params = array(":NMROLE" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $role = null;
        if ($item)
        {
            $role = new Role();
            $role->setId($item["ID"]);
            $role->setName($item["NMROLE"]);
            $role->setDescription($item["DSROLE"]);
            return $role;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT ID, NMROLE, DSROLE FROM ROLE";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $role = new Role();
            $role->setId($item["ID"]);
            $role->setName($item["NMROLE"]);
            $role->setDescription($item["DSROLE"]);
            $items[] = $role;
        }                 
        return  $items;
    }
}
