<?php
/**
 * Created by PhpStorm.
 * User: rickimoore
 * Date: 10/14/16
 * Time: 1:14 PM
 */

namespace App;
use Illuminate\Http\Request;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Carbon\Carbon;


class MoveMaterial
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkMaterial()
    {
        $type = $this->request->get('type');


        $material = $this->request->all();

        $exists = [];
        //check for any pre-existing file names in the directory.
        for($x = 0; $x < count($material); $x++){

            if($type == 'image'){
                $toVerify = $material['image-'. $x]->getClientOriginalName();
                $isExist = file_exists (public_path().'/'. $type . '/originals/' . $toVerify);
            } else{
                $toVerify = $material['video']->getClientOriginalName();
                $isExist = file_exists (public_path().'/'. $type .'/'. $toVerify);
            }

            $isExist = file_exists (public_path().'/'. $type . '/originals/' . $toVerify);

            if($isExist){
                array_push($exists, $toVerify);
            }
        }
        //if duplicate file names are found return them.
        if(count($exists) > 0){
            return $exists;
        }

        return 'gg';
    }

    public function moveMaterial()
    {
        $check = $this->checkMaterial();

        if($check == 'gg'){

            Flysystem::put('hi.txt', 'foo');
            dd($this->request->all());
        }
//        $projectName = $this->fetchProjectName($id);
//        $fileName = $this->sizeMoveFile($material, $projectName);
//        $originalFilename = $material->getClientOriginalName();
//        $data = [
//            'name' => $fileName,
//            'alias' => $originalFilename,
//            'type' => 'image'
//        ];
//        return Material::createMaterial($data, $id);
    }

}