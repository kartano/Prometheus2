<?php
/**
 * User helper routines.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\user
 *
 * @version         1.0.0           2017-08-22 2017-08-22 Prototype
 */

namespace Prometheus2\common\user;

/**
 * Class UserHelper
 * @package Prometheus2\common\user
 */
class UserHelper
{
    /**
     * Generate a random salt value.
     *
     * @return string A random GUID to use as salt for the user password.
     */
    public static function getRandomSalt(): string
    {
        // SM:  Windows wants to return a GUID with curly braces.  Other systems don't.
        return trim(com_create_guid(), '{}');
    }

    /**
     * Encrypts an unsafe password prior to being saved in the DB.
     *
     * @param string $password The raw password.
     * @param string $salt The random salt value.
     *
     * @return string The hashed password, including the salt.
     * @throws \RuntimeException Thrown if the requested hashing algorithm is not available.
     */
    public static function encryptPassword(string $password, string $salt): string
    {
        if (!in_array('sha512',hash_algos())) {
            throw new \RuntimeException("This installation requires that PHP have the SHA512 hash algorithm.");
        }
        return hash('sha512',$password.$salt);
    }
}
