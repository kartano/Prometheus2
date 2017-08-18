<?php
/**
 * Base Prom Exception class.  All PROM custom exceptions will extend this.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\exceptions
 *
 * @version         1.0.0           2017-08-17 2017-08-17 Prototype
 */

namespace Prometheus2\common\exceptions;

use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class BaseException
 * @package Prometheus2\common\exceptions
 */
abstract class BaseException extends \Exception
{
    /**
     * BaseException constructor.
     *
     * @param string          $message      The exception message.
     * @param int             $code         The code for this exception.
     * @param \Throwable|null $previous A previously thrown exception (I.E: we have a cascading set of exceptions)
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Save this exception to the log.
     *
     * @return int The related db entry, where applicable.
     */
    public function saveToLog(): int
    {
        return CFG::getLogger()->appendExceptionToLog($this);
    }
}