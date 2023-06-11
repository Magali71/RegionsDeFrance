<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegionController extends AbstractController
{
    #[Route('/regions', name: 'app_region', methods: 'GET')]
    public function getRegions(serializerInterface $serializer): Response
    {
        $regions = file_get_contents('https://geo.api.gouv.fr/regions');
        $regionDecode = $serializer->decode($regions, 'json');

        return $this->render('region/index.html.twig', [
            'mesRegions' => $regionDecode,
        ]);
    }
}
