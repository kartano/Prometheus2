<?php
/**
 * This should be the FIRST migration script ever executed.
 * It gets the very basic Prom2 tables installed.
 *
 * @author          kartano@gmail.com
 *
 * @namespace       Prometheus2\common\migration\migrationscripts
 *
 * @version         1.0.0           2017-08-22 2017-08-22 Prototype
 */

namespace Prometheus2\common\migration\migrationscripts;

use Prometheus2\common\migration\MigrationBaseClass;
use Prometheus2\common\database as DB;

class Init_Prom2 extends MigrationBaseClass
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
    public function up(): void
    {
        try {
            // SM:  We NEVER delete this table.
            if ($this->db->tableExists('prom2_migrations')) {
                return;
            }
            $this->db->ForeignKeyChecks(false);
            $statement=$this->db->prepare("CREATE TABLE `prom2_migrations` (
  `cntID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datExecute` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `txtFilename` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name of migration script run.',
  PRIMARY KEY (`cntID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            $statement->execute();
            $statement->close();
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
    public function down(): void
    {
        try {
            $this->db->ForeignKeyChecks(false);
            $statement=$this->db->prepare("DROP TABLE IF EXISTS prom2_migrations");
            $statement->execute();
            $statement->close();
            $this->db->ForeignKeyChecks(true);
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        }
    }
}
