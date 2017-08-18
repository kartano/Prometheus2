<?php
/**
 * Logging class
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       prometheus2\common\logging;
 *
 * @version         1.0.0           2017-08-17 2017-08-17 Prototype
 */

namespace prometheus2\common\logging;

/**
 * Class logger
 * @package prometheus2\common\logging
 */
abstract class logger
{
    /**
     * @param string $message The message string to append to the log.
     *
     * @return int The ID of the entry, where applicable.
     * @throws \BadFunctionCallException Thrown if this method was not extended in the child class.
     */
    public static function appendToLog(string $message): int
    {
        throw new \BadFunctionCallException(__METHOD__.' not implemented.');
    }

    /**
     * Append details about a thrown exception to the log.
     *
     * @param \Exception $exception The exception to be dumped to the log.
     *
     * @return int The ID of the entry, where applicable.
     */
    public static function appendExceptionToLog(\Exception $exception): int
    {
        throw new \BadFunctionCallException(__METHOD__.' not implemented.');
    }
}
