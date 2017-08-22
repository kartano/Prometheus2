<?php
/**
 * Helper functions for use with page rendering.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\pagerendering
 *
 * @version         1.0.0           2017-08-22 2017-08-22 Prototype
 */

namespace Prometheus2\common\pagerendering;
use Prometheus2\common\browserutils as BU;

/**
 * Class PageHelper
 * @package Prometheus2\common\pagerendering
 */
class PageHelper
{
    /**
     * Throws a HTTP error to the browser through the header.
     *
     * @param int    $http_error_code The HTTPD code to be returned.  Usually a 500.
     * @param string $message  The additional message to send.
     */
    public static function throwHTTPError(int $http_error_code, string $message): void
    {
        header("HTTP/1.1 $http_error_code ".BU\BrowserUtils::getHTTPErrorMessage($http_error_code));
        echo $message;
    }
}
