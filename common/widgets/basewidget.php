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
        $page->registerWidget($this);
    }

    /**
     * HTML code rendered automatically within <HEAD>...</HEAD> when the page is rendered.
     * This is meant to be used to include separate STYLE elements or external libraries and such.
     */
    public function customHead(): void
    {
        //
    }

    /**
     * Any code that this widget needs to be initialized should be placed inside this though.
     */
    public function headDocumentReady(): void
    {
        //
    }

    /**
     * Any customised JS code needed by the Widget to execute will be placed in here.
     */
    public function customJS(): void
    {
        //
    }

    /**
     * Your rendering code for your page can use this to render the actual widnet wherever you need it on the physical page.
     * The page rendered engine DOES NOT DO THAT FOR YOU!  It only sets up and configures it!
     */
    public function renderWidget(): void
    {
        //
    }
}