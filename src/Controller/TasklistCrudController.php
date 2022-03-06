<?php

namespace App\Controller;

use App\Entity\Tasklist;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasklistCrudController extends AbstractController
{
    /**
     * @Route("/tasklist/creation", name="tasklist_create")
     * @Route("/tasklist/modification", name="tasklist_edit")
     */
    public function index(): Response
    {

    }

    /**
     * @Route("/tasklist/{id}/archive", name="tasklist_archive", requirements={"id":"\d+"})
     */
    public function tasklistArchive(Tasklist $tasklist, Request $request, EntityManagerInterface $manager): Response
    {
        if($this->isCsrfTokenValid('ARCH' . $tasklist->getId(), $request->get('_token'))){
            $tasklist->setArchivedAt($tasklist->getArchivedAt() ? null : new DateTimeImmutable());
            $manager->persist($tasklist);
            $manager->flush();
            $this->addFlash("success",  "La liste '" . $tasklist->getTitle() . "' a été archivée.");
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/tasklist/{id}/delete", name="tasklist_delete", requirements={"id":"\d+"})
     */
    public function tasklistDelete(Tasklist $tasklist, Request $request, EntityManagerInterface $manager): Response
    {
        if($this->isCsrfTokenValid('SUP' . $tasklist->getId(), $request->get('_token'))){
            $manager->remove($tasklist);
            $manager->flush();
            $this->addFlash("success",  "La liste '" . $tasklist->getTitle() . "' suppression a été effectuée");
        }
        
        return $this->redirectToRoute('home');
    }
}
