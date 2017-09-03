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

    /**
     * LoginPage constructor.
     * @param DB\PromDB $database
     * @param PageOptions $options
     */
    public function __construct(DB\PromDB $database, PageOptions $options)
    {
        $this->adminheader=new Admin\Prom2AdminHeader($database);
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
            .login-line input[type="text"], .login-line input[type="password"] { padding: 7px; width: 300px; margin: 5px; }
            .login-line input[type="submit"] { background-color: orange; padding: 7px; color: #fff; width: 300px; border-radius: 5px; border: none; margin: 5px;}
            @media only screen and (max-width: 1024px) {
                .login-box img { width: 100%; }
                .login-line input[type="text"], .login-line input[type="password"]  { width: 800px; height: 100px; padding: 20px; font-size: 36pt; }
                .login-line input[type="submit"] { background-color: orange; padding: 20px; color: #fff; width: 800px; border-radius: 5px; border: none; margin: 5px;  font-size: 36pt;}
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
        ?>
        <form method="POST" action="/admin">
            <div>Login Screen</div>
            <div><input type="text" name="userUsername" placeholder="enter username"></div>
            <div><input type="password" name="userPassword" placeholder="enter password"></div>
            <div><input type="submit" name="submit" value="submit"></div>
        <?php
    }
}
