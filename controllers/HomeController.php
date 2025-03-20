<?php

class HomeController extends Controller
{
    function index()
    {      
        $model = (object)array("message" => "");
        $model->message = "Painel";
        return $this->view($model);
    }
    
    function details($id = null)
    {
        $this->authenticate("admtable");
        
        $model = array ("title" => "Details do Id: {$id}",
            "body" => "<p>This is the line 1.</p><p>This is the line 2.</p>");
        
        return $this->view((object)$model);
    }
}