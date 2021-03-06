<?php
/**
 * Created by PhpStorm.
 * User: abdujabbor
 * Date: 6/21/18
 * Time: 3:06 PM
 */

class Session
{
    public static $instance;
    private function __construct()
    {
        session_start();
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setFlash($name, $flash) {
        $_SESSION[$name] = $flash;
    }

    public function getFlash($name) {
        if(!empty($_SESSION[$name])) {
            $flash = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $flash;
        }
        return null;
    }
}