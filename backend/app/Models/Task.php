<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'project_id',
    ];

    //Relacion inversa
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
