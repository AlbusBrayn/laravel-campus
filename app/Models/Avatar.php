<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skin',
        'clothes',
        'mouth',
        'eyes',
        'eye_brow',
        'top',
        'hair_color',
        'accessories',
        'beard',
        'beard_color'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
