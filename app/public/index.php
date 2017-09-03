<?php
/**
 * Index file
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace
 *
 * @version         1.0.1           2017-08-27 20:53:00 SM Fixed bug where index.html and index.php were treated as pages to ignore.
 */

require_once dirname(__FILE__) . '/../bootstrap.php';

use Prometheus2\common\modules\admin as Admin;
use Prometheus2\common\database as DB;
use Prometheus2\common\pagerendering as PR;
use Prometheus2\app\content as Content;
use Prometheus2\common\exceptions AS Exceptions;

// TO DO:  Create an instance of the app.
//         Determine what page is requested.
//          Handle accordingly.

// Remember to adjust the DocumentRoot and modrewrite settings to get this to work.
// Also remember the local .htaccess file.

// SEE:  https://www.binpress.com/tutorial/php-bootstrapping-crash-course/146

// SAMPLE OF CONF FILE APACHE:
/*
 * DocumentRoot /path/to/myapp/app/public
<Directory "/path/to/myapp/app/public">
  # other setting here
</Directory>
 */

// Then the local .htaccess looks likes this:
/*
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
 */

$bits = parse_url($_SERVER['REQUEST_URI']);
$query = isset($bits['query']) ? $bits['query'] : '';
$path = $bits['path'];

// SM:  Use $pageHome at this point to switch to what part of the site is being requested.
//      This is be either "home" if no URL was specified; or
//

$database = DB\PromDB::create();
switch(strtolower($path)) {
    case '/':
    case 'index.html':
    case 'index.php':
    case '':
        $options=new PR\PageOptions();
        $page = new Content\HomePage($database,$options);
        $page->renderPage();
        break;
    case '/admin':
        $options = new PR\PageOptions();
        $options->requires_logged_in=true;
        try {
            $page = new Admin\Prom2Admin($database, $options);
            $page->renderPage();
        } catch (Exceptions\NotLoggedInException $exception) {
            // Squash:  The abstract pagerenderer engine will automatically throw up the LOGIN screen if the user is not logged in.
            //          If they ARE, it will render the Prom2Admin page.
        }
        break;
    default:
        PR\PageHelper::throwHTTPError(404,'Page not found');
}
$database->close();
