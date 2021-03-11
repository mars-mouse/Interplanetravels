<?php

namespace App\Controller;

use App\Entity\ImageTravel;
use App\Entity\Travel;
use App\Form\ImageTravelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/image", name="image_")
 */
class ImageController extends AbstractController
{
    // Récupération du chemin configuré dans services.yaml (bind)
    public function __construct(string $images_local_directory, string $images_site_directory)
    {
        $this->images_local_directory = $images_local_directory;
        $this->images_site_directory = $images_site_directory;
    }

    /**
     * @Route("/travel_add", name="travel_add")
     */
    public function addImageTravel(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $imageTravel = new ImageTravel();
        $form = $this->createForm(ImageTravelFormType::class, $imageTravel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le champ filename n'est pas mapped, on le récupère à part
            $imageFile = $form->get('filename')->getData();

            // on génère un nom de fichier à partir du nom du voyage qu'on passe au Slugger
            // https://symfony.com/doc/current/components/string.html#slugger
            $sluggedTravelName = $slugger->slug($imageTravel->getTravel()->getName());
            $newFilename = $sluggedTravelName . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Amélioration possible : mise en forme de l'image

            // On déplace le fichier dans le bon répertoire

            try {
                $imageFile->move(
                    $this->images_local_directory,
                    $newFilename
                );
            } catch (FileException $e) {
                throw new CannotWriteFileException('File could not be uploaded.');
            }

            // On enregistre le nom du fichier dans le ImageTravel
            $imageTravel->setSource($newFilename);

            $entityManager->persist($imageTravel);
            $entityManager->flush();

            // On renvoie sur la page pour ajouter d'autres images avec une redirection pour réinitialiser le formulaire
            $this->addFlash('successFile', 'The image has successfully been uploaded.');
            return new RedirectResponse($urlGenerator->generate('image_travel_add'));
        }

        return $this->render('image/travel_add.html.twig', [
            'imageTravelForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/from_travel/{id}", name="from_travel")
     */
    public function showImageFromTravel(Travel $travel)
    {
        return $this->render('image/from_travel.html.twig', [
            'travel' => $travel,
            'images_site_directory' => $this->images_site_directory,
        ]);
    }

    /**
     * @Route("/delete_imagetravel/{id}", name="delete_imagetravel")
     */
    public function deleteImageFromTravel(ImageTravel $imageTravel, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $travel = $imageTravel->getTravel();

        // 1. Supprimer le fichier
        $filepath = $this->images_local_directory . $imageTravel->getSource();
        if ($filepath) {
            unlink($filepath);
        }

        // 2. Supprimer l'entrée dans la DB
        $entityManager->remove($imageTravel);
        $entityManager->flush();

        return new RedirectResponse($urlGenerator->generate('image_from_travel', ['id' => $travel->getId()]));
    }
}