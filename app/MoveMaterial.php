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
    private $material_object;

    public function __construct(Request $request)
    {
        if(App::isLocal()){
            $this->env = 'local';
        } else{
            $this->env = 's3';
        }
        $this->request = $request;
    }


    public function checkMaterial(){

        $clean_storage = explode("/storage",$this->material_object['path']);

        $isExist = Storage::disk($this->env)->exists($clean_storage[1]);

            if($isExist){
                return $isExist;
            }

        return false;
    }

    public function storeMaterial($material){

        $gallery = $this->material_object;

        if($gallery['gallery_id'] != 'null'){

            $path = Gallery::find($gallery['gallery_id'])['name'];

            Storage::disk($this->env)->put($path . '/' . $material->getClientOriginalName(), file_get_contents($material));
        } else {

            $clean_storage = explode("/storage",$this->material_object['path']);

            Storage::disk($this->env)->put($clean_storage[1], file_get_contents($material));
        }


        return $this;
    }

    public function createMaterialObject($material, $image, $type){
        $name = $image->getClientOriginalName();
        $material = [
            'name' => $material['name'],
            'type' => $type,
            'gallery_id' => $material['gallery_id'],
            'path' => $this->createStoragePath($material, $name, $type),
            'credit' => $material['credit']
        ];

        $this->material_object = $material;

        return $this;
    }

    public function createStoragePath($material, $fileName, $type){
        
        $gallery = $this->fetchGallery($material);

        if($gallery){
            $folder = $gallery['name'];
        } else {
            $folder = $type;
        }

        $path = 'https://fabiana.objects.frb.io/' . $folder . '/' . $fileName;

        if(App::isLocal()){
            $path= '/storage/'. $folder .'/'. $fileName;
        }

        return $path;
    }

    public function saveMaterial(){
        return Materials::saveMaterial($this->material_object);
    }

    public function moveMaterial()
    {
        $type = $this->request->get('type');

        $material = $this->request->all();

        if($type == 'video' || $type == 'reel'){
            return $this->moveVideo($material, $type);
        } elseif($type == 'image') {
            return $this->moveImage($material, $type);
        } else {
            return $this->moveGalleryImage($material, 'image');
        }

    }

    public function fetchGallery($material){
        try{

            return Gallery::findGallery($material['gallery_id']);

        }
        catch (\Exception $e){
            return null;
        }

    }

    public function moveGalleryImage($material, $type){
        $image = $material['image-0'];

        $check = $this->createMaterialObject($material,$image, $type)->checkMaterial();

        if(!$check){
            $this->storeMaterial($image);
            return $this->material_object;
        }

        return 'there was an error';
    }

    public function moveVideo($material, $type){
        $video = $material['video'];

        $check = $this->createMaterialObject($material, $video, $type)->checkMaterial();

        if(!$check){
            return $this->storeMaterial($video)
                ->saveMaterial();
        }

        return 'there was an error';
    }

    public function moveImage($material, $type){

            $image = $material['image'];
            $check = $this->createMaterialObject($material, $image, $type)->checkMaterial();

            if($check){
                dd('nope');
            }
        //store material to gallery folder and save it into the model
        return $savedImage = $this->storeMaterial($image)
            ->saveMaterial();
    }

    public function deleteMaterial($id){
        $this->material_object = Materials::showMaterial($id);
        
        $check = $this->checkMaterial();

        if($check){
            $clean_storage = explode("/storage",$this->material_object['path']);
            Storage::disk($this->env)->delete($clean_storage[1]);
            return Materials::deleteMaterial($id);
        }
        return 'poop';
    }

}