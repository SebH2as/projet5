<?php
declare(strict_types=1);

namespace Projet5\Tools;

use Projet5\Model\Manager\ArticleManager;
use Projet5\Model\Manager\MagManager;
use Projet5\Model\Repository\ArticleRepository;
use Projet5\Model\Repository\MagRepository;
use Projet5\Tools\Database;
use Projet5\Tools\NoCsrf;
use Projet5\Tools\Request;
use Projet5\View\View;

class DataLoader
{
    private $magManager;
    private $articleManager;
    private $view;
    private $request;
    private $noCsrf;
    private Database $database;

    public function __construct()
    {
        $this->view = new View();
        $this->database = new Database();
        $this->magRepository = new MagRepository($this->database);
        $this->magManager = new MagManager($this->magRepository);
        $this->articleRepository = new ArticleRepository($this->database);
        $this->articleManager = new ArticleManager($this->articleRepository);
        $this->request = new request();
        $this->noCsrf = new noCsrf();
    }

    public function addDataMag($manager, $methode, $id, $column, $text): void
    {
        $message = null;
        
        if (!empty($this->request->post((string) $column)) && !empty($this->request->post((string) $methode))) {
            $this->$manager->$methode((int) $this->request->get($id), (string) $this->request->post($column));

            $message = $text;
            $magazine = $this->magManager->showById((int) $this->request->get($id));
            $articles = $this->articleManager->showByIdmag((int) $magazine->id_mag);
            $token = $this->noCsrf->createToken();

            $this->view->render(
                [
                'template' => 'back/pannelMag',
                'data' => [
                    'magazine' => $magazine,
                    'articles' => $articles,
                    'message' => $message,
                    'token' => $token,
                    ],
                ],
            );
            exit();
        }
    }

    public function deleteDataMag($manager, $methode, $id, $column, $text): void
    {
        $message = null;

        if (!empty($this->request->post((string) $methode))) {
            $this->$manager->$methode((int) $this->request->get($id));

            $message = $text;
            $magazine = $this->magManager->showById((int) $this->request->get($id));
            $articles = $this->articleManager->showByIdmag((int) $magazine->id_mag);
            $token = $this->noCsrf->createToken();

            $this->view->render(
                [
                'template' => 'back/pannelMag',
                'data' => [
                    'magazine' => $magazine,
                    'articles' => $articles,
                    'message' => $message,
                    'token' => $token,
                    ],
                ],
            );
            exit();
        }
    }

    public function addImgMag($manager, $post, $content, $id, $text, $template): void
    {
        if ($this->request->post($post) !== null) {
            $cover = $_FILES[$content];

            $ext = mb_strtolower(mb_substr($cover['name'], -3)) ;

            $allowExt = ["jpg", "png"];

            if (in_array($ext, $allowExt, true)) {
                $dataToErase = $this->magManager->showById((int) $this->request->get($id));
                
                if (($dataToErase->$content) !== null) {
                    unlink("../public/images/".$dataToErase->$content);
                }

                move_uploaded_file($cover['tmp_name'], "../public/images/".$cover['name']);
                $this->$manager->modifCover((int) $this->request->get($id), (string) $cover['name']);
                
                $message = $text;
                $token = $this->noCsrf->createToken();
                $magazine = $this->magManager->showById((int) $this->request->get($id));
                $articles = $this->articleManager->showByIdmag((int) $magazine->id_mag);

                $this->view->render(
                    [
                    'template' => 'back/pannelMag',
                    'data' => [
                        'magazine' => $magazine,
                        'articles' => $articles,
                        'message' => $message,
                        'token' => $token,
                        ],
                    ],
                );
                exit();
            }
        }
    }
}
