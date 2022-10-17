<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImageService;
use Exception;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService){
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = request()->only([
            'sort',
            'filters',
            'page',
            'limit'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->imageService->getAllImages($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }
        return response()->json($result, $result['status']);
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
    public function store()
    {
        $data = request()->only([
            'name',
            'description',
            'author',
            'imageUrl',
            'width',
            'height',
            'tags'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->imageService->saveImageData($data);
        } catch (Exception $e) {
            $result = ['status' => 500, 
            'error' => $e->getMessage()];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = request()->only([
            'date'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->imageService->getById($id, $data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
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
        $data = request()->only([
            'name',
            'description',
            'author',
            'imageUrl',
            'width',
            'height',
            'tags'
        ]);

        $result = ['status' => 200];

        try{
            $result['data'] = $this->imageService->updateImage($data, $id);
        }catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->imageService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500, 
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}
