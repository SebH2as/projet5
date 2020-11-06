<?php

declare(strict_types=1);

namespace Projet5\Model\Manager;

use Projet5\Model\Entity\Mag;
use Projet5\Model\Repository\MagRepository;

use Projet5\Tools\Request;
use Projet5\Tools\Session;

final class MagManager
{
    private MagRepository $magRepo;
    private Request $request;
    private Session $session;

    public function __construct(MagRepository $magRepository, Session $session, Request $request)
    {
        $this->magRepo = $magRepository;
        $this->session = $session;
        $this->request = $request;
    }

    public function showLastAndPub(): ?Mag
    {
        return $this->magRepo->findByLastAndPub();
    }

    public function showByIdAndPub(int $idMag): ?Mag
    {
        return $this->magRepo->findByIdAndPub($idMag);
    }

    public function showById(int $idMag): ?Mag
    {
        return $this->magRepo->findById($idMag);
    }

    public function showByNumber(int $numberMag): ?Mag
    {
        return $this->magRepo->findByNumber($numberMag);
    }

    public function countMag(): ?array
    {
        return $this->magRepo->findCountMag();
    }

    public function countPubMag(): ?array
    {
        return $this->magRepo->findCountPubMag();
    }

    public function showAllMag(int $offset, int $nbByPage): ?array
    {
        return $this->magRepo->findAllMag($offset, $nbByPage);
    }

    public function showAllPubMag(int $offset, int $nbByPage): ?array
    {
        return $this->magRepo->findAllPubMag($offset, $nbByPage);
    }

    public function createMag(int $numberMag): bool
    {
        return $this->magRepo->newMag($numberMag);
    }

    public function deleteMagById(int $idMag): void
    {
        $this->magRepo->deleteMagById($idMag);
    }

    public function showAllNumberMag(): array
    {
        return $this->magRepo->getAllNumberMag();
    }

    public function showPubNumberMag(): array
    {
        return $this->magRepo->getPubNumberMag();
    }

    public function countNumberMag(int $number): ?array
    {
        return $this->magRepo->countNumberMag($number);
    }

    public function updateMag(int $idMag): bool
    {
        $mag = new Mag();
        $mag->setId_mag($idMag);
        
        if ($this->request->post('publication') !== null && !empty($this->request->post('publication'))
        && !empty($this->request->post('modifPublication'))) {
            $this->session->setSessionData('message', 'La date de publication du magazine a été modifié');

            $mag->setPublication((string) $this->request->post('publication'));

            $return = $this->magRepo->modifPublication($mag);
            
            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }

            return $return;
        }

        if ($this->request->post('title01') !== null && !empty($this->request->post('title01'))
        && !empty($this->request->post('modifTitle01'))) {
            $this->session->setSessionData('message', 'Le titre 1 du magazine a été modifié');

            $mag->setTitle01((string) $this->request->post('title01'));

            $return = $this->magRepo->modifTitle01($mag);
            
            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }
            
            return $return;
        }

        if (!empty($this->request->post('deleteTitle01'))) {
            $this->session->setSessionData('message', 'Le titre 1 du magazine a été supprimmé');
            
            $mag->setTitle01(null);

            $return = $this->magRepo->modifTitle01($mag);
            
            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }
            
            return $return;
        }

        if ($this->request->post('title02') !== null && !empty($this->request->post('title02'))
        && !empty($this->request->post('modifTitle02'))) {
            $this->session->setSessionData('message', 'Le titre 2 du magazine a été modifié');

            $mag->setTitle02((string) $this->request->post('title02'));

            $return = $this->magRepo->modifTitle02($mag);
            
            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }
            
            return $return;
        }

        if (!empty($this->request->post('deleteTitle02'))) {
            $this->session->setSessionData('message', 'Le titre 2 du magazine a été supprimmé');
            
            $mag->setTitle02(null);

            $return = $this->magRepo->modifTitle02($mag);
            
            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }
            
            return $return;
        }

        if (!empty($this->request->post('modifCover'))) {
            $cover = $_FILES['cover'];
            $ext = mb_strtolower(mb_substr($cover['name'], -3)) ;
            $allowExt = ["jpg", "png"];
            
            if (in_array($ext, $allowExt, true)) {
                $dataToErase = $this->magRepo->findById($idMag);
                
                if (($dataToErase->getCover()) !== null) {
                    unlink("../public/images/".$dataToErase->getCover());
                }
                
                move_uploaded_file($cover['tmp_name'], "../public/images/".$cover['name']);

                $this->session->setSessionData('message', 'La couverture du magazine a été modifiée');

                $mag->setCover((string) $cover['name']);
                
                $return = $this->magRepo->modifCover($mag);

                if (!$return) {
                    $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
                }
                
                return $return;
            }
        }

        return false;
    }

    public function updateStatusMag(int $idMag): bool
    {
        $magazine = $this->magRepo->findById($idMag);

        if ($magazine === null) {
            $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            return false;
        }

        $mag = new Mag();
        $mag->setId_mag($idMag);

        if ($magazine->getStatusPub() === 0) {
            $this->session->setSessionData('message', 'le magazine a été mis en ligne');
            
            $mag->setStatusPub(1);
            $return = $this->magRepo->updateStatusMag($mag);
            
            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }
            
            return $return;
        }

        if ($magazine->getStatusPub() === 1) {
            $this->session->setSessionData('message', 'le magazine a été sauvegardé');
            
            $mag->setStatusPub(0);
            $return = $this->magRepo->updateStatusMag($mag);

            if (!$return) {
                $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
            }
            
            return $return;
        }
    }

    public function updateEdito(int $idMag): bool
    {
        $mag = new Mag();
        $mag->setId_mag($idMag);
        $mag->setEditorial((string) $this->request->post('contentEdito'));

        $this->session->setSessionData('message', 'L\'éditorial a été mis à jour');
        
        $return = $this->magRepo->modifEdito($mag);

        if (!$return) {
            $this->session->setSessionData('message', 'Une erreur est survenue, veuillez recommencer');
        }
        
        return $return;
    }
}
