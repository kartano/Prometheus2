<?php
/**
 * User Login form renderer
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\modules\admin
 *
 * @version         1.0.0           2017-09-01 2017-09-01 Prototype
 */

namespace Prometheus2\common\pagerendering;

use Prometheus2\common\pagerendering as Page;
use Prometheus2\common\database as DB;

class LoginForm extends PageRenderer
{
    /**
     * Prom2AdminHeader constructor.
     *
     * @param DB\PromDB   $database The database
     * @param PageOptions $options  Page options
     */
    public function __construct(DB\PromDB $database)
    {
        $options = new Page\PageOptions();
        $options->render_body_only = true;
        parent::__construct($database, $options);
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
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
        <form method="POST" action="/admin">
            <div>Login Screen</div>
            <div><input type="text" name="userUsername" placeholder="enter username"></div>
            <div><input type="password" name="userPassword" placeholder="enter password"></div>
            <div><input type="submit" name="submit" value="submit"></div>
        <?php
    }

}