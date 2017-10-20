<?php
/**
 * Alter pages table schema
 *
 * @author Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\migration\migrationscripts
 *
 * @version         1.0.0        2017-10-20 15:16 Prototype
 */

namespace Prometheus2\common\migration\migrationscripts;
use Prometheus2\common\migration\MigrationBaseClass;
use Prometheus2\common\database AS DB;

class Alter_Prom2_Pages extends MigrationBaseClass
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
            $this->db->ForeignKeyChecks(false);
            $statement=$this->db->prepare("ALTER TABLE `prom2_pages`
ADD COLUMN `txtHeadAppend`  text NULL AFTER `blnRobots_DoNotAllow`,
ADD COLUMN `txtBodyPrepend`  text NULL AFTER `txtHeadAppend`,
ADD COLUMN `txtCustomJS`  text NULL AFTER `txtBodyPrepend`,
ADD COLUMN `txtCustomDocumentReady_Start`  text NULL AFTER `txtCustomJS`,
ADD COLUMN `txtCustomDocumentRead_End`  text NULL AFTER `txtCustomDocumentReady_Start`;");
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
        //
    }
}