<?php

namespace App\Objects;

// object that i use as an interface between db'entries and dev utilisation of it

class LightMaterial {

    public $id;
    public $identification;
    public $type;
    public $region;
    public $site;
    public $status;

    function __construct($rawMaterial, $typeName) {
        $this->id = $rawMaterial->id;
        $this->identification = $rawMaterial->identification;
        $this->type = $typeName;
        $this->region = $rawMaterial->region;
        $this->site = $rawMaterial->site;
        $this->status = $rawMaterial->status;
    }
}

?>