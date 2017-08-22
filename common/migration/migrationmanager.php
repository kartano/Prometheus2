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
                        echo "<p>Create $class_name</p>";
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
            foreach($migration_scripts as $class_name => $migration_script) {
                $db->begin_transaction();
                if ($up) {
                    $migration_script->safeUp();
                } else {
                    $migration_script->safeDown();
                }
                $db->commit();
                if ($up) {
                    $migration_script->up();
                } else {
                    $migration_script->down();
                }
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
