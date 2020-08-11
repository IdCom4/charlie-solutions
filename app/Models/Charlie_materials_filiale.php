<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charlie_materials_filiale extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_mat',
        'identification',
        'serial_number',
        'region',
        'id_chantier',
        'id_zone_stockage',
        'site',
        'id_site',
        'back_shed',
        'in_control',
        'outControl',
        'inControl_cp',
        'outControl_cp',
        'observation',
        'use_rate',
        'lost',
        'price',
        'status',
        'control_place',
        'id_affiliate',
        'completed',
    ];
}
