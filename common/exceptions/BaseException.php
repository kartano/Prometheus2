<?php
/**
 * Base Prom Exception class.  All PROM custom exceptions will extend this.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\exceptions
 *
 * @version         1.0.1           2017-08-31 12:07:00 SM:  Now throws a 500 if the debug setting is not set.
 */

namespace Prometheus2\common\exceptions;

use Prometheus2\common\settings\Settings AS CFG;
use Prometheus2\common\pagerendering\pagehelper AS PageHelper;

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
     * Displays a nicely formatted exception on the screen.
     * This will only show a HTTP 500 if DEBUG is turned off.
     */
    public function display()
    {
        $logid=$this->saveToLog();
        if (CFG::get('app','debug')) {
            ?>
            </script>
            <style>
                .exception_div {
                    border: 2px solid red;
                    border-radius: 25px;
                    width: 90%;
                    background-color: white;
                }

                .exception_section {
                    font-family: Arial, Helvetica, sans-serif;
                }
            </style>
            <div class="exception_div">
                <section class="exception_section">
                    <h1>Exception thrown: <code><?=get_class($this);?></code></h1>
                    <p>Log ID:  <?=$logid; ?></p>
                    <p>Error(<?=$this->getCode(); ?>) - <?=$this->getMessage();?></p>
                </section>
            </div>
            <?php
        } else {
            PageHelper::throwHTTPError('500','Invalid debug value in settings.',true);
        }
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
