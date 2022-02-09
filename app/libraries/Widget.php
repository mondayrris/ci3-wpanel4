<?php /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */

/**
 * @copyright Eliel de Paula <dev@elieldepaula.com.br>
 * @license http://wpanel.org/license
 */

defined('BASEPATH') OR exit('No direct script access allowed');
const EXT = '.php';

/**
 * Esta classe provê os métodos para o funcionamento do mecanismo de Widget usado
 * no WpanelCMS.
 *
 * @property CI_Loader $load
 * @property CI_Config $config
 * @property CI_Router $router
 * @author Eliel de Paula <dev@elieldepaula.com.br>
 */
class Widget
{

    function __get($var)
    {
        global $CI;
        return $CI->$var;
    }

    /**
     * Initialize the params to the widget.
     *
     * @param array $params
     * @return $this
     */
    public function initialize($params = array())
    {
        foreach ($params as $key => $val)
        {
            if (isset($this->$key))
            {
                $method = 'set_' . $key;
                if (method_exists($this, $method))
                    $this->$method($val);
                else
                    $this->$key = $val;
            }
        }
        return $this;
    }

    /**
     * Load an widget file.
     *
     * @param string $file
     * @param array $param
     * @return mixed
     */
    public function load($file, $param = null)
    {
        $file = ucfirst($file);
        $module = $this->router->module;
        if (($pos = strrpos($file, '/')) !== FALSE)
        {
            $module = substr($file, 0, $pos);
            $file = substr($file, $pos + 1);
        }

        list($path, $file) = $this->_find($file, $module);

        if ($path === FALSE)
            $path = APPPATH . 'widgets/';

        $file = ucfirst($file);

        $this->_load_file($file, $path);

        $widget = new $file($param);
        return $widget->main();
    }

    public function view($view, $data = array(), $string = false)
    {
        $template = ($this->config->item('template') ? $this->config->item('template') : 'default');
        $this->load->view($template . '/widgets/' . $view, $data, $string);
    }

    /**
     * Find some file into the folders.
     *
     * @param String $file
     * @param String $module
     * @return array
     */
    private function _find($file, $module)
    {
        $segments = explode('/', $file);

        $file = array_pop($segments);
        $file_ext = (pathinfo($file, PATHINFO_EXTENSION)) ? $file : $file . EXT;

        $path = ltrim(implode('/', $segments) . '/', '/');
        $module ? $modules[$module] = $path : $modules = array();

        if (!empty($segments))
        {
            $modules[array_shift($segments)] = ltrim(implode('/', $segments) . '/', '/');
        }

        $location = APPPATH . 'modules/';
        $offset = '../modules/';
        foreach ($modules as $module => $subpath)
        {
            $fullpath = $location . $module . '/' . 'widgets/' . $subpath;
            if ('widgets/' == 'libraries/' OR 'widgets/' == 'models/')
            {
                if (is_file($fullpath . ucfirst($file_ext)))
                    return array($fullpath, ucfirst($file));
            }
            else
                /* load non-class files */
                if (is_file($fullpath . $file_ext))
                    return array($fullpath, $file);
        }
        return array(FALSE, $file);
    }

    /**
     * Load some file.
     *
     * @param string $file
     * @param string $path
     * @return void
     */
    private function _load_file($file, $path)
    {
        $type = 'other';
        $file = str_replace(EXT, '', $file);
        $location = $path . $file . EXT;
        if ('other' === 'other')
        {
            if (class_exists($file, FALSE))
            {
                log_message('debug', "File already loaded: {$location}");
                return;
            }
            include_once $location;
        } else
        {
            /* load config or language array */
            include $location;

            if (!isset($$type) OR ! is_array($$type))
                show_error("{$location} does not contain a valid other array");

            $result = $$type;
        }
        log_message('debug', "File loaded: {$location}");
    }

}
