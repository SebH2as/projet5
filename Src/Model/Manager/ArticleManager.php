<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Article;
use Projet5\Model\Repository\ArticleRepository;

use Projet5\Tools\Request;
use Projet5\Tools\Session;

final class ArticleManager
{
    private ArticleRepository $articleRepo;
    private Request $request;
    private Session $session;

    public function __construct(ArticleRepository $articleRepository, Session $session, Request $request)
    {
        $this->articleRepo = $articleRepository;
        $this->session = $session;
        $this->request = $request;
    }

    public function showByIdmag($idMag): ?array
    {
        return $this->articleRepo->findByIdmag($idMag);
    }

    public function showById($idText): ?Article
    {
        return $this->articleRepo->findById($idText);
    }

    public function countPublishedByType(string $textType): ?array
    {
        return $this->articleRepo->findNumberPubByType($textType);
    }

    public function showAllPublishedByType(string $textType, int $offset, int $nbByPage): ?array
    {
        return $this->articleRepo->findAllPublishedByType($textType, $offset, $nbByPage);
    }

    public function createArticle(int $idMag): bool
    {
        $article = new Article();
        $article->setId_mag($idMag);
        
        $return = $this->articleRepo->newArticle($article);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function showMostRecentArticle(): ?Article
    {
        return $this->articleRepo->findMostRecentArticle();
    }

    public function updateContent(int $idText): bool
    {
        $article = new Article();
        $article->setId_text($idText);
        $article->setContent((string) $this->request->post('content'));
        
        $return = $this->articleRepo->updateContent($article);

        if (!$return) {
            $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
        }

        return $return;
    }

    public function deleteArticle(int $idText): bool
    {
        $dataToErase = $this->articleRepo->findById($idText);

        if ($dataToErase === null) {
            $this->session->setSessionData('error', 'L\'article demandé n\'existe pas');
            return false;
        }

        if (($dataToErase->getArticleCover()) !== null && (file_exists("../public/images/".$dataToErase->getArticleCover()))) {
            unlink("../public/images/".$dataToErase->getArticleCover());
        }
        
        $article = new Article();
        $article->setId_text($idText);
        $this->articleRepo->deleteArticle($article);

        return true;
    }

    public function unsetMainAllArticles(int $idMag): bool
    {
        return $this->articleRepo->unsetMainAllArticles($idMag);
    }

    public function updateMain(int $idText, int $idMag): bool
    {
        $article = $this->articleRepo->findById($idText);

        if ($article === null) {
            $this->session->setSessionData('error', 'L\'article demandé n\'existe pas');
            return false;
        }

        if ($article->getMain() === 0) {
            $this->session->setSessionData('message', 'L\'article a été passé à la une');
            
            $this->articleRepo->unsetMainAllArticles($idMag);

            $article = new Article();
            $article->setId_text($idText);
            $article->setMain(1);
            $return = $this->articleRepo->changeMain($article);

            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }
    
            return $return;
        }

        if ($article->getMain() === 1) {
            $this->session->setSessionData('message', 'L\'article a été retiré de la une');
            
            $article = new Article();
            $article->setId_text($idText);
            $article->setMain(0);
            $return = $this->articleRepo->changeMain($article);

            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }
    
            return $return;
        }
    }

    public function updateArticle(int $idText): bool
    {
        $article = new Article();
        $article->setId_text($idText);
        
        if (mb_strlen($this->request->post('title')) > 70
        || mb_strlen($this->request->post('author')) > 30 || mb_strlen($this->request->post('teaser')) > 95) {
            $this->session->setSessionData('error', 'Le champ renseigné ne respecte pas le nombre de caractères autorisés');
            return false;
        }

        if ($this->request->post('rubric') !== null && !empty($this->request->post('rubric'))
        && !empty($this->request->post('modifRubric'))) {
            $this->session->setSessionData('message', 'La rubrique de l\'article a été modifié');
            
            $article->setTextType((string) $this->request->post('rubric'));

            $return = $this->articleRepo->modifTextType($article);
            
            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }

            return $return;
        }

        if ($this->request->post('title') !== null && !empty($this->request->post('title'))
        && !empty($this->request->post('modifTitle'))) {
            $this->session->setSessionData('message', 'Le titre de l\'article a été modifié');
            
            $article->setTitle((string) $this->request->post('title'));

            $return = $this->articleRepo->modifTitle($article);
            
            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }

            return $return;
        }

        if ($this->request->post('author') !== null && !empty($this->request->post('author'))
        && !empty($this->request->post('modifAuthor'))) {
            $this->session->setSessionData('message', 'L\' auteur de l\'article a été modifié');
            
            $article->setAuthor((string) $this->request->post('author'));

            $return = $this->articleRepo->modifAuthor($article);
            
            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }

            return $return;
        }

        if ($this->request->post('teaser') !== null && !empty($this->request->post('teaser'))
        && !empty($this->request->post('modifTeaser'))) {
            $this->session->setSessionData('message', 'Le teaser de l\'article a été modifié');
            
            $article->setTeaser((string) $this->request->post('teaser'));

            $return = $this->articleRepo->modifTeaser($article);
            
            if (!$return) {
                $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
            }

            return $return;
        }

        if (!empty($this->request->post('modifCover'))) {
            $cover = $_FILES['articleCover'];
            $ext = mb_strtolower(mb_substr($cover['name'], -3)) ;
            $allowExt = ["jpg", "png"];

            $fileName = "../public/images/".$cover['name'];
            if (file_exists($fileName)) {
                $this->session->setSessionData('error', 'Cette image est déjà utilisée. Veuillez modifier son nom pour l\'uploader à nouveau');
                return false;
            }
            
            if (in_array($ext, $allowExt, true)) {
                $dataToErase = $this->articleRepo->findById($idText);
                
                if (($dataToErase->getArticleCover()) !== null && (file_exists("../public/images/".$dataToErase->getArticleCover()))) {
                    unlink("../public/images/".$dataToErase->getArticleCover());
                }
                
                move_uploaded_file($cover['tmp_name'], "../public/images/".$cover['name']);

                $this->session->setSessionData('message', 'La couverture de l\'article a été modifiée');

                $article->setArticleCover((string) $cover['name']);

                $return = $this->articleRepo->modifCover($article);

                if (!$return) {
                    $this->session->setSessionData('error', 'Une erreur est survenue, veuillez recommencer');
                }
                return $return;
            }
        }

        $this->session->setSessionData('error', 'Le champ visé ne contient aucune donnée');
        return false;
    }
}
