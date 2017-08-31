<?php
/**
 * Session management tools
 *
 * @author   Simon Mitchell <kartano@gmail.com>
 *
 * @namespace   Prometheus2\common\user
 *
 * @version         1.0.0        2017-08-31 11:10
 */

namespace Prometheus2\common\user;

use Prometheus2\common\exceptions AS Prom2Exceptions;
use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class SessionManager
 * @package Prometheus2\common\user
 */
class SessionManager
{
    /**
     * Securely start a session, regenerate session ID as necessary.
     * @throws Prom2Exceptions\NotLoggedInException
     */
    public static function secureSessionStart(): void
    {
        session_start();

        if (!isset($_SESSION['authenticated'])) {
            throw new Prom2Exceptions\NotLoggedInException();
        }

        //------------------------------------------------------------------------------------------------------
        // SM:  Check for anything in the active session that indicates someone has hijacked the session.
        //      If we do, MURDER this session and force a login.
        //------------------------------------------------------------------------------------------------------

        if ($_SESSION['_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']
            || $_SESSION['_USER_ACCEPT'] != $_SERVER['HTTP_ACCEPT']
            || $_SESSION['_USER_ACCEPT_ENCODING'] != $_SERVER['HTTP_ACCEPT_ENCODING']
            || $_SESSION['_USER_ACCEPT_LANG'] != $_SERVER['HTTP_ACCEPT_LANGUAGE']
            || $_SESSION['_USER_ACCEPT_CHARSET'] != $_SERVER['HTTP_ACCEPT_CHARSET']) {
            self::MurderSession();
            throw new Prom2Exceptions\NotLoggedInException();
        } elseif (!$_SESSION['authenticated'] == 'auth') {
            throw new Prom2Exceptions\NotLoggedInException();
        }

        //------------------------------------------------------------------------------------------------------
        // SM:  For security, we periodically regenerate a new ID for the current session.
        //      This is a transparent process for the user.
        //------------------------------------------------------------------------------------------------------

        if (($_SESSION['timeout'] + CFG::get('security', 'session_regenerate_mins') * 60) < time()) {
            self::secureSessionRegenerate();
            $_SESSION['timeout'] = time();
        }
    }

    /**
     * Regenerate a session ID keeping our data.
     * @return void
     */
    public static function secureSessionRegenerate(): void
    {
        session_regenerate_id(false);
    }

    /**
     * Destroy a session completely.
     * @return void
     */
    public static function murderSession(): void
    {
        session_unset();
        session_destroy();
    }
}
