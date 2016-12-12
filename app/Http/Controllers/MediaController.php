<?php

namespace App\Http\Controllers;

use App\MoveMaterial;
use App\SocialMedia;
use Illuminate\Http\Request;

use App\Http\Requests;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
        return SocialMedia::get_icons();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $icon = $this->material->storeIcon();
        if($icon){
            return SocialMedia::save_icon($icon, $request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        return SocialMedia::updateIcon($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if($this->material->deleteIconMaterial($id)){
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
