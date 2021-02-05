<?php

namespace App\Content\Battle;

use App\Content\Collection\ShipCollection;
use App\Content\Entity\AbstractShip;
use App\Content\Entity\BountyHunterShip;
use App\Content\Entity\BrokenShip;
use App\Content\Entity\RebelShip;
use App\Content\Entity\Ship;
use App\Internal\Storage\LoaderInterface;

class ShipLoader implements ShipLoaderInterface
{
    public function __construct(
        private LoaderInterface $jsonFixturesLoader
    ){}

    public function fetchAll(): ShipCollection
    {
        $result = $this->jsonFixturesLoader->fetchAllData();
        $ships = [];

        foreach ($result as $ship) {
            $ships[] = $this->createFromData($ship);
        }

        return (new ShipCollection($ships))->removeBrokenShips();
    }

    public function fetchOne(int $shipId): ?AbstractShip
    {
        $ship = $this->jsonFixturesLoader->fetchSingleData($shipId);

        return $this->createFromData($ship);
    }

    public function createFromData(array $data): ?AbstractShip
    {
        if (!isset($data['team'])) {
            return null;
        }

        $ship = match ($data['team']) {
            'rebel' => new RebelShip(),
            'broken' => new BrokenShip(),
            'bounty hunter' => new BountyHunterShip(),
             default => (new Ship())->setJediFactor($data['jedi_factor'])
        };

        return $ship->setId($data['id'])
            ->setName($data['name'])
            ->setWeaponPower($data['weapon_power'])
            ->setStrength($data['strength']);
    }
}
