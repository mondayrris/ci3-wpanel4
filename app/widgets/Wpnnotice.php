<?php

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Wpanel notice class.
 *
 * @property CI_Loader $load
 * @property $session
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Wpnnotice extends Widget
{

    /**
     * Main method of the widget.
     * 
     * @return void
     */
    public function main()
    {
        $data = $this->session->flashdata('_notice');
        if (count($data))
            $this->load->view('widgets/notice', $data);
    }

}
