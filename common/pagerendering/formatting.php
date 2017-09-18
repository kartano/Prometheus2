<?php
/**
 * General formatting utilities for values.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\pagerendering
 *
 * @version         1.0.0           2017-09-18 2017-09-18 Prototype
 */


namespace Prometheus2\common\pagerendering;
use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class Formatting
 * @package Prometheus2\common\pagerendering
 */
final class Formatting
{
    /**
     * Accepts a string and converts it into whatever the endian date format is.
     *
     * @param $value
     *
     * @return string
     */
    public static function formatDate($value): string
    {
        return date(CFG::get('format','endiandate'),strtotime($value));
    }
}