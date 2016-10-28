<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    protected $fillable = ['name', 'type', 'path', 'status', 'credit'];

    public static function showAll(){
        return Materials::where('status', 'active')
            ->get();
    }
    public static function saveMaterial($material)
    {
        $newMaterial = new Materials(array(
            'name' => $material['name'],
            'type' => $material['type'],
            'path' => $material['path'],
            'credit' => $material['credit'],
            'status' => 'inactive'
        ));

        $newMaterial->gallery()->associate(Gallery::find($material['gallery_id']));

        $newMaterial->save();

        return $newMaterial;

    }

    public static function showMaterialGroup($id){
        return Materials::where('type', $id)
            ->get();
    }

    public static function showMaterial($id){
        return Materials::where('id', $id)
            ->first();
    }

    public static function updateMaterial($update, $id){
        $material = Materials::find($id);

        $material->fill($update);

        $material->save();

        return $material;
    }

    public static function updateActiveMaterial($data, $id){
        $lastActive = Materials::where('status', 'active')
            ->where('id', '!=', $id)
            ->get();

        $downGrades = [];

        if(!$lastActive->isEmpty()){
            for($i = 0; $i < count($lastActive); $i++){
                $downGrade = $lastActive[$i];
                $downGrade->status = 'inactive';
                $downGrade->save();
                array_push($downGrades, $downGrade);
            }
        }


        $material = Materials::find($id);

        $material->fill($data);
        $material->save();

        return [
            'new_active' => $material,
            'deactivated' => $downGrades
        ];
    }

    public static function deleteMaterial($id){
        $material = Materials::find($id);
        
        return $material->delete();
    }
    
    public function gallery(){
        return $this->belongsTo('App\Gallery');
    }
}
