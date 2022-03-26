<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\TasklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserCrudController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(TasklistRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }

        $tasklists = $repository->findBy(
            ['user' => $this->getUser()->getId(),
            'archived_at' => null],
            ['updated_at' => 'DESC']
        );

        $userForm = $this->createForm(UserFormType::class, $this->getUser());

        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()) {
            $manager->persist($this->getUser());
            $manager->flush();

            $this->addFlash('success',  'La modification a bien été effectué.');
            return $this->redirectToRoute('profil');
        }

        return $this->render('user_crud/index.html.twig', [
            'tasklists' => $tasklists,
            'activeTasklist' => $list ?? $tasklists[0],
            'userForm' => $userForm->createView()
        ]);
    }
}
