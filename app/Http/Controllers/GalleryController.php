<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\MoveMaterial;
use Illuminate\Http\Request;

use App\Http\Requests;

class GalleryController extends Controller
{

    protected $material;

    public function __construct(MoveMaterial $material) {

        $this->material = $material;

        $this->middleware('auth', [
            'except' => [
                'index',
            ],
        ]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Gallery::fetchGalleries();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $path = $this->material->createStoragePath('', '', $request->get('name'));

        if ($request->hasFile('image-0')) {
             $imageObject = $this->material->moveMaterial();

            if($imageObject){
                return Gallery::createGallery($imageObject, $path);
            }
        }
        return Gallery::createGallery($request->all(), $path);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->material->deleteGallery($id)){
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
