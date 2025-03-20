<?php

class RoleValidator 
{
    private Role $_object;
    private string $_message = "";
    
    public  static function instance(Role &$object): RoleValidator
    {
        $validator = new RoleValidator();      
        $validator->_object = $object;
        return $validator;
    }
    

    public function validate(): bool
    {     
        if (empty($this->_object->getName()))
        {
            $this->_message = "O campo 'Nome' deve ser preenchido.";
            return false;
        }
        
        $size = strlen($this->_object->getName());
        if ($size < 3 || $size > 50)
        {
            $this->_message = "O número de caracteres do campo 'Nome' deve ser entre 3 e 50 caracteres. .";
            return false;
        }
        
        if (!preg_match("/^[a-zA-Z\d]+$/", $this->_object->getName()))
        {
            $this->_message = "Caracteres inválidos no campo 'Nome'.";
            return false;            
        }
        
        if (!empty($this->_object->getDescription()))
        {
            if (strlen($this->_object->getDescription()) > 255)
            {
                $this->_message = "O limite máximo de caracteres do campo 'Descrição' é 255.";
                return false;
            }
        }        
        
        return true;
    }
    
    public function get(): string
    {
        return $this->_message;
    }
}
