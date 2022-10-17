<?php

namespace App\Services;

use App\Repositories\ImageRepository;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ImageService
{

    protected $imageRepository;

    public function __construct(ImageRepository $imageRepository){
        $this->imageRepository = $imageRepository;
    }

    public function getAllImages($data){
        return $this->imageRepository->getAllImages($data);
    }

    public function saveImageData($data){
        $validator = Validator::make($data, [
            'name' => 'required',
            'author' => 'required',
            'imageUrl' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric'
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->imageRepository->save($data);

        return $result;
    }

    public function getById($id, $data){
        if($data && array_key_exists('date', $data)){
            if(is_int($data['date'])){
                $date = new DateTime();
                $data['date'] = $date->setTimestamp($data['date']);
                $data['date'] = $date->format('Y-m-d H:m:s');
            }else{
                $date = new DateTime($data['date']);
                $data['date'] = $date->format('Y-m-d H:m:s');
            }
            $data['id'] = $id;   
        }
       
        return $this->imageRepository->getById($id, $data);
    }

    public function updateImage($data, $id){
        $validator = Validator::make($data, [
            'name' => 'required',
            'author' => 'required',
            'imageUrl' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric'
        ]);


        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try{
            $image = $this->imageRepository->update($data, $id);
        }
        catch(Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            throw new InvalidArgumentException('Unable to update image');
        }

        DB::commit();

        return $image;

    }

    public function deleteById($id){
        DB::beginTransaction();

        try {
            $image = $this->imageRepository->delete($id);
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete image');
        }

        DB::commit();
        
        return $image;
    }
}