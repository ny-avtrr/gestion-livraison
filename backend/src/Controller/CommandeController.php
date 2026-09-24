<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\SuiviLivraison;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\LivreurRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/commandes')]
class CommandeController extends AbstractController
{
    private const GROUPS = ['commande:read', 'client:read', 'livreur:read', 'statut:read'];

    #[Route('', methods: ['GET'])]
    public function index(CommandeRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($repo->findAll(), 'json', ['groups' => self::GROUPS]);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Commande $commande, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($commande, 'json', ['groups' => self::GROUPS]);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
        ValidatorInterface $validator, ClientRepository $clientRepo,
        LivreurRepository $livreurRepo, StatutRepository $statutRepo,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $client = $clientRepo->find($data['clientId'] ?? 0);
        if (!$client) {
            return new JsonResponse(['error' => 'Client introuvable'], 400);
        }

        $commande = new Commande();
        $commande->setAdresseLivraison($data['adresseLivraison'] ?? '');
        $commande->setMontant($data['montant'] ?? null);
        $commande->setClient($client);
        $commande->setReference('CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)));
        $commande->setDateCreation(new \DateTimeImmutable());

        if (!empty($data['livreurId'])) {
            $commande->setLivreur($livreurRepo->find($data['livreurId']));
        }

        $statutInitial = $statutRepo->findOneBy(['ordre' => 1]); // "En attente"
        $commande->setStatutActuel($statutInitial);

        $errors = $validator->validate($commande);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $em->persist($commande);

        $suivi = new SuiviLivraison();
        $suivi->setCommande($commande);
        $suivi->setStatut($statutInitial);
        $suivi->setDateChangement(new \DateTimeImmutable());
        $em->persist($suivi);

        $em->flush();

        $json = $serializer->serialize($commande, 'json', ['groups' => self::GROUPS]);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Commande $commande, Request $request, EntityManagerInterface $em,
        SerializerInterface $serializer, LivreurRepository $livreurRepo,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (array_key_exists('adresseLivraison', $data)) $commande->setAdresseLivraison($data['adresseLivraison']);
        if (array_key_exists('montant', $data)) $commande->setMontant($data['montant']);
        if (array_key_exists('livreurId', $data)) {
            $commande->setLivreur($data['livreurId'] ? $livreurRepo->find($data['livreurId']) : null);
        }
        // volontairement pas de statutActuel ici → ça passe par POST /api/commandes/{id}/suivi

        $em->flush();
        $json = $serializer->serialize($commande, 'json', ['groups' => self::GROUPS]);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Commande $commande, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($commande);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    #[Route('/{id}/suivi', methods: ['GET'])]
    public function suiviIndex(Commande $commande, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($commande->getSuiviLivraisons(), 'json', ['groups' => ['suivi:read', 'statut:read']]);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}/suivi', methods: ['POST'])]
    public function suiviCreate(
        Commande $commande, Request $request, EntityManagerInterface $em,
        SerializerInterface $serializer, StatutRepository $statutRepo,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $statut = $statutRepo->find($data['statutId'] ?? 0);
        if (!$statut) {
            return new JsonResponse(['error' => 'Statut introuvable'], 400);
        }

        $suivi = new SuiviLivraison();
        $suivi->setCommande($commande);
        $suivi->setStatut($statut);
        $suivi->setCommentaire($data['commentaire'] ?? null);
        $suivi->setDateChangement(new \DateTimeImmutable());
        $em->persist($suivi);

        $commande->setStatutActuel($statut); // on resynchronise le statut courant

        $em->flush();
        $json = $serializer->serialize($suivi, 'json', ['groups' => ['suivi:read', 'statut:read']]);
        return new JsonResponse($json, 201, [], true);
    }
}