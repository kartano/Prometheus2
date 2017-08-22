<?php
/**
 * Migration script manager.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\migration
 *
 * @version         1.0.0           2017-08-22 2017-08-22 Prototype
 */

namespace Prometheus2\common\migration;
use Prometheus2\common\database as DB;
use Prometheus2\common\exceptions AS EX;

/**
 * Class MigrationManager
 * @package Prometheus2\common\migration
 */
class MigrationManager
{
    /**
     * Executes all migrations scripts that have NOT already been recorded.
     * Only exception is InitProm2 which creates the migration table if it doesn't exist.
     * @param \Prometheus2\common\database\PromDB $db
     * @param boolean $up If TRUE then the safeUp and up methods are called.  Otherwise, DOWN.
     * @throws \mysqli_sql_exception Exception thrown from previously failed migration script.
     * @throws \RuntimeException If the migration scripts folder does not exist or cannot be found.
     * @throws \Exception Usually occurs when a particular migration script cannot be executed.
     * @throws \Prometheus2\common\exceptions\DatabaseException if an attempt to determine if a table exists has failed.
     */
    public static function InstallScripts(DB\PromDB $db, bool $up=true): void
    {
        static $already_checked=false;

        if ($already_checked) {
            return;
        }
        $already_checked=true;
        $migration_scripts=[];

        $script_path=dirname(__FILE__).'\\migrationscripts\\';
        $handle=opendir($script_path);
        if ($handle===false)
            throw new \RuntimeException("Migration script folder doesn't exist or is not accessible: $script_path");
        try
        {
            while ($filename=readdir($handle))
            {
                if ($filename != "." && $filename != "..")
                {
                    $filename_parts=pathinfo($script_path.$filename);
                    if (strtoupper($filename_parts['extension'])=="PHP")
                    {
                        $class_name=self::getMigrationScriptClassName($filename_parts['filename']);
                        require_once $script_path.$filename;
                        $class_name="Prometheus2\\common\\migration\\migrationscripts\\$class_name";
                        $migration_script=new $class_name($db);
                        $migration_scripts[$filename]=$migration_script;
                    }
                }
            }
            closedir($handle);
        }
        catch (\Exception $exception) {
            throw $exception;
        }

        try {
            $migration_insert_query="INSERT INTO prom2_migrations(datExecute, txtFilename) VALUES (NOW(), ?)";
            $migration_check_query="SELECT * from prom2_migrations WHERE txtFilename=?";
            foreach($migration_scripts as $filename => $migration_script) {
                $statement=$db->prepare($migration_check_query);
                $statement->bind_param('s', $filename);
                $statement->execute();
                $statement->store_result();
                $rowcount=$statement->num_rows;
                $statement->close();
                if ($rowcount==1) {
                    continue;
                }
                $db->autocommit(false);
                $db->begin_transaction();
                if ($up) {
                    $migration_script->safeUp();
                } else {
                    $migration_script->safeDown();
                }
                $db->commit();
                $db->autocommit(true);
                if ($up) {
                    $migration_script->up();
                } else {
                    $migration_script->down();
                }
                $statement=$db->prepare($migration_insert_query);
                $statement->bind_param('s', $filename);
                $statement->execute();
                $statement->close();
            }
        } catch (\mysqli_sql_exception $exception) {
            throw $exception;
        } catch (EX\DatabaseException $exception) {
            throw $exception;
        }
    }

    /**
     * Remove date portion from migration script filename, and determine the equivalent class name.
     *
     * @param string $filename The filename from which to ascertain the migreation script class name.
     *
     * @return string The class name to expect.
     */
    protected static function getMigrationScriptClassName(string $filename): string
    {
        $first_us_pos=strpos($filename,'_');
        $filename=substr($filename,$first_us_pos+1);
        return ucwords($filename,'_');
    }
}
