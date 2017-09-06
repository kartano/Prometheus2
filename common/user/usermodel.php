<?php
/**
 * Model that describes a user.
 *
 * @author   Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\user
 *
 * @version         1.0.0        2017-09-04 15:52
 */

namespace Prometheus2\common\user;

use Prometheus2\common\database AS DB;
use Prometheus2\common\exceptions AS Exceptions;

/**
 * @property int promUserID  The DB login ID for this person.  This will be 0 if the user was authenticated against LDAP for SunsetCoders.
 * @property string salutation The salutation for this user.
 * @property string firstname The user's firstname.
 * @property string lastname The user's lastname.
 * @property string lastLogin The user's last login in ISO8601 format.
 * @property string email The user's email address, as used to log in.
 * @property bool isSunsetcoders If TRUE then this user is a SunsetCoders operator.
 */

/**
 * Class UserModel
 * @package Prometheus2\common\user
 */
class UserModel
{
    /**
     * @var array Internal data array
     */
    protected $data = [];

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        $this->promUserID = 0;
        $this->salutation = '';
        $this->firstname = '';
        $this->lastname='';
        $this->preferredName = '';
        $this->lastLogin = '';
        $this->email = '';
        $this->isSunsetcoders=false;
    }

    /**
     * Load the properties directly from a result set.
     * @param array $row The associated array from the user name.
     */
    public function loadFromResultset(array $row): void
    {
        $this->promUserID = intval($row['cntPromUserID']);
        $this->salutation = $row['enuSalutation'];
        $this->firstname = $row['txtFirstname'];
        $this->lastname = $row['txtLastname'];
        $this->preferredName = $row['txtPreferredName'];
        $this->lastLogin = $row['datLastLogin'];
        $this->email = $row['txtEmail'];
    }

    /**
     * @param string $name Name of attribute to return.
     * @return mixed
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     * @param string $name Name of attribute to set,
     * @param mixed $value The value to set.
     */
    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    public function recordLastLogin(): void
    {
        // SM:  If this user auth came from LDAP, then do NOT update the last login.
        if ($this->promUserID == 0) {
            return;
        }

        $db = DB\PromDB::create();
        $query = "UPDATE prom2_user
            SET datLastLogin=NOW()
            WHERRE cntPromUserID=?";
        $statement = $db->prepare($query);
        $statement->bind_param('i', $this->promUserID);
        $statement->execute();
        $statement->close();
        $db->close();
    }
}
