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
 * @version         1.2.0           2017-09-25 11:41:00 SM:  Added getFieldComment, getFieldEnums and getFieldLength.
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
    // SM:  These bit patterns match that of the META flags within a FIELD object (mysqli_result->fetch_fields() ....)
    public const NOT_NULL_FLAG = 1;
    public const PRI_KEY_FLAG = 2;
    public const UNIQUE_KEY_FLAG = 4;
    public const BLOB_FLAG = 16;
    public const UNSIGNED_FLAG = 32;
    public const ZEROFILL_FLAG = 64;
    public const BINARY_FLAG = 128;
    public const ENUM_FLAG = 256;
    public const AUTO_INCREMENT_FLAG = 512;
    public const TIMESTAMP_FLAG = 1024;
    public const SET_FLAG = 2048;
    public const NUM_FLAG = 32768;
    public const PART_KEY_FLAG = 16384;
    public const GROUP_FLAG = 32768;
    public const UNIQUE_FLAG = 65536;

    /**
     * @var bool $isGod TRUE if this connection was made with ROOT priviledges.  If this is ever TRUE, there be a fucking good reason for it!!!
     */
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

    /**
     * Obtain the comments value for a given field from the prom2 db.
     *
     * @param string $table Table to look for.
     * @param string $column Column within table to look for.
     *
     * @return string The comments attached to that field.
     * @throws \InvalidArgumentException Thrown if the specified table and column do not exist in the prom2 database.
     */
    public static function getFieldComment(string $table, string $column): string
    {
        static $comments=[];

        // SM:  Cache these.
        //      If we don't hit the cache, we grab comments for ALL fields in the specified table.
        $key=$table.$column;
        if (array_key_exists($key,$comments)) {
            return $comments[$key];
        }
        $catalogue=CFG::get('god','catalogue');
        $query="SELECT information_schema.`COLUMNS`.COLUMN_COMMENT,
            information_schema.`COLUMNS`.COLUMN_NAME
            FROM 
            information_schema.COLUMNS
            WHERE TABLE_NAME = ?
            AND TABLE_SCHEMA = ?";
        $db=self::createGod();
        $stmt=$db->prepare($query);
        $stmt->bind_param('ss',$table, $catalogue);
        $stmt->execute();
        $stmt->bind_result($comment, $colname);
        $retval=null;
        while($stmt->fetch()) {
            $key=$table.$colname;
            $comments[$key]=$comment;
            if ($colname===$column) {
                $retval=$comment;
            }
        }
        $stmt->close();
        $db->close();
        if ($retval===null) {
            throw new \InvalidArgumentException("Comments for field {$table}.{$column} could not be found.");
        }
        return $retval;
    }

    /**
     * Grab the enum values for a specific table and field.
     *
     * @param string $table The table to look at
     * @param string $field The field to obtain enum values for.
     * @param bool   $sort  If TRUE then the enum values are sorted.  FALSE by default.
     *
     * @return array An array of enumerated values.
     */
    public static function getFieldEnums(string $table, string $field, bool $sort=false): array
    {
        static $enums=[];
        $key=$table.$field;

        if (array_key_exists($key,$enums)) {
            return $enums[$key];
        }

        $db=self::createGod();
        $query = "SHOW COLUMNS 
            FROM  $table
            WHERE Field = ?";
        $stmt=$db->prepare($query);
        $stmt->bind_param('s',$field);
        $stmt->execute();
        $stmt->bind_result($comment, $colname);
        $retval=[];
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $db->close();
        preg_match('/^enum\((.*)\)$/', $row['Type'], $matches);
        foreach (explode(',', $matches[1]) as $value) {
            $retval[] = str_replace("'", '', $value);
        }
        if ($sort) {
            sort($retval);
        }
        $enums[$key]=$retval;
        return $retval;
    }

    /**
     * Obtain the maximum field length for a given field and table.
     *
     * @param string $table Table to look through.
     * @param string $field Field to look for.
     *
     * @return int The max length for this field.  NULL if the field doesn't support this, or the field length is N/A
     */
    public static function getFieldLength(string $table, string $field): int
    {
        static $lengths=[];

        // SM:  Cache these.
        //      If we don't hit the cache, we grab comments for ALL fields in the specified table.
        $key=$table.$field;
        if (array_key_exists($key,$lengths)) {
            return $lengths[$key];
        }
        $catalogue=CFG::get('god','catalogue');
        $query="SELECT information_schema.`COLUMNS`.CHARACTER_MAXIMUM_LENGTH,
            information_schema.`COLUMNS`.COLUMN_NAME
            FROM 
            information_schema.COLUMNS
            WHERE TABLE_NAME = ?
            AND TABLE_SCHEMA = ?";
        $db=self::createGod();
        $stmt=$db->prepare($query);
        $stmt->bind_param('ss',$table, $catalogue);
        $stmt->execute();
        $stmt->bind_result($length,$column);
        $retval=null;
        while($stmt->fetch()) {
            $key=$table.$column;
            $lengths[$key]=$length;
            if ($column===$field) {
                $retval=$length;
            }
        }
        $stmt->close();
        $db->close();
        if ($retval===null) {
            throw new \InvalidArgumentException("Field Length for field {$table}.{$field} could not be found.");
        }
        return $retval;
    }
}
