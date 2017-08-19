<?php
/**
 * Page rendering engine.
 *
 * @author Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\pagerendering
 *
 * @version     1.0.0           2017-08-18 12:31:00 Prototype.
 */
namespace Prometheus2\common\pagerendering;

use Prometheus2\common\database as DB;

/**
 * Class PageRenderer
 * @package Prometheus2\common\pagerendering
 */
abstract class PageRenderer
{
    protected static $database;
    protected static $options;

    public function __construct(DB\PromDB $database, PageOptions $options)
    {
        $this->database=$database;
        $this->options=$options;
    }
}
