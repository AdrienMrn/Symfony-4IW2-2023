<?php

namespace App\Controller\Front;

use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/organisation', name: 'organisation_')]
#[IsGranted('ROLE_COORDINATOR')]
class OrganisationController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        $organisations = $organisationRepository->findAll();

        return $this->render('front/organisation/index.html.twig', [
            'organisations' => $organisations,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: 'get')]
    #[Security('organisation.isMember(user)')]
    public function show(Organisation $organisation): Response
    {
        return $this->render('front/organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }
}
