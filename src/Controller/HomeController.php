<?php

namespace App\Controller;

use App\Entity\Tasklist;
use App\Repository\TasklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Route("/{id}", name="tasklist-selected")
     */
    public function index(Tasklist $list = null,TasklistRepository $repository): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }

        $tasklists = $repository->findBy(
            ['user' => $this->getUser()->getId(),
            'archived_at' => null],
            ['updated_at' => 'DESC']
        );

        return $this->render('home/index.html.twig', [
            'tasklists' => $tasklists,
            'activeTask' => $list ?? $tasklists[0]
        ]);
    }

}
