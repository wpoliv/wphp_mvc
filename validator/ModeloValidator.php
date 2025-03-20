<?php

class ModeloValidator 
{
    private Modelo $_object;
    private string $_message = "";
    
    public  static function instance(Modelo &$object): ModeloValidator
    {
        $validator = new ModeloValidator();      
        $validator->_object = $object;
        return $validator;
    }
    

    public function validate(): bool
    {       
        if ($this->_object->getTipo()->getCodigo() == 0)
        {
            $this->_message = "O tipo deve ser selecionada.";
            return false;            
        }
        
        if ($this->_object->getFabricante()->getCodigo() == 0)
        {
            $this->_message = "O fabricante deve ser selecionado.";
            return false;            
        }
        
                if (empty($this->_object->getNome()))
        {
            $this->_message = "O campo 'Nome' deve ser preenchido.";
            return false;
        }
        
        $size = strlen($this->_object->getNome());
        if ($size < 3 || $size > 255)
        {
            $this->_message = "O número de caracteres do campo 'Nome' deve ser entre 3 e 255 caracteres. .";
            return false;
        }

        if (!empty($this->_object->getDescricao()))
        {
            if (strlen($this->_object->getDescricao()) > 255)
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
