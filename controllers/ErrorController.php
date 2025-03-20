<?php

class ErrorController extends Controller
{
    function show (): void
    {
        $message = "";       
        if (key_exists("error", $_GET))
        {
            $message = $_GET["error"];
        }
        
        $model = (object)array("message" => $message);
        $this->view($model);
       
    }
}
