<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @return CI_Controller|MY_Controller
 * @noinspection PhpUnnecessaryLocalVariableInspection
 */
function get_CI_instance()
{
    /** @var MY_Controller $CI */
    $CI =& get_instance();
    return $CI;
}