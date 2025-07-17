<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    // Ajoutez les champs que vous souhaitez pouvoir remplir via l'assignation de masse
    protected $fillable = [
        'nom',          // Ajoutez le champ 'nom'
        'email',
        'poste',
        'telephone',
    ];
}
