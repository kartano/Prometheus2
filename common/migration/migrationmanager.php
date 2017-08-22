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
     * @throws \mysqli_sql_exception Exception thrown from previously failed migration script.
     * @throws \RuntimeException If the migration scripts folder does not exist or cannot be found.
     * @throws \Exception Usually occurs when a particular migration script cannot be executed.
     */
    public static function InstallScripts(DB\PromDB $db): void
    {
        $migration_scripts=[];

        $script_path=dirname(__FILE__).'/.migrationscripts/';
        $handle=opendir($script_path);
        if ($handle===false)
            throw new \RuntimeException("Migration script folder doesn't exist or is not accessible: $script_path");
        try
        {
            while ($filename=readdir($script_path))
            {
                if ($filename != "." && $filename != "..")
                {
                    $filename_parts=pathinfo($script_path.$filename);
                    if (strtoupper($filename_parts['extension'])=="PHP")
                    {
                        $filename=self::getMigrationScriptClassName($filename);
                        // Recall:  requiring a file here will inherit the calling method's scope.
                        require_once $script_path.$filename;
                        //$migration_script=new $filename_parts['filename']($db);
                        //$migration_scripts[$filename_parts['filename']]=$migration_script;
                        $migration_scripts[$filename_parts['filename']]=$filename_parts['filename'];
                    }
                }
            }
            closedir($handle);
        }
        catch (\Exception $exception) {
            throw $exception;
        }

        try {
            foreach($migration_scripts as $filename => $migration_script) {
                $db->begin_transaction();
                echo "<br><code>$migration_script</code>";
                $db->commit();
            }
        } catch (\mysqli_sql_exception $exception) {
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
        $filename=substr($filename,$first_us_pos);
        return ucwords($filename,'_');
    }
}
