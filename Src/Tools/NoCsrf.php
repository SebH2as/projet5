<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Tools\Session;
use Projet5\Tools\Request;

class NoCsrf 
{
    private $session;
    private $request;
   
    public function __construct()
    {
        $this->session = new Session();
        $this->request = new Request();
    }
     
    public function createToken(): ?string
    {
        $this->session->setSessionData("token", bin2hex(random_bytes(64)));
        return($this->session->getSessionData("token"));
    }

    public function isTokenValid($param):bool
    {
        return($this->session->getSessionData("token") === $param);
    }
}