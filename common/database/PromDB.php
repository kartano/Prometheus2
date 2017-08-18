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
 * @version         1.0.0           2017-08-17 2017-08-17 Prototype
 */

namespace Prometheus2\common\database;

use Prometheus2\common\settings\Settings AS CFG;
use Prometheus2\common\exceptions\DatabaseException AS DBException;
/**
 * Class PromDB
 * @package Prometheus2\common\database
 */
class PromDB extends \mysqli
{
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
     * @throws DatabaseException Thrown if the DB does not connect.
     */
    public function __construct(string $host, string $username, string $passwd, string $dbname, int $port, int $socket)
    {
        parent::__construct($host, $username, $passwd, $dbname, $port, $socket);
        if ($this->connect_error) {
            throw new DBException($this->connect_error, $this->connect_errno);
        }
    }

    /**
     * Destruct.  Close connection if open.
     */
    public function __destruct()
    {
        if ($this->ping()) {
            @$this->close();
        }
    }

    /**
     * Basic factory method to create a new instance of a DB.
     *
     * @return \Prometheus2\common\database\PromDB
     */
    public static function Create(): PromDB
    {
        $settings=CFG::get('db');
        return new PromDB($settings['host'],$settings['user'],$settings['pass'],$settings['catalogue'],$settings['port'],$settings['socket']);
    }
}
