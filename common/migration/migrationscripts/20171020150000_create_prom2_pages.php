<?php
/**
 * DESCRIPTION
 *
 * @author   karta  <EMAIL>
 *
 * @namespace   Prometheus2\common\migration\migrationscripts
 *
 * @version         1.0.0        2017-10-20 14:54
 */

namespace Prometheus2\common\migration\migrationscripts;
use Prometheus2\common\migration\MigrationBaseClass;
use Prometheus2\common\database AS DB;

class Create_Prom2_Pages extends MigrationBaseClass
{
    /**
     * Create Prom2 User constructor.
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
            if ($this->db->tableExists('prom2_pages')) {
                return;
            }
            $this->db->ForeignKeyChecks(false);
            $statement=$this->db->prepare("CREATE TABLE `prom2_pages` (
  `cntPageID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txtRelativeURL` varchar(255) NOT NULL COMMENT 'Relative URL',
  `txtTitle` varchar(70) NOT NULL COMMENT 'Title',
  `txtContent` text NOT NULL COMMENT 'Content',
  `blnRobots_DoNotAllow` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`cntPageID`),
  UNIQUE KEY `txtRelativeURL` (`txtRelativeURL`),
  KEY `txtTitle` (`txtTitle`)
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
            $statement=$this->db->prepare("DROP TABLE IF EXISTS prom2_pages");
            $statement->execute();
            $statement->close();
            $this->db->ForeignKeyChecks(true);
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        }
    }
}
