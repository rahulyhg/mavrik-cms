<?php

namespace App\Http\Controllers;

use App\Materials;
use App\MoveMaterial;
use Illuminate\Http\Request;

use App\Http\Requests;

class MaterialController extends Controller
{
    protected $material;

    public function __construct(MoveMaterial $material) {

        $this->material = $material;

        $this->middleware('auth', [
            'except' => [
                'index', 'show'
            ],
        ]);

    }

    public function index()
    {
        return Materials::showAll();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->material->moveMaterial();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id == 'video' || $id == 'image' || $id == 'reel' || $id == 'active'){
            return Materials::showMaterialGroup($id);
        }
        
        return Materials::showMaterial($id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateReel(Request $request, $id)
    {
        return Materials::updateActiveMaterial($request->all(), $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Materials::updateMaterial($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->material->deleteMaterial($id)){
            return response([
                'status' => 'success',
                'material_id' => $id
            ], 200);
        }

        return response([
            'status' => 'failure'
        ]);
    }
}
