<?php

namespace TapItTest;


class View
{
    public $view = '';
    public $data = [];

    /** use in master template
     *
     * @return string
     * @throws \Exception
     */
    public function includeContentView()
    {
        // we need this so we'll be able to use
        // variables in content view templates
        foreach ($this->data as $name => $value) {
            global $$name;
            $$name = $value;
        }

        if ($this->view) {
            $viewName = "../src/views/{$this->view}.php";
            if (file_exists($viewName)) {
                ob_start();
                include $viewName;
                return ob_get_clean();
            } else {
                throw new \Exception("missing view `$viewName` file");
            }
        }

        return '';
    }

    public function render()
    {
        // we need this so we'll be able to use
        // variables in master template
        foreach ($this->data as $name => $value) {
            global $$name;
            $$name = $value;
        }

        $master = '../src/views/master.php';
        ob_start();
        require($master);

        return ob_get_clean();
    }


}