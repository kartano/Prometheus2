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

$page = $_SERVER['REQUEST_URI'];
$halfValue = explode('.php/', $page);

if (!empty($halfValue[1])) {
    $pageName = $halfValue[1];
} else {
    $pageName="home";
}

// SM:  Use $pageHome at this point to switch to what part of the site is being requested.
//      This is be either "home" if no URL was specified; or
//

echo "<pre>";
print_r($page);
print_r($halfValue);
print_r($pageName);
echo "</pre>";
