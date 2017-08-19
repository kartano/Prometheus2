<?php
/**
 * Page rendering class options
 *
 * @author   Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\pagerendering
 *
 * @version         1.0.0        2017-08-18 12:39:00 Prototype
 */

/**
 * @property bool uses_jquery Flag to determine if header needs to include jquery.
 * @property bool uses_jqueryui Flag to determine if header needs to include jquery ui
 * @property bool display_header Flag to determine if page <header> element is rendered.
 * @property bool display_footer Flag to determine if page <footer> element is rendered.
 */
namespace Prometheus2\common\pagerendering;

/**
 * Class PageOptions
 * @package Prometheus2\common\pagerendering
 */
class PageOptions
{
    /**
     * @var array The array of settings
     */
    protected $data=[];

    /**
     * PageOptions constructor.
     */
    public function __construct()
    {
        $this->uses_jquery=true;
        $this->uses_jqueryui=true;
        $this->display_header=true;
        $this->display_footer=true;
    }

    /**
     * @param string $name The name of the setting.
     * @return mixed The return value. NULL of setting not found.
     */
    public function __get(string $name): mixed
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     * Magic method - set.
     *
     * @param string $name The name of the setting.
     * @param mixed $value The value for the setting.
     */
    public function __set(string $name , mixed $value)
    {
        $this->data[$name]=$value;
    }

}