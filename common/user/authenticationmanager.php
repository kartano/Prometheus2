<?php
/**
 * Authentication management.
 *
 * @author   Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\user
 *
 * @version         1.0.0        2017-08-31 09:51
 */
 namespace Prometheus2\common\user;

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
 }
