<?php
/**
 * Page rendering engine.
 *
 * @author      Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\pagerendering
 *
 * @version     1.0.0           2017-08-18 12:31:00 Prototype.
 */

namespace Prometheus2\common\pagerendering;

use Prometheus2\common\database as DB;
use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class PageRenderer
 * @package Prometheus2\common\pagerendering
 */
abstract class PageRenderer
{
    /**
     * @var DB\PromDB The database we use.
     */
    protected $database;
    /**
     * @var PageOptions The page options.
     */
    protected $options;

    /**
     * PageRenderer constructor.
     *
     * @param DB\PromDB   $database The DB connect to use.
     * @param PageOptions $options  The page options to driver rendering.
     */
    public function __construct(DB\PromDB $database, PageOptions $options)
    {
        $this->database = $database;
        $this->options = $options;
    }

    /**
     * Render the entire page.
     * @return void
     */
    public function renderPage(): void
    {
        $starttime = microtime(true);
        if (!$this->options->render_body_only) {
            $this->renderHead();
            $this->renderBody($starttime);
        } else {
            ?>
            <script type="text/javascript">
                <?php
                $this->renderCustomJS();
                ?>
            </script>
            <?php
            $this->renderContent();
        }
    }

    /**
     * Render HEAD node.
     * @return void
     */
    private function renderHead(): void
    {
        ?>
        <!DOCUMENT <?= $this->options->document_type; ?>>
        <head>
            <title><?= $this->options->title; ?></title>
            <?php
            if ($this->options->uses_jquery) {
                ?>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                <?php
            }
            if ($this->options->uses_jqueryui) {
                ?>
                <link rel="stylesheet"
                      href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
                <?php
            }
            $this->renderHeadContent();
            ?>
            <link rel="stylesheet"
                  href="/global.css">
            <script type="text/javascript">
                $(function () {
                    <?php
                    $this->renderDocumentReady();
                    ?>
                });
                <?php
                $this->renderCustomJS();
                ?>
            </script>
        </head>
        <?php
        // TO DO:  render the HEAD option.
    }

    /**
     * Render the BODY node.
     * @return void
     */
    private function renderBody(float $starttime): void
    {
        ?>
        <body>
        <?php
        if ($this->options->display_header) {
            ?>
            <header>
                <?php
                $this->renderHeader();
                ?>
            </header>
            <?php
        }
        $this->renderContent();
        $endtime = microtime(true);
        $timediff = $endtime - $starttime;
        if (CFG::get('app', 'debug')) {
            ?>
            <section id="debugsection_<?= __CLASS__; ?>">
                <code>
                    Page Render Time: <?= $timediff; ?> seconds.
                </code>
            </section>
            <?php
        }
        if ($this->options->display_footer) {
            ?>
            <footer>
                <?php
                $this->renderFooter();
                ?>
            </footer>
            <?php
        }
        ?>
        </body>
        <?php
    }

    /**
     * Render the content.
     * @return void
     */
    private function renderContent(): void
    {
        ?>
        <section id="<?= $this->options->sectionid; ?>">
            <?php
            $this->renderSectionContent();
            ?>
        </section>
        <?php
        // Render the body content.
    }

    //==================================================================================================================
    // Any methods under here can be overloaded by child classes to render pages.
    //==================================================================================================================

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
        // Render <HEADER> block.
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        // Render the HTML content.  It will be within BODY and within its own SECTION with a unique ID.
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