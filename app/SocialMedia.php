<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $fillable = ['name', 'image', 'path', 'status', 'position'];

    public static function get_icons(){
        return SocialMedia::all();
    }

    public static function findIcon($id){
        return SocialMedia::findOrFail($id);
    }
    
    public static function save_icon($icon, $data){
        $newIcon = new SocialMedia(array(
            'name' => $data['name'],
            'image' => $icon,
            'path' => $data['path'],
            'status' => 'inactive'
        ));

        $newIcon->save();

        return $newIcon;
    }

    public static function updateIcon($update, $id){
        $icon = SocialMedia::find($id);

        $icon->fill($update);

        $icon->save();

        return $icon;
    }

    public static function delete_icon($id){
        $icon = SocialMedia::find($id);

        return $icon->delete();
    }
}
