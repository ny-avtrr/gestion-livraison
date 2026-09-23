<?php

namespace App\DataFixtures;
use App\Entity\Statut;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

class StatutFixtures extends Fixture
{
    public const EN_ATTENTE = 'statut-en-attente';
    public const EN_PREPARATION = 'statut-en-preparation';
    public const EN_LIVRAISON = 'statut-en-livraison';
    public const LIVREE = 'statut-livree';
    public const ANNULEE = 'statut-annulee';

    public function load(ObjectManager $manager): void
    {
        $statuts = [
            ['libelle' => 'En attente',     'ordre' => 1, 'couleur' => '#9CA3AF', 'ref' => self::EN_ATTENTE],
            ['libelle' => 'En préparation', 'ordre' => 2, 'couleur' => '#FBBF24', 'ref' => self::EN_PREPARATION],
            ['libelle' => 'En livraison',   'ordre' => 3, 'couleur' => '#3B82F6', 'ref' => self::EN_LIVRAISON],
            ['libelle' => 'Livrée',         'ordre' => 4, 'couleur' => '#10B981', 'ref' => self::LIVREE],
            ['libelle' => 'Annulée',        'ordre' => 5, 'couleur' => '#EF4444', 'ref' => self::ANNULEE],
        ];

        foreach ($statuts as $data) {
            $statut = new Statut();
            $statut->setLibelle($data['libelle']);
            $statut->setOrdre($data['ordre']);
            $statut->setCouleur($data['couleur']);
            $manager->persist($statut);
            $this->addReference($data['ref'], $statut);
        }

        $manager->flush();
    }
}