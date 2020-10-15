<?php
declare(strict_types=1);

namespace Projet5\Tools;

class Session
{
    private $_session;
   

    public function adminControl():void
    {
        if (empty($_SESSION['admConnected'])) {
            header('Location: index.php');
            exit();
        }
    }

    public function userControl():void
    {
        if (empty($_SESSION['userConnected'])) {
            header('Location: index.php');
            exit();
        }
    }

    public function setSessionData(string $session_name, ?string $data):void
    {
        $_SESSION[$session_name] = $data;
    }
    
    public function getSessionData(string $session_name): ?string
    {
        if (isset($_SESSION[$session_name])) {
            return $_SESSION[$session_name];
        }
        unset($_SESSION[$session_name]);
        return null;
    }
}
