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
use GuzzleHttp\Client;


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

        //trigger exception in a "try" block
        try {
            $clean_storage = explode("/storage",$this->material_object['path']);
                    $isExist = Storage::disk($this->env)->exists($clean_storage[1]);

            if($isExist){
                return $isExist;
            }
        }

        //catch exception
        catch(\Exception $e) {
            return false;
        }
        return false;
    }

    public function storeMaterial($material){

        $gallery = $this->material_object;

        if($gallery['gallery_id'] != 'null'){

            $path = Gallery::find($gallery['gallery_id'])['name'];

            Storage::disk($this->env)->put($path . '/' . $material->getClientOriginalName(), file_get_contents($material));
        } else {
            if($this->env == 'local'){
                $image_path = explode("/storage",$gallery['path'])[1];
            } else {
                $image_path = explode("https://fabiana.objects.frb.io",$gallery['path'])[1];
            }
            Storage::disk($this->env)->put($image_path, file_get_contents($material));
        }


        return $this;
    }

    public function createMediaPath(){
        $type = $this->request['media'];
        if($type == 'vimeo'){
            // find image and set as the credit
            $res = unserialize(file_get_contents("http://vimeo.com/api/v2/video/186154747.php"));
            $this->request['credit'] = $res[0]['thumbnail_large'];

            // break and reform path

            $videoId = explode('https://vimeo.com/', $this->request['path']);
            $this->request['path'] = 'https://player.vimeo.com/video/' . $videoId[1];

        }
        return $this;
    }

    public function createLinkObject(){
        $material = $this->request->all();
        $material = [
            'name' => $material['name'],
            'type' => 'video',
            'gallery_id' => null,
            'path' => $material['path'],
            'credit' => $material['credit']
        ];

        $this->material_object = $material;

        return $this;
    }

    public function createMaterialObject($material, $image, $type){
        $name = $image->getClientOriginalName();
        $newMaterial = [
            'name' => $material['name'],
            'type' => $type,
            'gallery_id' => $material['gallery_id'],
            'path' => $this->createStoragePath($material, $name),
            'credit' => $material['credit']
        ];

        $this->material_object = $newMaterial;

        return $this;
    }

    public function createStoragePath($material, $fileName){
        
        $gallery = $this->fetchGallery($material);

        if($gallery){
            $folder = $gallery['name'];
        } else {
            $folder = 'gallery';
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
        } elseif($type == 'video-link'){
            return $this->moveVideoLink($material);
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

    public function moveVideoLink(){
        return $this->createMediaPath()
            ->createLinkObject()->saveMaterial();
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
        } else {
            return Materials::deleteMaterial($id);
        }
        return 'poop';
    }
    public function deleteGallery($id){
        $gallery = Gallery::findGallery($id);
        if($this->env == 'local'){
            $path = explode("/storage",$gallery['path'])[1];
        } else {
            $path = $gallery['path'];
        }
        Storage::disk($this->env)->deleteDirectory($path);


        if (strpos($gallery['image'], 'colors.jpg') === false) {
            if($this->env == 'local'){
                $image_path = explode("/storage",$gallery['image'])[1];
            } else {
                $image_path = $gallery['image'];
            }

            Storage::disk($this->env)->delete($image_path);
        }

        return Gallery::deleteGallery($id);
    }

}