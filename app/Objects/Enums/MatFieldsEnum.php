<?php

namespace App\Objects\Enums;

// enum to always have common const values for a category in a robust way

abstract class MatFieldsEnum {

    const _ASCENDING = "asc";
    const _DESCENDING = "desc";
    
    const _DEFAULT = "id";
    const _IDENT = "identification";
    const _TYPE = "id_mat";
    const _REGION = "region";
    const _SITE = "site";
    const _STATUS = "status";
    const _ID_CHANT = "id_chantier";
    const _ID_Z_S = "id_zone_stockage";
    const _LOST = "lost";
    const _PRICE = "price";
    const _OBSERVATION = "observation";
    const _SERIE = "serial_number";

    // iterable fields of the LightMaterial object
    const _LIGHT_AS_TAB = [
        MatFieldsEnum::_DEFAULT,
        MatFieldsEnum::_IDENT,
        MatFieldsEnum::_TYPE,
        MatFieldsEnum::_REGION,
        MatFieldsEnum::_SITE,
        MatFieldsEnum::_STATUS
    ];

    // iterable fields of the Material object
    const _AS_TAB = [
        MatFieldsEnum::_DEFAULT,
        MatFieldsEnum::_IDENT,
        MatFieldsEnum::_TYPE,
        MatFieldsEnum::_REGION,
        MatFieldsEnum::_SITE,
        MatFieldsEnum::_STATUS,
        MatFieldsEnum::_ID_CHANT,
        MatFieldsEnum::_ID_Z_S,
        MatFieldsEnum::_LOST,
        MatFieldsEnum::_PRICE,
        MatFieldsEnum::_OBSERVATION,
        MatFieldsEnum::_SERIE
    ];

    // fillable fields of the db materials entries
    const _FILLABLE_AS_TAB = [
        MatFieldsEnum::_REGION,
        MatFieldsEnum::_SITE,
        MatFieldsEnum::_STATUS,
        MatFieldsEnum::_ID_CHANT,
        MatFieldsEnum::_ID_Z_S,
        MatFieldsEnum::_LOST,
        MatFieldsEnum::_PRICE,
        MatFieldsEnum::_OBSERVATION,
        MatFieldsEnum::_SERIE
    ];


}

?>