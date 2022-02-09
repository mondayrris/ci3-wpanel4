<?php

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Wpanel AddThis buttons class.
 * 
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Wpnaddthisbuttons extends Widget
{

    /**
     * Main method of the widget.
     * 
     * @return void
     */
    public function main()
    {
        $this->view('addthisbuttons');
    }

}
