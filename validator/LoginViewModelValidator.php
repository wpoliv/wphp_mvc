<?php

class LoginViewModelValidator 
{
    private LoginViewModel $_object;
    private string $_message = "";
    
    public  static function instance(LoginViewModel &$object): LoginViewModelValidator
    {
        $validator = new LoginViewModelValidator();      
        $validator->_object = $object;
        return $validator;
    }
    

    public function validate(): bool
    {     
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
        /*    
        if (empty($this->_object->getNewPassword()))
        {
            $this->_message = "O campo 'Nova senha' deve ser preenchido.";
            return false;
        }
        
        $size = strlen($this->_object->getNewPassword());
        if ($size < 3 || $size > 50)
        {
            $this->_message = "O número de caracteres do campo 'Nova Senha' deve ser entre 5 e 50 caracteres. .";
            return false;
        }
        
        if ( $this->_object->getNewPassword() != $this->_object->getConfirmPassword())
        {
            $this->_message = "A senha não foi confirmada!";
            return false;
        }
        */
       
        return true;
    }
    
    public function get(): string
    {
        return $this->_message;
    }
}
