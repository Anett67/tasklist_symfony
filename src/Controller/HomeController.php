<?php

namespace App\Controller;

use App\Entity\Tasklist;
use App\Form\TasklistCreationFormType;
use App\Repository\TasklistRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Route("/tasklist/{id}", name="tasklist-selected")
     */
    public function index(Tasklist $list = null,TasklistRepository $repository, Request $request, EntityManagerInterface $manager): Response
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

        return $this->render('home/index.html.twig', [
            'tasklists' => $tasklists,
            'activeTasklist' => $list ?? $tasklists[0],
            'tasklistCreationForm' => $tasklistCreationForm->createView()
        ]);
    }
}
