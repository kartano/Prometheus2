<?php
/**
 * Datagrid callback for rendering JS functions.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\widgets
 *
 * @version         1.0.0           2017-09-20 2017-09-20 Prototype
 */


namespace Prometheus2\common\widgets;

/**
 * Interface IDataGridJSCallBacks
 * @package Prometheus2\common\widgets
 */
interface IDataGridJSCallBacks
{
    public function renderAddRecordJS(): void;
    public function renderEditRecordJS(): void;
    public function renderDeleteRecordJS(): void;
}
