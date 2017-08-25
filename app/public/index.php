<?php
/**
 * Index file
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace
 *
 * @version         1.0.0           2017-08-16 2017-08-16 Prototype
 */

require_once dirname(__FILE__) . '/../bootstrap.php';

use Prometheus2\common\modules\admin as Admin;
use Prometheus2\common\database as DB;
use Prometheus2\common\pagerendering as PR;
use Prometheus2\app\content as Content;

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

$database = DB\PromDB::Create();
switch(strtolower($path)) {
    case '/':
        $options=new PR\PageOptions();
        $page = new Content\HomePage($database,$options);
        $page->renderPage();
        break;
    case '/admin':
        $options = new PR\PageOptions();
        $page = new Admin\Prom2Admin($database, $options);
        $page->renderPage();
        break;
    default:
        PR\PageHelper::throwHTTPError(404,'Page not found');
}
$database->close();
