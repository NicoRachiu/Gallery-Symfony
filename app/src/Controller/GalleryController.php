<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Photos;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;
use App\Repository\PhotosRepository;
use App\Repository\CommentRepository;
use App\form\PhotosFormType;
use App\form\CommentsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GalleryController extends AbstractController
{
    private $entityManager;
    private $photosRepository;
    private $userRepository;
    private $commentRepository;
    
    public function __construct(PhotosRepository $photosRepository, UsersRepository $userRepository, CommentRepository $commentRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->photosRepository = $photosRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    #[Route('/gallery', name: 'app_gallery')]
    public function index(): Response
    {
        $repository = $this->entityManager->getRepository(Photos::class);
        $photos = $repository->findAll();

        return $this->render('gallery/index.html.twig', [
            'photos' => $photos,
        ]);
    }

    #[Route('/photo/{title}', name: 'app_photo_show')]
    public function showPhoto(string $title): Response
    {
        $photo = $this->entityManager->getRepository(Photos::class)->findOneBy(['title' => $title]);
        if (!$photo) {
            throw $this->createNotFoundException('La foto non è stata trovata.');
        }

        return $this->render('gallery/photo.html.twig', [
            'photo' => $photo,
        ]);
    }

    #[Route('/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $photo = new Photos();
        $form = $this->createForm(PhotosFormType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($photo);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_gallery');
        }

        return $this->render('gallery/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/delete/{title}', name: 'delete_photos')]
    public function delete(Request $request, string $title): Response
    {
        $photo = $this->photosRepository->findOneBy(['title' => $title]);

        if (!$photo) {
            throw $this->createNotFoundException('The photo does not exist');
        }

        $this->entityManager->remove($photo);
        $this->entityManager->flush();                      

        // Redirect to gallery index page after deletion
        return $this->redirectToRoute('app_gallery');
    }
    #[Route('/users', name: 'users')]
    public function user(): Response
    {
        $repository = $this->entityManager->getRepository(Users::class);  
        $users = $repository->findAll();

        return $this->render('gallery/user.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/photos', name: 'photos')]
    public function photos(): Response
    {
        $repository = $this->entityManager->getRepository(Photos::class);
        $photo = $repository->findAll();

        return $this->render('gallery/photos.html.twig', [
            'photos' => $photo,
        ]);
    }
    #[Route('/commentcreate', name: 'commentcreate')]
    public function createComments(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentsFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_gallery');
        }

        return $this->render('gallery/comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/edit/{title}', name: 'edit_photo')]
    public function edit(Request $request, $title): Response {
        $photo = $this->entityManager->getRepository(Photos::class)->findOneBy(['title' => $title]);
        $form = $this->createForm(PhotosFormType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_gallery');
        }

        return $this->render('gallery/edit.html.twig', [
            'form' => $form->createView(),
            'photo' => $photo,
        ]);
    }
    #[Route('/comment', name: 'comment')]
    public function comment(): Response
    {
        $repository = $this->entityManager->getRepository(Comment::class);  
        $comment = $repository->findAll();

        return $this->render('gallery/comments.html.twig', [
            'comments' => $comment,
        ]);
    }
    #[Route('/deleteComment/{title}', name: 'delete_comment')]
    public function deleteComment(Request $request, string $title): Response
    {
        // Usa il repository corretto per Comment
        $comment = $this->commentRepository->findOneBy(['title' => $title]);

        if (!$comment) {
            throw $this->createNotFoundException('The Comment does not exist');
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();                      

        return $this->redirectToRoute('comment');
    }
}