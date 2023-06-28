<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegionController extends AbstractController
{
    #[Route('/regions', name: 'app_region', methods: 'GET')]
    public function getRegions(serializerInterface $serializer): Response
    {
        $regions = file_get_contents('https://geo.api.gouv.fr/regions');
        $regions = $serializer->deserialize($regions, 'App\Entity\Region[]', 'json');

        return $this->render('region/index.html.twig', [
            'mesRegions' => $regions,
        ]);
    }

    #[Route('/departements', name: 'app_departement', methods: 'GET')]
    public function getDepByRegions(Request $request, serializerInterface $serializer): Response
    {
        // je récupère la région sélectionnée dans le formulaire
        // region provient du formulaire
        $codeRegion = $request->query->get('region');
        $regions = file_get_contents('https://geo.api.gouv.fr/regions');
        $regions = $serializer->deserialize($regions, 'App\Entity\Region[]', 'json');

        if ($codeRegion === null || $codeRegion === 'all') {
            $departements = file_get_contents('https://geo.api.gouv.fr/departements');
        } else {
            $departements = file_get_contents('https://geo.api.gouv.fr/regions/' . $codeRegion . '/departements');
        }

        $departements = $serializer->decode($departements, 'json');

        return $this->render('region/listDepartement.html.twig', [
            'mesRegions' => $regions,
            'mesDepartements' => $departements
        ]);
    }
}
