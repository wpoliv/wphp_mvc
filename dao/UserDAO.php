<?php

class UserDAO
{
    function insert(User $user): void
    {
        $sql = "INSERT INTO USER (CONTACTID, LOGIN, ROLEID, PASSWORD, ACTIVE) VALUES (:CONTACTID, :LOGIN, :ROLEID, :PASSWORD, 1)";
        $params = array(":CONTACTID" => $user->getId(), ":LOGIN" => $user->getLogin(), ":ROLEID" => $user->getRole()->getId(), ":PASSWORD" => $user->getPassword());
        Database::open();
        Database::execute($sql, $params);
    }

    function updateRole(int $userId, int $roleId): void
    {
        $sql = "UPDATE USER SET ROLEID = :ROLEID WHERE CONTACTID = :CONTACTID";
        $params = array(":CONTACTID" => $userId, ":ROLEID" => $roleId);
        Database::open();
        Database::execute($sql, $params);
    }
    
    function updateLogin(int $userId, string $login): void
    {
        $sql = "UPDATE USER SET LOGIN = :LOGIN WHERE CONTACTID = :CONTACTID";
        $params = array(":CONTACTID" => $userId, ":LOGIN" => $login);
        Database::open();
        Database::execute($sql, $params);
    }

    
    function updatePassword(int $userId, string $password): void
    {
        $sql = "UPDATE USER SET PASSWORD = :PASSWORD WHERE CONTACTID = :CONTACTID";
        $params = array(":CONTACTID" => $userId, ":PASSWORD" => $password);
        Database::open();
        Database::execute($sql, $params);
    }
    
    function  get($id): ?User
    {
        $sql =  "SELECT CONTACT.ID, FIRSTNAME, LASTNAME, LOGIN, ACTIVE, ROLEID, NMROLE, DSROLE FROM CONTACT " .
                "LEFT JOIN USER ON USER.CONTACTID = CONTACT.ID " .
                "LEFT JOIN ROLE ON ROLE.ID = USER.ROLEID " .
                "WHERE CONTACT.ID = :ID";
        $params = array(":ID" => $id);
        
        Database::open();
        $item = Database::get($sql, $params);
        $user = null;
        if ($item)
        {
            $user = new User();
            $user->setId($item["ID"]);
            $user->setFirstName($item["FIRSTNAME"]);
            $user->setLastName($item["LASTNAME"]);
            $user->setLogin($item["LOGIN"]);
            if ($item["ROLEID"] != null)
            {
                $user->setActive($item["ACTIVE"]);

                $user->getRole()->setId($item["ROLEID"]);
                $user->getRole()->setName($item["NMROLE"]);
                $user->getRole()->setDescription($item["DSROLE"]);
            }
        }
        return $user;
    } 
    
    function  login(string $login, string $password): ?User
    {
        $sql =  "SELECT CONTACT.ID, FIRSTNAME, LASTNAME, LOGIN, ACTIVE, ROLEID, NMROLE, DSROLE FROM CONTACT " .
                "INNER JOIN USER ON USER.CONTACTID = CONTACT.ID " .
                "INNER JOIN ROLE ON ROLE.ID = USER.ROLEID " .
                "WHERE LOGIN = :LOGIN AND PASSWORD = :PASSWORD";
        $params = array(":LOGIN" => $login, ":PASSWORD" => $password);
        
        Database::open();
        $item = Database::get($sql, $params);
        $user = null;
        if ($item)
        {
            $user = new User();
            $user->setId($item["ID"]);
            $user->setFirstName($item["FIRSTNAME"]);
            $user->setLastName($item["LASTNAME"]);
            $user->setLogin($item["LOGIN"]);
            $user->setActive($item["ACTIVE"]);          
            $user->getRole()->setId($item["ROLEID"]);
            $user->getRole()->setName($item["NMROLE"]);
            $user->getRole()->setDescription($item["DSROLE"]);
        }
        return $user;
    }       
}
