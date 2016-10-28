<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';
    protected $fillable = ['name', 'status', 'path', 'image'];

    public static function fetchGalleries(){
        return Gallery::with('materials')
            ->get();
    }

    public static function findGallery($id){
        return Gallery::findOrFail($id);
    }
    public static function createGallery($object, $path){
        
        if($object['path']){
            $imagePath = $object['path'];
        } else {
            $imagePath = null;
        }


        $newGallery = new Gallery(array(
            'name' => $object['name'],
            'image' => $imagePath,
            'path' => $path,
            'status' => 'inactive'
        ));

        $newGallery->save();

        return $newGallery;
    }

    public function materials()
    {
        return $this->hasMany('App\Materials');
    }
}
