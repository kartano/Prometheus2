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
    protected static $layout;

    /**
     * PageRenderer constructor.
     * @param DB\PromDB $database The DB connect to use.
     * @param PageOptions $options The page options to driver rendering.
     * @param PageLayout $layout The layout engine to use to arrange the page.
     */
    public function __construct(DB\PromDB $database, PageOptions $options, PageLayout $layout)
    {
        $this->database = $database;
        $this->options = $options;
        $this->layout = $layout;
    }

    /**
     * Render the entire page.
     * @return void
     */
    public function renderPage(): void
    {
        // TO DO:  Render the entire page.
    }

    /**
     * Render HEAD node.
     * @return void
     */
    protected function renderHead(): void
    {
        ?>
        <head>
        </head>
        <?php
        // TO DO:  render the HEAD option.
    }

    /**
     * Render the BODY node.
     * @return void
     */
    protected function renderBody(): void
    {
        ?>
        <body>
        </body>
        <?php
    }

}
