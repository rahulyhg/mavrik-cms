<?php
/**
 * Created by PhpStorm.
 * User: rickimoore
 * Date: 10/14/16
 * Time: 1:14 PM
 */

namespace App;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class MoveMaterial
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkMaterial($material, $type)
    {

        //check for any pre-existing file names in the directory.

            if($type == 'image'){
                $toVerify = $material->getClientOriginalName();
                $isExist = file_exists (public_path().'/'. $type . '/originals/' . $toVerify);
            } else{
                $toVerify = $material->getClientOriginalName();
                $isExist = file_exists (storage_path().'/app/public/'. $type .'/'. $toVerify);

//                $exists = Storage::disk('s3')->exists($type . 'file.jpg');
            }

            if($isExist){
                return $toVerify;
            }

        return 'gg';
    }

    public function storeMaterial($material, $type){

            Storage::disk('s3')->put($type . '/' . $material->getClientOriginalName(), file_get_contents($material));
        
            return $this;
    }

    public function createMaterialObject(){
        $material = $this->request->all();

        $type = $material['type'];

        if($type == 'image'){
            $name = $material['image']->getClientOriginalName();
        } else{
            $name = $material['video']->getClientOriginalName();
        }

        $material = [
            'name' => $name,
            'type' => $material['type'],
            'path' => 'storage/'. $type .'/'. $name
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

        if($type == 'video'){

            $check = $this->checkMaterial($material['video'], $type);

            if( $check != 'gg'){
                return 'there was an error';
            }

                return $this->storeMaterial($material['video'], $type)
                    ->saveMaterial();



        } else {
            for($x = 0; $x < count($material); $x++){

            }
        }

    }

}