<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenteProduit extends Model
{
    protected $fillable = ['vente_id', 'produit_id', 'quantite', 'prix_vente'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
}
