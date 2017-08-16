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

$page = $_SERVER['REQUEST_URI'];
$halfValue = explode('.php/', $page);
if (!empty($halfValue[1])) {
    $getPageName = $halfValue[1];
}
$pageName = (empty($getPageName)) ? "Home" : $getPageName;

echo $pageName;
