<?php

namespace App\Objects\Enums;

// enum to always have common const values for a category in a robust way

abstract class FormFieldsEnum {

    const _PAGE = "page";
    const _OFFSET = "offset";
    const _FILIALE = "filiale";
    const _KEYWORDS = "keywords";
    const _SORT_BY = "sort-by";
    const _ORDER = "order";

    const _DEF_OFFSET = 10;
}

?>