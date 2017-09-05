<?php
/**
 * Authentication management.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\user
 *
 * @version         1.0.0        2017-08-31 09:51
 */

namespace Prometheus2\common\user;

use Prometheus2\common\database AS DB;
use Prometheus2\common\exceptions AS Exceptions;

/**
 * Class AuthenticationManager
 * @package Prometheus2\common\user
 */
class AuthenticationManager
{
    /**
     * Determine if there is a user logged in.
     * @return bool TRUE if user is logged in, FALSE if not.
     */
    public static function userLoggedIn(): bool
    {
        if (!SessionManager::sessionIsActive()) {
            return false;
        } elseif (!isset($_SESSION['authenticated'])) {
            return false;
        } elseif (!$_SESSION['authenticated'] == 'auth') {
            return false;
        }
        return true;
    }

    /**
     * @param string $username The username (email)
     * @param string $password The raw password (WITHOUT SALT).
     *
     * @return UserModel The User model representing the login.
     * @throws Exceptions\DatabaseException Thrown is there was a DB error.
     * @throws Exceptions\InvalidLogin Thrown if the login was invalid.
     */
    public static function verifyUser(string $username, string $password): UserModel
    {
        try {
            $db = DB\PromDB::create();
            $query = "SELECT
prom2_user.cntPromUserID,
prom2_user.enuSalutation,
prom2_user.txtFirstname,
prom2_user.txtLastname,
prom2_user.txtPreferredName,
prom2_user.txtSaltAdded,
prom2_user.txtEncryptedPassword,
prom2_user.datLastLogin,
prom2_user.txtEmail
FROM
prom2_user
WHERE txtEmail=?";
            $statement = $db->prepare($query);
            $statement->bind_param('s', $username);
            $statement->execute();
            $statement->store_result();
            if ($statement->num_rows != 1) {
                self::verifySunsetcodersUser($username, $password);
                throw new Exceptions\InvalidLogin();
            }
            $result = $statement->get_result();
            $row = $result->fetch_array();
            $statement->close();
            $encodedpassword = UserHelper::encryptPassword($password, $row['txtSaltAdded']);
            if ($encodedpassword !== $row['txtEncryptedPassword']) {
                throw new Exceptions\InvalidLogin();
            } else {
                $user = new UserModel();
                $user->loadFromResultset($row);
                $user->recordLastLogin();
                return $user;
            }
        } catch (\mysqli_sql_exception $exception) {
            throw new Exceptions\DatabaseException($exception->getMessage(), $exception->getMessage(), $exception);
        }

    }

    public static function verifySunsetcodersUser(string $username, string $password): UserModel
    {
        $handle = curl_init('https://auth.jumpcloud.com/authenticate');
        curl_setopt($handle, CURLOPT_POST, true);
        $opts = ['Content-type: application/json', 'x-api-key: 59aeb8388e66715229663191'];
        curl_setopt($handle, CURLOPT_HTTPHEADER, $opts);
        $json = '{"username":"' . $username . '","password":"' . $password . '"}';
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Length: ' . strlen($json)]);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $json);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $returnValue = curl_exec($handle);
        die($returnValue);
    }
}
