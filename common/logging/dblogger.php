<?php
/**
 * Logging class specifically for logging to the Prom2 database.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       prometheus2\common\logging
 *
 * @version         1.0.0           2017-08-17 2017-08-17 Prototype
 */

namespace Prometheus2\common\logging;

use Prometheus2\common\database\PromDB AS DB;
use Prometheus2\common\exceptions\DatabaseException AS DBException;
use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class dblogger
 * @package Prometheus2\common\logging
 */
class dblogger extends logger
{
    /**
     * Get a static instance of the DB for this logger.
     *
     * @return \Prometheus2\common\database\PromDB An instance of a database.
     * @throws \Prometheus2\common\exceptions\DatabaseException Exception thrown if the DB does not connect.
     */
    protected static function getDB() : DB
    {
        static $db=null;
        try {
            if ($db===null) {
                $db=DB::Create();
            }
        }
        catch (DBException $exception) {
            throw $exception;
        }
        return $db;
    }

    /**
     * @param string $message The message string to append to the log.
     *
     * @return int The ID of the entry, where applicable.
     * @throws \BadFunctionCallException Thrown if this method was not extended in the child class.
     */
    public static function appendToLog(string $message): int
    {
        // SM:  To get the log table - CFG::get('log','table')
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
        // TO DO:  Write to database
    }
}