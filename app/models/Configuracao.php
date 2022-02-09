<?php

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Configuracao
 *
 * @property Wpanel $wpanel
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Configuracao extends MY_Model
{

    /**
     * Class constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Load json config file or item.
     *
     * @return mixed
     */
    public function load_config()
    {
        return $this->wpanel->read_json(FCPATH . 'config/config.json');
    }

    /**
     * Save a config file.
     * 
     * @param mixed $data
     * @return boolean
     */
    public function save_config($data)
    {
        $json = json_encode($data);
        if($this->wpanel->write_json($data, FCPATH . 'config/config.json', $json))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Return a config item.
     * 
     * @param string $item
     * @return mixed
     */
    public function get_config($item = null)
    {
        $object = $this->load_config();
        return $object->{$item};
    }

}
