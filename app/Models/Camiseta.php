<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camiseta extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'club',
        'pais',
        'tipo',
        'color',
        'precio',
        'precio_oferta',
        'cantidad',
        'detalles',
        'sku'
    ];

    public function tallas()
    {
        return $this->belongsToMany(Talla::class);
    }

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class);
    }
}
