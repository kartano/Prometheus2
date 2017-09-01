<?php
/**
 * Logging class specifically for logging to the Prom2 database.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       prometheus2\common\logging
 *
 * @version         1.1.0           2017-08-30 12:54:00 Fleshed out code to write to logs.
 */

namespace Prometheus2\common\logging;

use Prometheus2\common\database\PromDB AS DB;
use Prometheus2\common\exceptions\DatabaseException AS DBException;

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
                $db=DB::create();
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
        try {
            $query="INSERT INTO prom2_log (datTimestamp,txtCallStack,txtMessage,lngCode,lngLoggedInUserID) VALUES(NOW(), null, ?, 0, 0)";
            $statement=self::getDB()->prepare($query);
            $statement->bind_param('s', $message);
            $statement->execute();
        } catch (\mysqli_sql_exception $exception) {
            die($exception);
        }
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
        try {
            $callstack=print_r(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT ,10),true);
            $message=$exception->getMessage();
            $code=$exception->getCode();
            $usercode=0;
            $query="INSERT INTO prom2_log (datTimestamp,txtCallStack,txtMessage,lngCode,lngLoggedInUserID) VALUES(NOW(), ?, ?, ?, ?)";
            $statement=self::getDB()->prepare($query);
            $statement->bind_param('ssii', $callstack, $message, $code, $usercode);
            $statement->execute();
            $retval=$statement->insert_id;
            $statement->close();
            return $retval;
        } catch (\mysqli_sql_exception $exception) {
            die($exception);
        }
    }
}
