<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $fillable = ['client_id', 'montant_total', 'mode_paiement'];

    public function produits()
    {
        return $this->hasMany(VenteProduit::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
