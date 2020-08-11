<?php

namespace App\Objects;

use App\Objects\LightMaterial;

// object that i use as an interface between db'entries and dev utilisation of it

class Material extends LightMaterial {

    public $id_chantier;
    public $id_zone_stockage;
    public $lost;
    public $price;
    public $observation;
    public $serie;


    function __construct($rawMaterial, $typeName) {
        parent::__construct($rawMaterial, $typeName);

        $this->id_chantier = $rawMaterial->id_chantier;
        $this->id_zone_stockage = $rawMaterial->id_zone_stockage;
        $this->lost = $rawMaterial->lost;
        $this->price = $rawMaterial->price;
        $this->observation = $rawMaterial->observation;
        $this->serie = $rawMaterial->serial_number;
    }
}

?>