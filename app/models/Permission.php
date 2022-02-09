<?php

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Permission
 * 
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 *
 * @method validate_permission($account_id, $url)
 */
class Permission extends MY_Model
{

    public $table_name = 'permissions';
    public $primary_key = 'id';
    public $date_format = 'datetime';
    protected $soft_deletes = FALSE;
    protected $log_user = FALSE;
    protected $set_created = TRUE;
    protected $set_modified = TRUE;



}
