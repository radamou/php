<?php

namespace App\Content\Battle;

use App\Content\Collection\ShipCollection;
use App\Content\Entity\AbstractShip;

class LoggableShipLoader implements ShipLoaderInterface
{
    public function __construct(
        private ShipLoaderInterface $shipLoader
    ){}

    public function fetchAll(): ShipCollection
    {
        return $this->shipLoader->fetchAll();
    }

    public function fetchOne(int $shipId): AbstractShip
    {
        return $this->shipLoader->fetchOne($shipId);
    }
}
