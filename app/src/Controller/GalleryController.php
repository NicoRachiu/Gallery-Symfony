<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Photos;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotosRepository;
use App\Form\PhotosFormType;
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
    public function __construct(PhotosRepository $photosRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->photosRepository = $photosRepository;
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
}