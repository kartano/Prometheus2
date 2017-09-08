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
use Prometheus2\common\database as DB;
use Prometheus2\common\pagerendering as Page;
use Prometheus2\common\exceptions AS Exceptions;

/**
 * Class Prom2Admin
 * @package Prometheus2\common\admin
 */
class Prom2Admin extends Page\PageRenderer
{
    /**
     * @var Prom2AdminHeader The page render object that does nothing but render the HEADER section of the BODY document.
     */
    protected $adminheader;

    /**
     * Prom2Admin constructor.
     *
     * @param \Prometheus2\common\database\PromDB           $database
     * @param \Prometheus2\common\pagerendering\PageOptions $options
     *
     * @throws \Prometheus2\common\exceptions\NotLoggedInException If login failed.
     */
    public function __construct(DB\PromDB $database, Page\PageOptions $options)
    {
        $this->adminheader=new Prom2AdminHeader($database);
        try {
            parent::__construct($database, $options);
        } catch (Exceptions\NotLoggedInException $exception) {
            throw $exception;
        }
    }

    /**
     * Render additional content within the HEAD of the document.
     * @return void
     */
    protected function renderHeadContent(): void
    {
        ?>
        <style>
            <?php
            require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'admin.css';
            ?>
        </style>
        <?php
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
?>
        <div class="admin_container">
            <div class="lhs_menu menu_border">
                <ul id="menu">
                    <li class="ui-widget-header"><div>Admin</div></li>
                    <li><div id="home_button"><i class="fa fa-home blue" aria-hidden="true"></i>&nbsp;Home</div></li>
                    <li><div id="users_button"><i class="fa fa-users green" aria-hidden="true"></i>&nbsp;Users</div></li>
                    <li class="ui-widget-header"><div>Modules</div></li>
                    <li><div>Option 4</div></li>
                    <li><div>Option 5</div></li>
                    <li><div>Option 6</div></li>
                </ul>
            </div>
            <div class="rhs_screen option_border">
                <iframe class="content_frame" id="content_frame">
                </iframe>
            </div>
        </div>
        <?php
    }

    /**
     * Render any custom JS to go into the document ready script.
     * @return void
     */
    protected function renderDocumentReady(): void
    {
        ?>
        $( "#menu" ).menu({
            items: "> :not(.ui-widget-header)"
        });
        $( "#home_button" ).click(function() {
            window.location = '\index.php';
        });
        $( "#users_button" ).click(function() {
            $("#content_frame").attr("src", "admin/useradminpage.php");
        });
        <?php
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
        ?>
        <hr>
        <p><i>Brought to you by SunsetCoders. &copy;<?= date('Y'); ?></i></p>
        <?php
    }
}