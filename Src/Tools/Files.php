<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Model\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Tools\Request;
use Projet5\View\View;

class Files
{
    private $magManager;
    private $articleManager;
    private $view;
    private $request;
    private $noCsrf;

    public function __construct()
    {
        $this->view = new View();
        $this->magManager = new magManager();
        $this->articleManager = new articleManager();
        $this->request = new request();
        $this->noCsrf = new noCsrf();
    }

    public function addFiles($manager, $post, $content, $id, $text, $template, $method): void
    {
        if ($this->request->post($post) !== null) {
            $cover = $_FILES[$content];
            $ext = mb_strtolower(mb_substr($cover['name'], -3)) ;
            $allowExt = ["jpg", "png"];
            if (in_array($ext, $allowExt, true)) {
                $dataToErase = $this->$manager->$method((int) $this->request->get($id));
                if (($dataToErase[0]->$content) !== null) {
                    unlink("../public/images/".$dataToErase[0]->$content);
                }
                move_uploaded_file($cover['tmp_name'], "../public/images/".$cover['name']);
                $this->$manager->modifCover((int) $this->request->get($id), (string) $cover['name']);
                
                $message = $text;
                $token = $this->noCsrf->createToken();
                $data = $this->$manager->$method((int) $this->request->get($id));
                $this->view->render('back/' . $template, 'back/layout', compact('data', 'message', 'token'));
                exit();
            }
        }
    }
}
