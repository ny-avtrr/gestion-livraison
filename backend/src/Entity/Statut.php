<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\Column(length: 7)]
    private ?string $couleur = null;

    /**
     * @var Collection<int, SuiviLivraison>
     */
    #[ORM\OneToMany(targetEntity: SuiviLivraison::class, mappedBy: 'statut')]
    private Collection $suiviLivraisons;

    public function __construct()
    {
        $this->suiviLivraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

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
            $suiviLivraison->setStatut($this);
        }

        return $this;
    }

    public function removeSuiviLivraison(SuiviLivraison $suiviLivraison): static
    {
        if ($this->suiviLivraisons->removeElement($suiviLivraison)) {
            // set the owning side to null (unless already changed)
            if ($suiviLivraison->getStatut() === $this) {
                $suiviLivraison->setStatut(null);
            }
        }

        return $this;
    }
}
