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

#[Route('/organisation', name: 'organisation_')]
class OrganisationController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        $organisations = $organisationRepository->findAll();

        return $this->render('organisation/index.html.twig', [
            'organisations' => $organisations,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d{1,3}'], methods: 'get')]
    public function show(Organisation $organisation): Response
    {
        return $this->render('organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['get', 'post'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($organisation);
            $manager->flush();

            $this->addFlash('success', "L'association {$organisation->getName()} a bien été créée");

            return $this->redirectToRoute('organisation_show', [
                'id' => $organisation->getId()
            ]);
        }

        return $this->render('organisation/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d{1,3}'], methods: ['get', 'post'])]
    public function update(Organisation $organisation, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "L'association {$organisation->getName()} a bien été modifiée");

            return $this->redirectToRoute('organisation_show', [
                'id' => $organisation->getId()
            ]);
        }

        return $this->render('organisation/update.html.twig', [
            'form' => $form,
            'organisation' => $organisation
        ]);
    }

    #[Route('/delete/{id}/{token}', name: 'delete', requirements: ['id' => '\d{1,3}'], methods: 'get')]
    public function delete(Organisation $organisation, string $token, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $organisation->getId(), $token)) {
            $manager->remove($organisation);
            $manager->flush();

            $this->addFlash('success', "L'association {$organisation->getName()} a bien été supprimée");
        }

        return $this->redirectToRoute('organisation_index');
    }
}
