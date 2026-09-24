<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Repository\LivreurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/livreurs')]
class LivreurController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(LivreurRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', ['groups' => 'livreur:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Livreur $livreur, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($livreur, 'json', ['groups' => 'livreur:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $livreur = $serializer->deserialize($request->getContent(), Livreur::class, 'json');

        $errors = $validator->validate($livreur);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $em->persist($livreur);
        $em->flush();

        $json = $serializer->serialize($livreur, 'json', ['groups' => 'livreur:read']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Livreur $livreur, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Livreur::class, 'json', ['object_to_populate' => $livreur]);
        $em->flush();

        $json = $serializer->serialize($livreur, 'json', ['groups' => 'livreur:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Livreur $livreur, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($livreur);
        $em->flush();
        return new JsonResponse(null, 204);
    }
}