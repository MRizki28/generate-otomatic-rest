<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaModel extends Model
{
    use HasFactory;

    protected $table = 'tb_anggota';
    protected $fillable = [
        'id' ,'template_id', 'uuid' , 'nama' , 'image_kartu_anggota' , 'created_at' , 'updated_at'
    ];


    public function template()
    {
        return $this->belongsTo(TemplateModel::class);
    }

    public function getTemplate($template_id)
    {
        $data = $this->join('tb_template', 'tb_product.template_id', '=', 'tb_template.id')
            ->select('tb_template.uuid', 'tb_template.image_template')
            ->where('tb_product.template_id', '=', $template_id)
            ->first();

        return $data;
    }
}
