<?php
/**
 * Created by PhpStorm.
 * User: rickimoore
 * Date: 10/14/16
 * Time: 1:14 PM
 */

namespace App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;


class MoveMaterial
{
    protected $request;
    protected $env;

    public function __construct(Request $request)
    {
        if(App::isLocal()){
            $this->env = 'local';
        } else{
            $this->env = 's3';
        }
        $this->request = $request;
    }


    public function checkMaterial($material, $type){

            if($type == 'image'){
                $isExist = file_exists (storage_path().'/'. $type . '/originals/' . $material);
            } else{
                $isExist = Storage::disk($this->env)->exists('/video/' . $material);
            }

            if($isExist){
                return $material;
            }

        return false;
    }

    public function storeMaterial($material, $type){

                Storage::disk($this->env)->put($type . '/' . $material->getClientOriginalName(), file_get_contents($material));
        
            return $this;
    }

    public function createMaterialObject(){
        $material = $this->request->all();

        $type = $material['type'];

        if($type == 'image'){
            $name = $material['image']->getClientOriginalName();
            $folder = 'image';
        } else{
            $name = $material['video']->getClientOriginalName();
            $folder = 'video';
        }

        $path = 'https://fabiana.objects.frb.io/' . $folder . '/' . $name;

        if(App::isLocal()){
            $path= '/Users/rickimoore/Laravel/FABIANA/storage/app/'. $folder .'/'. $name;
        }

        $material = [
            'name' => $name,
            'type' => $material['type'],
            'path' => $path
        ];

        return $material;
    }

    public function saveMaterial(){

        return Materials::saveMaterial($this->createMaterialObject());
    }

    public function moveMaterial()
    {
        $type = $this->request->get('type');

        $material = $this->request->all();

        if($type != 'image'){

            $video = $material['video'];

            $check = $this->checkMaterial($video->getClientOriginalName(), $type);

            if(!$check){
                return $this->storeMaterial($video, 'video')
                    ->saveMaterial();
            }

            return 'there was an error';




        } else {
            for($x = 0; $x < count($material); $x++){

            }
        }

    }

    public function deleteMaterial($id){
        $material = Materials::showMaterial($id);
        $check = $this->checkMaterial($material['name'], $material['type']);
        if($check){
            Storage::disk($this->env)->delete('/video/' . $material['name']);
            return Materials::deleteMaterial($id);
        }
        return 'poop';
    }

}