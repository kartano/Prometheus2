<?php
/**
 * Main page for the prometheus 2 administration screen
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\modules\admin
 *
 * @version         1.0.1           2017-08-27 21:00:00 SM Added use of admin header as section only portion of page.
 */

namespace Prometheus2\common\modules\admin;
use Detection\MobileDetect as Mobile_Detect;
use Prometheus2\common\database as DB;
use Prometheus2\common\pagerendering as Page;

/**
 * Class Prom2Admin
 * @package Prometheus2\common\admin
 */
class Prom2Admin extends Page\PageRenderer
{
    protected $adminheader;

    /**
     * Prom2Admin constructor.
     *
     * @param \Prometheus2\common\database\PromDB           $database
     * @param \Prometheus2\common\pagerendering\PageOptions $options
     */
    public function __construct(DB\PromDB $database, Page\PageOptions $options)
    {
        $this->adminheader=new Prom2AdminHeader($database);
        parent::__construct($database, $options);
    }

    /**
     * Render addtional content within the HEAD of the document.
     * @return void
     */
    protected function renderHeadContent(): void
    {
        // Render the custom HEAD content.
    }

    /**
     * Render the header
     * @return void
     */
    protected function renderHeader(): void
    {
        $this->adminheader->renderPage();
    }
    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        $mobile=new Mobile_Detect();
        echo "<pre>";
        print_r($mobile->getHttpHeaders());
        print_r($mobile->getUserAgent());
        echo "</pre>";
    }

    /**
     * Render any custom JS to go into the document ready script.
     * @return void
     */
    protected function renderDocumentReady(): void
    {
        // Render document ready script.
    }

    /**
     * Render custom JS.
     * @return void
     */

    protected function renderCustomJS(): void
    {
        // Render any custom JS code here - functions NOT executed within document ready.
    }

    /**
     * Render the footer.
     * @return void
     */
    protected function renderFooter(): void
    {
        // Render any custom HTML to be displayed in the footer of the page.
    }

}