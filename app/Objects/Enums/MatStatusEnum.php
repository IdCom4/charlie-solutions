<?php

namespace App\Objects\Enums;

// enum to always have common const values for a category in a robust way

abstract class MatStatusEnum {

    const _OK = "EN SERVICE";
    const _HS = "HS";
    const _HS_R_W = "HS/RETOUR ATELIER";
    const _WORKSHOP = "ATELIER";
    const _TO_CTRL = "A CONTROLER";
    const _LOST = "PERDU";
    const _STOLEN = "VOLE";
    const _TO_FIX = "A REPARER";
    const _DUPLICATE = "DOUBLON";
    const _SOLD = "VENDU";
    const _MISS_1_P = "MANQUE 1 PARTIE";
    const _MISS = "MANQUE";
    const _MISSING = "MANQUANT";

    const _AS_TAB = [
        MatStatusEnum::_OK,
        MatStatusEnum::_HS,
        MatStatusEnum::_LOST,
        MatStatusEnum::_STOLEN,
        MatStatusEnum::_HS_R_W,
        MatStatusEnum::_TO_FIX,
        MatStatusEnum::_DUPLICATE,
        MatStatusEnum::_SOLD,
        MatStatusEnum::_MISS_1_P,
        MatStatusEnum::_MISS,
        MatStatusEnum::_MISSING,
        MatStatusEnum::_WORKSHOP,
        MatStatusEnum::_TO_CTRL
    ];
}

?>