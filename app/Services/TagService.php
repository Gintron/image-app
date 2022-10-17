<?php

namespace App\Services;

use App\Repositories\TagRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository){
        $this->tagRepository = $tagRepository;
    }

    public function saveTagData($data){
        $validator = Validator::make($data,[
            'name' => 'required'
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->tagRepository->save($data);

        return $result;
    }

    public function getAllTags(){
        return $this->tagRepository->getAllTags();
    }

    public function getById($id){
        return $this->tagRepository->getById($id);
    }

    public function updateTag($data, $id){
        $validator = Validator::make($data,[
            'name' => 'required'
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try{
            $tag = $this->tagRepository->update($data, $id);
        }
        catch(Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            throw new InvalidArgumentException('Unable to update tag');
        }

        DB::commit();

        return $tag;

    }

    public function deleteById($id){
        DB::beginTransaction();

        try {
            $tag = $this->tagRepository->delete($id);
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete tag');
        }

        DB::commit();
        
        return $tag;
    }
}