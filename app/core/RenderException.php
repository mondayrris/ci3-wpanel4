<?php

class RenderException extends Exception
{

    /**
     * @param $string
     * @param $code
     * @param $previous
     */
    public function __construct($string = '', $code = 0, $previous = NULL)
    {
        parent::__construct($string, $code, $previous);
    }
}