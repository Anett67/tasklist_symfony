<?php

namespace App\Controller;

use App\Entity\Task;
use DateTimeImmutable;
use App\Entity\Tasklist;
use App\Form\TaskFormType;
use App\Repository\TasklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskCrudController extends AbstractController
{
    /**
     * @Route("tasklist/{tasklist}/task/creation", name="task_create", requirements={"tasklist":"\d+"})
     * @Route("tasklist/{tasklist}/task/{task}/edit", name="task_edit", requirements={"tasklist":"\d+", "task":"\d+"})
     */
    public function index(Tasklist $tasklist = null, Task $task = null,TasklistRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }

        $tasklists = $repository->findBy(
            ['user' => $this->getUser()->getId(),
            'archived_at' => null],
            ['updated_at' => 'DESC']
        );

        $task = $task ?? new Task();
        $taskForm = $this->createForm(TaskFormType::class, $task);
        $taskForm->handleRequest($request);

        if($taskForm->isSubmitted() && $taskForm->isValid()){
            if(!$task->getId()){
                $task->setCreatedAt(new DateTimeImmutable());
            }
            $task->setTasklist($tasklist);
            $task->setUpdatedAt(new DateTimeImmutable());
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('tasklist-selected', array('id' => $tasklist->getId()));
        }
        
        return $this->render('task_crud/index.html.twig', [
            'tasklists' => $tasklists,
            'activeTasklist' => $list ?? $tasklists[0],
            'taskForm' => $taskForm->createView()
        ]);
    }
}
