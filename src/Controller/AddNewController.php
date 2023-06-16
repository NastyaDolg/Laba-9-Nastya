<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use App\Form\AddNewsType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
class AddNewController extends AbstractController
{
    #[Route('/addNew', name: 'app_addNew')]
    public function register(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        if ($this->getUser() == null ) {
            return $this->redirectToRoute('app_login');
       }

        $new = new News();
        $form = $this->createForm(AddNewsType::class, $new);


        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $new = $form->getData();
            $date = new \DateTime('@'.strtotime('now + 3 hours'));
            $new->setDateLoad($date);
            $new->setViewsNum(0);
            $new->setUser($user); 

            $filename = $form->get('fotopath')->getData();
            if ($filename) {
                $originalFilename = pathinfo($filename->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$filename->guessExtension();

                try {
                    $filename->move(
                        $this->getParameter('newsfoto_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return $this->render('Forms/AddNewsForm/index.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                $new->setfotopath($newFilename);
            }
            $manager = $doctrine->getManager();
            $manager->persist($new);
            $manager->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('Forms/AddNewsForm/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
