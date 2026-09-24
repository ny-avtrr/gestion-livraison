<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['commande:read'])]
    private ?string $reference = null;

    #[ORM\Column]
    #[Groups(['commande:read'])]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['commande:read'])]
    private ?string $adresseLivraison = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['commande:read'])]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commande:read'])]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['commande:read'])]
    private ?Livreur $livreur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Groups(['commande:read'])]
    private ?Statut $statutActuel = null;

    /**
     * @var Collection<int, SuiviLivraison>
     */
    #[ORM\OneToMany(targetEntity: SuiviLivraison::class, mappedBy: 'commande')]
    private Collection $suiviLivraisons;

    public function __construct()
    {
        $this->suiviLivraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(string $adresseLivraison): static
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getLivreur(): ?livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?livreur $livreur): static
    {
        $this->livreur = $livreur;

        return $this;
    }

    public function getStatutActuel(): ?statut
    {
        return $this->statutActuel;
    }

    public function setStatutActuel(?statut $statutActuel): static
    {
        $this->statutActuel = $statutActuel;

        return $this;
    }

    /**
     * @return Collection<int, SuiviLivraison>
     */
    public function getSuiviLivraisons(): Collection
    {
        return $this->suiviLivraisons;
    }

    public function addSuiviLivraison(SuiviLivraison $suiviLivraison): static
    {
        if (!$this->suiviLivraisons->contains($suiviLivraison)) {
            $this->suiviLivraisons->add($suiviLivraison);
            $suiviLivraison->setCommande($this);
        }

        return $this;
    }

    public function removeSuiviLivraison(SuiviLivraison $suiviLivraison): static
    {
        if ($this->suiviLivraisons->removeElement($suiviLivraison)) {
            // set the owning side to null (unless already changed)
            if ($suiviLivraison->getCommande() === $this) {
                $suiviLivraison->setCommande(null);
            }
        }

        return $this;
    }
}
