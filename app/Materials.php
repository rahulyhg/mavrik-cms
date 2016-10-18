<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    protected $fillable = ['name', 'type', 'path', 'status'];

    public static function showAll(){
        return Materials::where('status', 'active')
            ->get();
    }
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

    public static function showMaterialGroup($id){
        return Materials::where('type', $id)
            ->get();
    }

    public static function showMaterial($id){
        return Materials::where('id', $id)
            ->first();
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
        $user = Materials::find($id);

        return $user->delete();
    }
}
