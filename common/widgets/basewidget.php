<?php
/**
 * The base widget class.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\widgets
 *
 * @version         1.0.0           2017-09-11 2017-09-11 Prototype
 */


namespace Prometheus2\common\widgets;
use Prometheus2\Common\database AS DB;
use Prometheus2\Common\pagerendering AS PAGE;

/**
 * Class BaseWidget
 * @package Prometheus2\common\widgets
 */
abstract class BaseWidget
{
    protected $database;
    protected $page;

    /**
     * BaseWidget constructor.
     *
     * @param \Prometheus2\Common\database\PromDB            $database
     * @param \Prometheus2\Common\pagerendering\PageRenderer $page
     */
    public function __construct(DB\PromDB $database, PAGE\PageRenderer $page)
    {
        $this->database=$database;
        $this->page=$page;
    }
}