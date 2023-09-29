<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrganisationController extends AbstractController
{
    #[Route('/organisation', name: 'organisation_index')]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        $organisations = $organisationRepository->findAll();

        return $this->render('organisation/index.html.twig', [
            'organisations' => $organisations,
        ]);
    }

    #[Route('/organisation/{id}', name: 'organisation_show', requirements: ['id' => '\d+'])]
    public function show(Organisation $organisation): Response
    {
        return $this->render('organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }

    #[Route('/organisation/new', name: 'organisation_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($organisation);
            $manager->flush();

            return $this->redirectToRoute('organisation_show', [
                'id' => $organisation->getId()
            ]);
        }

        return $this->render('organisation/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
