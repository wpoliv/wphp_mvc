<?php

class ContactDAO
{
    function insert(Contact $contact): Contact
    {
        $sql = "INSERT INTO CONTACT (FIRSTNAME, LASTNAME, DTREGISTRO) VALUES (:FIRSTNAME, :LASTNAME, NOW())";
        $params = array(":FIRSTNAME" => $contact->getFirstName(), ":LASTNAME" => $contact->getLastName());
        Database::open();
        Database::execute($sql, $params);
        $contact->setId(Database::getNewId());
        return $contact;
    }
    
    function update(Contact $contact) : void
    {
        $sql = "UPDATE CONTACT SET FIRSTNAME = :FIRSTNAME, LASTNAME = :LASTNAME WHERE ID = :ID";
        $params = array(":ID" => $contact->getId(), ":FIRSTNAME" => $contact->getFirstName(), ":LASTNAME" => $contact->getLastName());
        Database::open();
        Database::execute($sql, $params);     
    }
    
    function delete($id) : void
    {
        $sql = "DELETE FROM CONTACT WHERE ID = :ID";
        $params = array(":ID" => $id);
        Database::open();
        Database::execute($sql, $params);          
    }
    
    function  get($id): ?Contact
    {
        $sql = "SELECT ID, FIRSTNAME, LASTNAME FROM CONTACT WHERE ID = :ID";
        $params = array(":ID" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        if ($item)
        {
            $contact = new Contact();
            $contact->setId($item["ID"]);
            $contact->setFirstName($item["FIRSTNAME"]);
            $contact->setLastName($item["LASTNAME"]);
            return $contact;
        }
        else
        {
            return null;
        }
    }
    
    function getByName($name): ?Contact
    {
        $sql = "SELECT ID, FIRSTNAME, LASTNAME FROM CONTACT WHERE EMAIL = :EMAIL";
        $params = array(":EMAIL" => $name);
        
        Database::open();
        $item = Database::get($sql, $params);
        $contact = null;
        if ($item)
        {
            $contact = new Contact();
            $contact->setId($item["ID"]);
            $contact->setFirstName($item["FIRSTNAME"]);
            $contact->setLastName($item["LASTNAME"]);
            return $contact;
        } 
        else
        {
            return null;     
        }
    }
     
    function list() : ?array
    {
        $sql = "SELECT ID, FIRSTNAME, LASTNAME FROM CONTACT";
        
        Database::open();
        $record =  Database::query($sql);
        $items = null;
        foreach($record as $item)
        {
            $contact = new Contact();
            $contact->setId($item["ID"]);
            $contact->setFirstName($item["FIRSTNAME"]);
            $contact->setLastName($item["LASTNAME"]);
            $items[] = $contact;
        }    
                
        return  $items;
    }     
}
