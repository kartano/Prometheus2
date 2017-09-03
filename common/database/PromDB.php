<?php
/**
 * Prometheus 2 database.
 * This is an extension of MySQLI, but still allows for us to append logging and tracking
 * functionality later.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\database
 *
 * @version         1.1.2           2017-08-22 15:00:00 Added createGod
 */

namespace Prometheus2\common\database;

use Prometheus2\common\exceptions\DatabaseException;
use Prometheus2\common\settings\Settings AS CFG;
use Prometheus2\common\exceptions\DatabaseException AS DBException;

/**
 * Class PromDB
 * @package Prometheus2\common\database
 */
class PromDB extends \mysqli
{
    protected $isGod;
    /**
     * PromDB constructor.
     *
     * @param string $host
     * @param string $username
     * @param string $passwd
     * @param string $dbname
     * @param int    $port
     * @param int    $socket
     *
     * @throws DBException Thrown if the DB does not connect.
     */
    public function __construct(string $host, string $username, string $passwd, string $dbname, int $port, int $socket)
    {
        parent::__construct($host, $username, $passwd, $dbname, $port, $socket);

        // SM:  Force us to throw exceptions for all errors.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        if ($this->connect_error) {
            throw new DBException($this->connect_error, $this->connect_errno);
        }
        $this->isGod=false;
    }

    /**
     * Destruct.  Close connection if open.
     */
    public function __destruct()
    {
        @mysqli_report(MYSQLI_REPORT_OFF);
        @$this->close();
    }

    /**
     * Basic factory method to create a new instance of a DB using the standard DB access.
     *
     * @return \Prometheus2\common\database\PromDB
     */
    public static function Create(): PromDB
    {
        $settings = CFG::get('db');
        return new PromDB($settings['host'], $settings['user'], $settings['pass'], $settings['catalogue'],
            $settings['port'], $settings['socket']);
    }

    /**
     * Factory method to create a new instance of a DB with ROOT priviledges.
     * THIS CONNECTION SHOULD NOT BE USED FOR ANYTHING OTHER THAN MIGRATION SCRIPTS!
     */
    public static function createGod(): PromDB
    {
        $settings = CFG::get('god');
        $database=new PromDB($settings['host'], $settings['user'], $settings['pass'], $settings['catalogue'],
            $settings['port'], $settings['socket']);
        $database->isGod=true;
        return $database;
    }

    /**
     * Get the "God" flag.
     *
     * @return bool TRUE if this is a root connection.
     */
    public function getIsGod(): bool
    {
        return $this->isGod;
    }

    /**
     * Set flag for foreign key checks.
     *
     * @param bool $flag True to turn on foreign key checks, false to turn them off.
     *
     * @throws \mysqli_sql_exception If the query fails.
     */
    public function ForeignKeyChecks(bool $flag)
    {
        try {
            $value = $flag ? '1' : '0';
            $this->query('SET FOREIGN_KEY_CHECKS=' . $value);
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        }
    }

    /**
     * Check to see if a table exists in this particular database.
     *
     * @param string $tablename The table to check.
     *
     * @return bool TRUE if table exists, FALSE if not.
     * @throws \Prometheus2\common\exceptions\DatabaseException On errors checking for table.
     */
    public function tableExists(string $tablename): bool
    {
        try {
            // SM:  PHP/MySQLI do not like you using params for table names.
            $query = "SHOW TABLES LIKE '" . $this->real_escape_string($tablename) . "'";
            $statement = $this->prepare($query);
            $statement->execute();
            $statement->store_result();
            $retval = $statement->num_rows == 1;
            $statement->close();
            return $retval;
        } catch (\mysqli_sql_exception $exception) {
            throw new DatabaseException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
