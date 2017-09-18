<?php
/**
 * Creates the translation table.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\migration\migrationscripts
 *
 * @version         1.0.0           2017-09-18 2017-09-18 Prototype
 */

namespace Prometheus2\common\migration\migrationscripts;
use Prometheus2\common\migration\MigrationBaseClass;
use Prometheus2\common\database AS DB;

class create_prom2_translations extends MigrationBaseClass
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
            if ($this->db->tableExists('prom2_translations')) {
                return;
            }
            $this->db->ForeignKeyChecks(false);
            $statement = $this->db->prepare("DROP TABLE IF EXISTS prom2_translations");
            $statement->execute();
            $statement->close();
            $statement = $this->db->prepare("CREATE TABLE `prom2_translations` (
  `cntTranslationID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txtSourceString` varchar(255) NOT NULL,
  `txtReplacementString` varchar(255) NOT NULL,
  PRIMARY KEY (`cntTranslationID`),
  UNIQUE KEY `source_unique_key` (`txtSourceString`,`txtReplacementString`),
  KEY `source_lookup_key` (`txtSourceString`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
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
            $statement = $this->db->prepare("DROP TABLE IF EXISTS prom2_translations");
            $statement->execute();
            $statement->close();
            $this->db->ForeignKeyChecks(true);
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        }
    }
}