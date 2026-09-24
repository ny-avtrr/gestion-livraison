<?php

namespace App\Controller;

use App\Repository\StatutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/statuts')]
class StatutController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(StatutRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findBy([], ['ordre' => 'ASC']), 'json', ['groups' => 'statut:read']);
        return new JsonResponse($json, 200, [], true);
    }
}