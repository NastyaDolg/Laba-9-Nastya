<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\News;
use App\Entity\Comments;

use App\Form\AddCommentType;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DetailPageController extends AbstractController
{
    #[Route('/news/{id}', name: 'app_detail_page')]
    public function index($id, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $manager = $doctrine->getManager();
        $repository = $doctrine->getRepository(News::class);
        $new = $repository->find($id);

        if($new->getActive() == false){
            return $this->redirect('/');
        }
        $user_new = $new->getUser();

        if($user_new != $this->getUser() && $this->getUser() != null)
        {
            $viewsNum = $new->getViewsNum();
            $new->setViewsNum($viewsNum + 1);
            $manager->persist($new);
            $manager->flush();
        }
        
        $comments = $new->getComments();

        //Добавление комментария
        $comment = new Comments();
        $user = $this->getUser();
        $form_comment = $this->createForm(AddCommentType::class, $comment);
        $form_comment->handleRequest($request);

        if ($form_comment->isSubmitted() && $form_comment->isValid())
        {
            $date = new \DateTime('@'.strtotime('now + 3 hours'));
            $comment->setDateLoad($date);
            $comment->setUser($user);
            $comment->setNew($new);
 
            $comment->setActive(false);

            $entityManager->persist($comment);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirect('/#');

        }
        return $this->render('detail_page/index.html.twig', [
            'new' => $new,
            'comments' => $comments,
            'form_comment' => $form_comment->createView(),

        ]);
    }
}
