<?php


class Message
{
    private string $_title = "";
    private $_messages = array();
    private bool $_close = false;
    private int $dialogType = 1;
    
    public function setCloseButton(bool $val) : void
    {
        $this->_close = $val;
    }
    
    public function setDialogType(int $type)
    {
        $this->dialogType = $type;
    }


    public function setTitle(string $title) : void
    {
        $this->_title = $title;
    }
    
    public function setMessage(string $message): void
    {
        $this->_messages[] = $message;
    }
    
    public function render() : string
    {
        $alert = "";
        if (($this->_messages))
        {
            $dialogTypeText = "danger";
            switch ($this->dialogType)
            {
                case 1:
                {
                    $dialogTypeText = "success";
                    break;
                }
            }
            
            $heading = ($this->_title != "" ? "<h4 class='alert-heading'>{$this->_title}</h4>" : "");
            $close = ($this->_close ? "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>" : "");
            $alert = "<div class='alert alert-{$dialogTypeText} alert-dismissible fade show' role='alert'>";
            $content = "";
            foreach ($this->_messages as $message)
            {
                $content .= "<p>{$message}</p>";
            }
            $alert .= "{$heading}{$content}{$close}</div>";
        }
        return $alert;
    }
}
