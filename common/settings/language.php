<?php
/**
 * Language utility.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\settings
 *
 * @version         1.0.0           2017-09-18 2017-09-18 Prototype
 */

namespace Prometheus2\common\settings;

use Prometheus2\common\database as DB;

/**
 * Class Language
 * @package Prometheus2\common\settings
 */
class Language
{
    /**
     * @param string $value
     *
     * @return string
     */
    public static function translate(string $value): string
    {
        static $db = null;
        static $translations = [];

        // SM:  Buffer any translations.  Avoid hitting the DB to get the same translation over and over.
        if (array_key_exists($value, $translations)) {
            return $translations[$value];
        } else {
            if ($db === null) {
                $db = DB\PromDB::Create();
            }
            $query = "SELECT txtReplacementString
        FROM prom2_translations
        WHERE txtSourceString=?";
            $statement = $db->prepare($query);
            $statement->bind_param('s', $value);
            $statement->execute();
            $returnvalue = $value;
            if ($statement->num_rows == 1) {
                $statement->bind_result($returnvalue);
                $statement->fetch();
            }
            $statement->close();
            $translations[$value] = $returnvalue;
            return $returnvalue;
        }
    }
}