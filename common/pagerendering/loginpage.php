<?php
/**
 * Page that displays the login form.
 *
 * @author   Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\pagerendering
 *
 * @version         1.0.0        2017-09-02 21:20
 */

namespace Prometheus2\common\pagerendering;
use Prometheus2\common\database as DB;
use Prometheus2\common\modules\admin AS Admin;
use Prometheus2\common\exceptions AS Exceptions;

/**
 * Class LoginPage
 * @package Prometheus2\common\pagerendering
 */
class LoginPage extends PageRenderer
{
    protected $adminheader;
    protected $failedLogin;

    /**
     * LoginPage constructor.
     * @param DB\PromDB $database
     * @param PageOptions $options
     * @param bool $failedLogin True if a login attempt was made, but failed.
     */
    public function __construct(DB\PromDB $database, PageOptions $options, bool $failedlogin)
    {
        $this->adminheader=new Admin\Prom2AdminHeader($database);
        $this->failedLogin=$failedlogin;
        try {
            parent::__construct($database, $options);
        } catch(Exceptions\NotLoggedInException $exception) {
            // Squash.  This is a login page. We already KNOW they're not logged in.
        }
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
     * Render additional content within the HEAD of the document.
     * @return void
     */
    protected function renderHeadContent(): void
    {
        ?>
        <style>
            body { background-color: #235e98; }
            .login-box { text-align: center; width: auto; padding: 10px; }
            .login-line { float: left; width: 100%; text-align: center;  }
            .login-line input[type="text"], .login-line input[type="password"]
            .login_text_boxes{ padding: 7px; width: 300px; margin: 5px; }

            .login-line input[type="submit"] { background-color: orange; padding: 7px; color: #fff; width: 300px; border-radius: 5px; border: none; margin: 5px;}
            .failed_login {
                font-family: Arial, Helvetica, sans-serif;
                font-size: large;
                color: white;
            }
        </style>
        <?php
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        if ($this->failedLogin) {
            ?>
            <span class="failed_login">Invalid username or password combination</span>
            <?php
        }
        ?>
        <div id="login_form_div" class="center_screen">
            <form method="POST" action="/admin">
                <div>Login Screen</div>
                <div><input class="login_text_boxes" type="text" name="username" placeholder="Enter username"></div>
                <div><input class="login_text_boxes" type="password" name="password" placeholder="Enter password"></div>
                <input class="ui-button ui-widget ui-corner-all" type="submit" value="A submit button">
                </form>
        </div>
        <?php
    }
}
