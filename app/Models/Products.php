<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    public function section()
    {
        return  $this->belongsTo( Sections::class,"section_ID");
    }
    use HasFactory;
    protected $fillable = [
        "name",
        "description",
        "section_ID"
    ];

}
