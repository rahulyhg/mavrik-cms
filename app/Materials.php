<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    protected $fillable = ['name', 'type', 'path', 'status'];
    
    
    public static function saveMaterial($material)
    {
        $material = new Materials(array(
            'name' => $material['name'],
            'type' => $material['type'],
            'path' => $material['path'],
            'status' => 'inactive'
        ));

        $material->save();

        return $material;

    }
}
