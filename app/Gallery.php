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
            $imagePath = '/image/defaults/colors.jpg';
        }


        $gallery = new Gallery(array(
            'name' => $object['name'],
            'image' => $imagePath,
            'path' => $path,
            'status' => 'inactive'
        ));

        $gallery->save();

        return $newGallery = Gallery::where('id', $gallery->id)
            ->with('materials')
            ->get();
    }

    public static function deleteGallery($id){
        $gallery = Gallery::find($id);

        return $gallery->delete();
    }

    public function materials()
    {
        return $this->hasMany('App\Materials');
    }
}
