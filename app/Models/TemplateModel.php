<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateModel extends Model
{
    use HasFactory;

    protected $table = 'tb_template';
    protected $fillable = [
        'id' , 'uuid' , 'image_template' , 'created_at' , 'updated_at'
    ];
}
