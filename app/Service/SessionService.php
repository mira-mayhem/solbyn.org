<?php

namespace Service;

class SessionService
{
    private $sessionName = "solbyn";
    private static $sessionBlob;
    private $sessionLifetime = 60000;
    private static $sessionStarted;

    public function __construct()
    {
        if (self::$sessionStarted !== true) {
            session_start();
            self::$sessionStarted = true;
        }
        if (isset($_SESSION[$this->sessionName])) {
            self::$sessionBlob = $this->unscramble($_SESSION[$this->sessionName]);
            $this->validateSessionLifetime();
        } else {
            self::$sessionBlob = [];
        }
    }

    private function validateSessionLifetime()
    {
        if (strlen($this->get('auth')) > 0 && $this->get('auth')) {
            if (strlen($this->get('lifetime')) == 0) {
                $this->setLifetime();
            }
            error_log("Time remaininge: " . ($this->get('lifetime') - time()));
            if ($this->get('lifetime') < time()) {
                self::$sessionStarted = false;
                session_unset();
                session_destroy();
            }
        }
    }

    public function setLifetime($lifetimeCycle = null)
    {
        if (!isset($lifetimeCycle)) {
            $lifetimeCycle = $this->sessionLifetime;
        }
        $this->set('lifetime', time() + $lifetimeCycle);
        return $this;
    }

    public function get($variable, $default = '')
    {
        return isset(self::$sessionBlob[$variable]) ? self::$sessionBlob[$variable] : $default;
    }

    private function unscramble($blob)
    {
        $blob = base64_decode($blob);
        $blob = json_decode($blob, true);
        return $blob;
    }

    private function scramble($blob)
    {
        $blob = json_encode($blob);
        $blob = base64_encode($blob);
        return $blob;
    }

    public function set($parameter, $value)
    {
        self::$sessionBlob[$parameter] = $value;
        return $this;
    }

    public function __destruct()
    {
        $this->save();
    }

    public function save()
    {
        ob_start();
        var_dump(self::$sessionBlob);
        $data = ob_get_clean();
        error_log($data);
        $blob = $this->scramble(self::$sessionBlob);
        $_SESSION[$this->sessionName] = $blob;
    }
}
