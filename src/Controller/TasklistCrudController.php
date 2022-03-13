<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Tasklist;
use App\Form\TasklistCreationFormType;
use App\Repository\TasklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TasklistCrudController extends AbstractController
{
    /**
     * @Route("/tasklist/creation", name="tasklist_create")
     * @Route("/tasklist/{id}/edit", name="tasklist_edit", requirements={"id":"\d+"})
     */
    public function index(Tasklist $list = null, TasklistRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }

        $tasklists = $repository->findBy(
            ['user' => $this->getUser()->getId(),
            'archived_at' => null],
            ['updated_at' => 'DESC']
        );

        $tasklist = $list ?? new Tasklist();
        $tasklistCreationForm = $this->createForm(TasklistCreationFormType::class, $tasklist);
        $tasklistCreationForm->handleRequest($request);

        if($tasklistCreationForm->isSubmitted() && $tasklistCreationForm->isValid()){
            $tasklist->setUser($this->getUser());
            if(!$tasklist->getId()){
                $tasklist->setCreatedAt(new DateTimeImmutable());
            }
            $tasklist->setUpdatedAt(new DateTimeImmutable());
            $manager->persist($tasklist);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('tasklist_crud/index.html.twig', [
            'tasklists' => $tasklists,
            'activeTasklist' => $list ?? $tasklists[0],
            'tasklistCreationForm' => $tasklistCreationForm->createView()
        ]);
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
