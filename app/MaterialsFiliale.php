<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialsFiliale extends Model
{
    use Notifiable;

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
