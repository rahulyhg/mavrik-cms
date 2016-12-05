<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $table = 'contents';
    protected $fillable = ['name', 'user_id', 'type', 'text', 'release_date', 'status'];


    public static function createContent($data){
        $content = new Content(array(
            'name' => $data['name'],
            'text' => $data['text'],
            'type' => $data['type'],
            'release_date' => $data['release_date'],
            'status' => 'disabled'
        ));

        $content->user()->associate(User::find($data['id']));

        $content->save();

        return $newContent= Content::where('id', $content->id)
            ->get();
    }
    public static function showContentType($id, $type){
        return Content::where('user_id', $id)
            ->where('type', $type)
            ->get();
    }

    public static function updateContent($update, $id){
        $content = Content::find($id);

        $content->fill($update);

        $content->save();

        return $content;
    }

    public static function deleteContent($id){
        $material = Content::find($id);

        return $material->delete();
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
