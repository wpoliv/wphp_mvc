<?php

class UserViewModelValidator 
{
    private UserViewModel $_object;
    private string $_message = "";
    
    public  static function instance(UserViewModel &$object): UserViewModelValidator
    {
        $validator = new UserViewModelValidator();      
        $validator->_object = $object;
        return $validator;
    }
    

    public function validate(): bool
    {      
        if ($this->_object->getRoleId() == 0)
        {
            $this->_message = "O perfil do usuário deve ser selecionado.";
            return false;            
        }
        
        if (!$this->_object->getNewRecord() && $this->_object->getPassword() == "")
        {
            return true;
        }
        
        if (empty($this->_object->getPassword()))
        {
            $this->_message = "O campo 'Senha' deve ser preenchido.";
            return false;
        }
        
        $size = strlen($this->_object->getPassword());
        if ($size < 3 || $size > 50)
        {
            $this->_message = "O número de caracteres do campo 'Senha' deve ser entre 5 e 50 caracteres. .";
            return false;
        }        
        
        if ( $this->_object->getPassword() != $this->_object->getConfirm())
        {
            $this->_message = "A senha não foi confirmada!";
            return false;
        }
       
       
        return true;
    }
    
    public function get(): string
    {
        return $this->_message;
    }
}
