<?php
/**
 * Migration script for the prom2 log.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\migration\migrationscripts
 *
 * @version         1.0.0           2017-08-22 2017-08-22 Prototype
 */


namespace Prometheus2\common\migration\migrationscripts;

use Prometheus2\common\migration\MigrationBaseClass;
use Prometheus2\common\database AS DB;

class Create_Prom2_Log extends MigrationBaseClass
{
    /**
     * InitProm2 constructor.
     *
     * @param \Prometheus2\common\database\PromDB|null $db
     */
    public function __construct(DB\PromDB $db = null)
    {
        parent::__construct($db);
    }

    /**
     * Safely updates or creates tables, within a transaction.
     *
     * @return void
     * @throws \mysqli_sql_exception Exception thrown on either foreign key setting, or the query to create tables.
     */
    public function safeUp(): void
    {
        try {
            $this->db->ForeignKeyChecks(false);
            $this->db->query = ("DROP TABLE IF EXISTS prom2_log");
            $this->db->query("CREATE TABLE `prom2_log` (
  `cntLogID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datTimestamp` datetime NOT NULL,
  `txtCallStack` varchar(512) DEFAULT NULL,
  `txtMessage` varchar(255) DEFAULT NULL,
  `lngCode` int(11) DEFAULT NULL,
  `lngLoggedInUserID` int(10) unsigned DEFAULT NULL COMMENT 'User who was logged in when error occurred',
  PRIMARY KEY (`cntLogID`),
  KEY `lngLoggedInUserID` (`lngLoggedInUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            $this->db->ForeignKeyChecks(true);
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        }
    }

    /**
     * Safely drops any related tables or data, within a transaction.
     *
     * @return void
     * @throws \mysqli_sql_exception Exception thrown on either foreign key setting, or the query to create tables.
     */
    public function safeDown(): void
    {
        try {
            $this->db->ForeignKeyChecks(false);
            $this->db->query = ("DROP TABLE IF EXISTS prom2_log");
            $this->db->ForeignKeyChecks(true);
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        }
    }
}