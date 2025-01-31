<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Niveis;

class Desenvolvedor extends Model
{
    use HasFactory;
    
    protected $table = 'desenvolvedores';

    protected $fillable = [
        'nivel_id',
        'nome',
        'sexo',
        'data_nascimento',
        'hobby',
    ];

    public function nivel()
    {
        return $this->belongsTo(Niveis::class, 'nivel_id');
    }
    
}
