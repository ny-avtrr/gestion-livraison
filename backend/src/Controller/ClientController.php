<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/clients')]
class ClientController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ClientRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', ['groups' => 'client:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Client $client, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($client, 'json', ['groups' => 'client:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');

        $errors = $validator->validate($client);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $em->persist($client);
        $em->flush();

        $json = $serializer->serialize($client, 'json', ['groups' => 'client:read']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Client $client, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Client::class, 'json', ['object_to_populate' => $client]);
        $em->flush();

        $json = $serializer->serialize($client, 'json', ['groups' => 'client:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Client $client, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($client);
        $em->flush();
        return new JsonResponse(null, 204);
    }
}