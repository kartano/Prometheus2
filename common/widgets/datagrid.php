<?php
/**
 * Data grid for use formatting tables in displays.  Normally used for admin pages.
 *
 * @author          Simon Mitchell <kartano@gmail.com?
 *
 * @namespace       Prometheus2\common\widgets
 *
 * @see             https://fiddle.jshell.net/shailesh_sal/o87z8yv6/1/
 *
 * @version         1.0.0           2017-09-11 2017-09-11 Prototype
 * @version         1.0.1           2017-09-19 11:39:00  SM:  The table.css is only imported ONCE if multiple datagrids
 *                  appear on the same page.
 */

namespace Prometheus2\common\widgets;

use Prometheus2\Common\database as DB;
use Prometheus2\Common\pagerendering as PAGE;
use Prometheus2\Common\Settings AS Settings;

/**
 * Class DataGrid
 * @package Prometheus2\common\widgets
 */
class DataGrid extends BaseWidget implements \Iterator, \ArrayAccess, \Countable
{
    protected static $cssAlreadyIncluded=false;
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
    protected $caption = '';

    /**
     * @var string The caption text to show in the footer.
     */
    protected $footercaption = '';

    /**
     * @var bool If TRUE then the ADD, EDIT and DELETE buttons will be added to the table.
     */
    protected $allowCRUDoperations=false;

    protected $idQueryField='';

    protected $callbackRenderingObject=null;

    /**
     * DataGrid constructor.
     *
     * @param DB\PromDB         $database
     * @param PAGE\PageRenderer $page
     * @param string            $caption
     */
    public function __construct(DB\PromDB $database, PAGE\PageRenderer $page, string $widgetID, string $caption = '', string $footercaption = '', bool $allowCRUDoperations=false, string $idQueryField='')
    {
        $this->arrColumns = [];
        $this->caption = $caption;
        $this->allowCRUDoperations=$allowCRUDoperations;
        $this->idQueryField=$idQueryField;
        parent::__construct($database, $page, $widgetID);
    }

    /**
     * @param \Prometheus2\common\widgets\IDataGridJSCallBacks $callbacks
    */
    public function setCallbackRenderingObject(IDataGridJSCallBacks $callbacks)
    {
        if (!$this->allowCRUDoperations) {
            throw new \RuntimeException(__METHOD__." called to set a callback for widget {$this->widgetID} but this widget has CRUD operations turned off.");
        }
        $this->callbackRenderingObject=$callbacks;
    }

    /**
     * @param string $columnName
     * @param string $scope
     * @param string $datatitle
     * @param string $queryFieldName
     * @param string $fieldFormat
     *
     * @return \Prometheus2\common\widgets\DataGridColumn
     */
    public function addColumn(string $columnName, string $scope, string $datatitle, string $queryFieldName, string $fieldFormat=DataGridColumn::STRING_FORMAT): DataGridColumn
    {
        $datagridcol = new DataGridColumn($columnName, $scope, $datatitle, $queryFieldName, $fieldFormat);
        $this->arrColumns[] = $datagridcol;
        return $datagridcol;
    }

    /**
     * HTML code rendered automatically within <HEAD>...</HEAD> when the page is rendered.
     * This is meant to be used to include separate STYLE elements or external libraries and such.
     */
    public function customHead(): void
    {
        if (self::$cssAlreadyIncluded) {
            return;
        }
        self::$cssAlreadyIncluded=true;
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
        ?>
        $("#add_record_button_<?=$this->widgetID;?>").button({icon: "ui-icon-plus", iconPosition: "beginning"})
        .click(function(){
            <?php
            if ($this->callbackRenderingObject!==null) {
                call_user_func([$this->callbackRenderingObject,'renderAddRecordJS']);
            }
            ?>
        });
        <?php
    }

    public function customJS(): void
    {
        if (!$this->callbackRenderingObject!==null) {
            ?>
            function delete_<?=$this->widgetID;?>(item_id)
            {
                <?php
                call_user_func([$this->callbackRenderingObject,'renderDeleteRecordJS']);
                ?>
            }
            function edit_<?=$this->widgetID;?>(item_id)
            {
                <?php
                call_user_func([$this->callbackRenderingObject,'renderEditRecordJS']);
                ?>
            }
            <?php
        }
    }

    /**
     * @return \Prometheus2\common\widgets\DataGrid
     */
    private function openTable(): DataGrid
    {
        ?>
        <div class="container">
        <table class="responsive-table">
        <caption><?=Settings\Language::translate($this->caption);?></caption>
        <?php
        return $this;
    }

    /**
     * @return \Prometheus2\common\widgets\DataGrid
     */
    private function displayTHead(): DataGrid
    {
        ?>
        <thead>
        <?php
            if ($this->allowCRUDoperations) {
                ?>
                <tr>
                    <td colspan="<?=count($this)+1;?>">
                        <div class="div_crud_control_container">
                            <div class="div_crud_control_button">
                                <button id="add_record_button_<?=$this->widgetID;?>">Add New Record</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
            }
         ?>
        <tr>
            <?php
            foreach ($this->arrColumns as $column) {
                ?>
                <th scope="<?=$column->scope; ?>"><?= $column->columnName; ?></th>
                <?php
            }
            if ($this->allowCRUDoperations) {
                ?>
                <th class="controlColumn"></th>
                <?php
            }
            ?>
        </tr>
        </thead>
        <?php
        return $this;
    }

    /**
     * @return \Prometheus2\common\widgets\DataGrid
     */
    private function displayTFoot(): DataGrid
    {
        ?>
        <tfoot>
        <tr>
            <td colspan="<?= count($this); ?>"><?= $this->footercaption; ?></td>
        </tr>
        </tfoot>
        <?php
        return $this;
    }

    /**
     * @param \mysqli_stmt $statement
     * @param array        ...$args
     *
     * @return \Prometheus2\common\widgets\DataGrid
     * @throws \Prometheus2\common\exceptions\DatabaseException If the count of fields does not match the count of
     *                                                          fields in the statement.
     */
    private function displayTBody(\mysqli_stmt $statement): DataGrid
    {
        $result = $statement->get_result();
        ?>
        <tbody>
        <?php
        while ($row = $result->fetch_array()) {
            $firstcolumn = true;
            $id=$row[$this->idQueryField];
            ?>
            <tr data-id="<?=$id;?>">
                <?php
                foreach ($this as $column) {
                    $value=$column->formatValue($row[$column->queryFieldName]);
                    if ($firstcolumn) {
                        ?>
                        <th scope="row"><?= $value; ?></th>
                        <?php
                        $firstcolumn = false;
                    } else {
                        ?>
                        <td data-title="PrefName"><?= $value; ?></td>
                        <?php
                    }
                }
                if ($this->allowCRUDoperations) {
                    ?>
                    <td>
                        <div class="div_crud_control_container">
                            <div class="div_crud_control_icon"></div><span class="table_green has_pointer" onclick="edit_<?=$this->widgetID;?>(<?=$id;?>);"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></div>
                            <div class="div_crud_control_icon"><span class="table_red has_pointer" onclick="delete_<?=$this->widgetID;?>(<?=$id;?>);"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                        </div>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <?php
        return $this;
    }

    /**
     * @return \Prometheus2\common\widgets\DataGrid
     */
    private function closeTable(): DataGrid
    {
        ?>
        </table>
        </div>
        <?php
        return $this;
    }

    /**
     * @param \mysqli_stmt $statement
     */
    public function renderWidget(\mysqli_stmt $statement=null): void
    {
        if ($this->allowCRUDoperations && $this->callbackRenderingObject===null) {
            throw new \RuntimeException(__METHOD__." for widget with ID {$this->widgetID} had CRUD options turned on, but no callback object set.");
        }
        $this->openTable()->displayTHead()->displayTFoot()->displayTBody($statement)->closeTable();
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
     *
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
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->arrColumns[$offset]);
    }

    /**
     * ArrayAccess interface.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->arrColumns[$offset]);
    }

    /**
     * ArrayInterface.
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->arrColumns[$offset]) ? $this->arrColumns[$offset] : null;
    }

    /**
     * Countable interface.
     * @return int
     */
    public function count(): int
    {
        return count($this->arrColumns);
    }
}

/**
 * Class DataGridColumn
 * @package Prometheus2\common\widgets
 * @property string columnName          The caption for this column.
 * @property string scope               The scope for this column.
 * @property string datatitle           The data title for this column.
 * @property string queryFieldName      The name of the related field in a result query.
 * @property string fieldFormat         The format of the field.
 */
class DataGridColumn
{
    const STRING_FORMAT = 0;
    const DATE_FORMAT = 1;
    const INTEGER_FORMAT = 2;
    const REAL_FORMAT = 3;

    protected $settings = [];

    /**
     * DataGridColumn constructor.
     *
     * @param string $columnName The caption at the top of the table.
     * @param string $scope The scope of the row or cell.
     * @param string $datatitle The data title for the cell.
     * @param string $queryFieldName The name of the field in the query result to use.
     * @param string $fieldFormat The format to use to display the data.
     */
    public function __construct(string $columnName, string $scope, string $datatitle, string $queryFieldName, string $fieldFormat)
    {
        $this->settings['columnName'] = $columnName;
        $this->settings['scope'] = $scope;
        $this->settings['datatitle'] = $datatitle;
        $this->settings['queryFieldName'] = $queryFieldName;
        $this->settings['fieldFormat']= $fieldFormat;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function formatValue($value)
    {
        switch ($this->fieldFormat) {
            case $this::DATE_FORMAT:
                $value = PAGE\formatting::formatDate($value);
                break;
            case $this::INTEGER_FORMAT:
                $value =intval($value);
                break;
            case $this::REAL_FORMAT:
                $value = floatval($value);
                break;
        }
        return $value;
    }

    public function __get($name)
    {
        return array_key_exists($name,$this->settings) ? $this->settings[$name] : null;
    }
}