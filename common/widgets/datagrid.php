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
class DataGrid extends BaseWidget
{
    /**
     * @var array Collection of DataGridColumn objects.
     */
    protected $arrColumns;

    /**
     * DataGrid constructor.
     *
     * @param \Prometheus2\Common\database\PromDB            $database
     * @param \Prometheus2\Common\pagerendering\PageRenderer $page
     */
    public function __construct(DB\PromDB $database, PAGE\PageRenderer $page)
    {
        $this->arrColumns=[];
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
        $datagridcol=new DataGridColumn($columnName, $scope, $datatitle);
        $this->arrColumns[]=$datagridcol;
        return $datagridcol;
    }

    public function renderHead()
    {
        // Bring in the CSS needed.
    }

    public function renderBody()
    {
        // Render the actual table.
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
        $this->columnName=$columnName;
        $this->scope=$scope;
        $this->datatTitle=$datatitle;
    }
}