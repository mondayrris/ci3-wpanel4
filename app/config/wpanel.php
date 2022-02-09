<?php

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Defines the website template.
 */
$config['template'] = 'default';

/**
 * Defines the delimiters of the Validator library error messages.
 */
$config['validator_error_delimiters'] = array(
    'open' => '<p><span class="label label-danger">',
    'close' => '</span></p>'
);

/**
 * Defines available languages.
 */
$config['available_languages'] = array(
    'english' => array('locale' => 'en', 'label' => 'English'),
    'portuguese' => array('locale' => 'pt_BR', 'label' => 'Portuguese')
);

/**
 * Defines the date format for the user.
 */
$config['user_date_format'] = '%d/%m/%Y';

/**
 * Defines the date format for the database.
 */
$config['db_date_format'] = '%Y-%m-%d';

/**
 * Defines the date and time format for the user.
 */
$config['user_datetime_format'] = '%d/%m/%Y %H:%i:%s';

/**
 * Defines the date and time format for the database.
 */
$config['db_datetime_format'] = '%Y-%m-%d %H:%i:%s';

/**
 * Defines the text editors available on the system.
 */
$config['available_editors'] = array('ckeditor'=>'CKEditor', 'tinymce'=>'TinyMCE');

/**
 * Defines what types of users will be allowed on the site.
 */
$config['users_role'] = array('user' => 'Common User', 'admin' => 'Administrator');

/**
 * Defines the views available for displaying post lists.
 */
$config['posts_views'] = array('list' => 'Listing', 'mosaic' => 'Mosaic');

/**
 * Defines the positions of banners on the site to be listed in the
 * control panel.
 */
$config['banner_positions'] = array('slide' => 'Slide-Show', 'sidebar' => 'Sidebar');

/**
 * Defines the functional links for the menu manager.
 */
$config['functional_links'] = array(
    'home' => 'Home page',
    'contact' => 'Contact page',
    'albums' => 'Photo gallery',
    'videos' => 'Video gallery',
    'events' => 'List of events',
    'pool' => 'Poll List',
    'users' => 'User area',
    'rss' => 'RSS Page',
);