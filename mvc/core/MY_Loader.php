<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * View Loader
     *
     * Loads "view" files from theme or view folder.
     *
     * @param string $view View name
     * @param array $vars An associative array of data
     *    to be extracted for use in the view
     * @param bool $return Whether to return the view output
     *    or leave it to the Output class
     * @return void
     */
    public function theme($view, $vars = array(), $return = FALSE)
    {
        $vars = (object) $vars;
        $themeFile = $view.'.php';
        if(file_exists($themeFile)) {
            return $this->_ci_load(array('_ci_path' => $themeFile, '_ci_view' => $view, '_ci_vars' => get_object_vars($vars), '_ci_return' => $return));
        }
        return $this->view($view, $vars, $return);        // Get from views
    }
}
