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
        if (isset($_SESSION['prom2authenticated'])) {
            return true;
        } else {
            return false;
        }
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
                $user=self::verifySunsetcodersUser($username, $password);
                if ($user===null) {
                    throw new Exceptions\InvalidLogin();
                } else {
                    return $user;
                }
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
        // http://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/

        $handle=ldap_connect('ldap.forumsys.com',389);
        if ($handle===false) {
            throw new Exceptions\InvalidLogin("LDAP connection failure");
        }
        ldap_set_option($handle, LDAP_OPT_PROTOCOL_VERSION, 3);
        $dn="uid=$username,dc=example,dc=com";
        if (ldap_bind($handle, $dn, $password)===false) {
            throw new Exceptions\InvalidLogin(ldap_error($handle),ldap_errno($handle));
        }
        $result=ldap_search($handle,$dn, "(uid=*)");
        if ($result===false) {
            throw new Exceptions\InvalidLogin(ldap_error($handle),ldap_errno($handle));
        }
        $settings=ldap_get_entries($handle, $result);
        if ($settings===false) {
            throw new Exceptions\InvalidLogin(ldap_error($handle),ldap_errno($handle));
        }
        if($settings['count']!=1) {
            throw new Exceptions\InvalidLogin("LDAP entries failure");
        }
        $user=new UserModel();
        $user->email=$settings[0]['mail'][0];
        $user->firstname=ucwords(explode(' ',$settings[0]['cn'][0])[0]);
        $user->lastname=ucwords($settings[0]['uid'][0]);
        $user->lastLogin=date('c');
        $user->preferredName=$user->firstname.' '.$user->lastname;
        $user->promUserID=0;
        $user->salutation='';
        $user->isSunsetcoders=true;
        return $user;

        /*
        $client = new GH\Client();
        try {
            $body = [
                'username' => $username,
                'password' => $password
            ];
            $json = json_encode($body);
            $response = $client->request('POST', 'https://auth.jumpcloud.com/authenticate',  ['body' => $json, 'headers' => [
                'x-api-key' => CFG::get('LDAP','apikey'),
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($json)
            ]]);
            echo "<pre>";
            print_r($response);
            echo "</pre>";
            die();
        } catch (GH\Exception\ClientException $exception) {
            echo "<pre>";
            print_r($exception);
            echo "<pre>";
            die();
        }
        */
    }
}
