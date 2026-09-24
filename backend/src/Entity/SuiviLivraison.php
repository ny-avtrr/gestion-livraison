<?php

namespace App\Entity;

use App\Repository\SuiviLivraisonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SuiviLivraisonRepository::class)]
class SuiviLivraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateChangement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['suivi:read'])]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'suiviLivraisons')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['suivi:read', 'commande:read'])]
    private ?commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'suiviLivraisons')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['suivi:read', 'statut:read'])]
    private ?statut $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateChangement(): ?\DateTimeImmutable
    {
        return $this->dateChangement;
    }

    public function setDateChangement(\DateTimeImmutable $dateChangement): static
    {
        $this->dateChangement = $dateChangement;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCommande(): ?commande
    {
        return $this->commande;
    }

    public function setCommande(?commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getStatut(): ?statut
    {
        return $this->statut;
    }

    public function setStatut(?statut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
