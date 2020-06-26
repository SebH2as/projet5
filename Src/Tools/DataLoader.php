<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Model\MagManager;
use Projet5\Model\ArticleManager;
use Projet5\View\View;
use Projet5\Tools\Request;

class DataLoader{
        
    private $magManager;
    private $articleManager;
    private $view;
    private $request;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->request = new request();
    }

    public function addData( $manager,  $id,  $method,  $column, $text = null )
    {
        if($this->request->post($method) !== null &&  !empty($this->request->post($column)))
        {
            $this->$manager->$method( (int) $this->request->get($id), (string) $this->request->post($column));
             
        }
        
    }

    public function key($key){
        return $key;
    }

}