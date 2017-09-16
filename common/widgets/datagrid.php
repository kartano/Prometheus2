<?php
/**
 * Data grid for use formatting tables in displays.  Normally used for admin pages.
 *
 * @author          Simon Mitchell <kartano@gmail.com?
 *
 * @namespace       Prometheus2\common\widgets
 *
 * @version         1.0.0           2017-09-11 2017-09-11 Prototype
 */

namespace Prometheus2\common\widgets;

use Prometheus2\Common\database as DB;
use Prometheus2\Common\pagerendering as PAGE;

/**
 * Class DataGrid
 * @package Prometheus2\common\widgets
 */
class DataGrid extends BaseWidget implements \Iterator, \ArrayAccess
{
    /**
     * @var array Collection of DataGridColumn objects.
     */
    protected $arrColumns;

    /**
     * @var int Position used by Iterator interface
     */
    protected $position = 0;

    /**
     * @var string The caption for this table.
     */
    protected $caption='';

    /**
     * DataGrid constructor.
     * @param DB\PromDB $database
     * @param PAGE\PageRenderer $page
     * @param string $caption
     */
    public function __construct(DB\PromDB $database, PAGE\PageRenderer $page, $caption='')
    {
        $this->arrColumns = [];
        $this->caption=$caption;
        parent::__construct($database, $page);
    }

    /**
     * @param string $columnName
     * @param string $scope
     * @param string $datatitle
     *
     * @return \Prometheus2\common\widgets\DataGridColumn
     */
    public function addColumn(string $columnName, string $scope, string $datatitle): DataGridColumn
    {
        $datagridcol = new DataGridColumn($columnName, $scope, $datatitle);
        $this->arrColumns[] = $datagridcol;
        return $datagridcol;
    }

    /**
     * HTML code rendered automatically within <HEAD>...</HEAD> when the page is rendered.
     * This is meant to be used to include separate STYLE elements or external libraries and such.
     */
    public function customHead(): void
    {
        ?>
        <style>
            <?php
            require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'table.css';
            ?>
        </style>
        <?php
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

    private function openTable(): void
    {
        ?>
        <div class="container">
            <table class="responsive-table">
        <?php
    }

    private function displayTHead(): void
    {

    }

    private function closeTable(): void
    {
        ?>
            </table>
        </div>
        <?php
    }

    /**
     * Your rendering code for your page can use this to render the actual widnet wherever you need it on the physical page.
     * The page rendered engine DOES NOT DO THAT FOR YOU!  It only sets up and configures it!
     */
    public function renderWidget(): void
    {
        //
    }

    /**
     * Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Iterator interface
     * @return mixed
     */
    public function current()
    {
        return $this->arrColumns[$this->position];
    }

    /**
     * Iterator interface
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Iterator interface.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Iterator interface
     * @return bool
     */
    public function valid()
    {
        return isset($this->arrColumns[$this->position]);
    }

    /**
     * ArrayAccess interface.
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->arrColumns[] = $value;
        } else {
            $this->arrColumns[$offset] = $value;
        }
    }

    /**
     * ArrayAccess inteface.
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->arrColumns[$offset]);
    }

    /**
     * ArrayAccess interface.
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->arrColumns[$offset]);
    }

    /**
     * ArrayInterface.
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->arrColumns[$offset]) ? $this->arrColumns[$offset] : null;
    }
}

/**
 * Class DataGridColumn
 * @package Prometheus2\common\widgets
 */
class DataGridColumn
{
    protected $columnName;
    protected $scope;
    protected $datatTitle;

    /**
     * DataGridColumn constructor.
     *
     * @param string $columnName
     * @param string $scope
     * @param string $datatitle
     */
    public function __construct(string $columnName, string $scope, string $datatitle)
    {
        $this->columnName = $columnName;
        $this->scope = $scope;
        $this->datatTitle = $datatitle;
    }
}