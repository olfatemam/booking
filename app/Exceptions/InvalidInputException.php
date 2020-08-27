<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Exceptions;

/**
 * Description of InvalidInputException
 *
 * @author Olfat.Emam
 */
class InvalidInputException extends Exception{
    //put custom codes list  here
    private $html_code;
    
    public function __constructor($message, $code, $html_code)
    {
        $this->$html_code=$html_code;
    }
    public function getHtmlCode()
    {
        return $this->$html_code;
    }
    
}
