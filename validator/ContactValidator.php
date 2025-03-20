<?php

class ContactValidator 
{
    private Contact $_object;
    private string $_message = "";
    
    public  static function instance(Contact &$object): ContactValidator
    {
        $validator = new ContactValidator();      
        $validator->_object = $object;
        return $validator;
    }
    

    public function validate(): bool
    {
        
        if (empty($this->_object->getFirstName()))
        {
            $this->_message = "O campo 'Nome' deve ser preenchido.";
            return false;
        }
        
        $size = strlen($this->_object->getFirstName());
        if ($size < 3 || $size > 50)
        {
            $this->_message = "O número de caracteres do campo 'Nome' deve ser entre 3 e 50 caracteres. .";
            return false;
        }
        
        if (!preg_match("/^[a-zA-Z\d]+$/", $this->_object->getFirstName()))
        {
            $this->_message = "Caracteres inválidos no campo 'Nome'.";
            return false;            
        }
        
        if (empty($this->_object->getLastName()))
        {
            $this->_message = "O campo 'Sobrenome' deve ser preenchido.";
            return false;
        }
        
        $size = strlen($this->_object->getLastName());
        if ($size < 3 || $size > 50)
        {
            $this->_message = "O número de caracteres do campo 'Sobrenome' deve ser entre 3 e 50 caracteres. .";
            return false;
        }
        
        if (!preg_match("/^[a-zA-Z\d]+$/", $this->_object->getLastName()))
        {
            $this->_message = "Caracteres inválidos no campo 'Sobrenome'.";
            return false;            
        }                     
        return true;
    }
    
    public function get(): string
    {
        return $this->_message;
    }
}
