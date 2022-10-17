<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository
{

    protected $image, $history;

    public function __construct(Image $image, HystoryRepository $history){
        $this->image = $image;
        $this->history = $history;
    }

    public function getAllImages($data){
        
        if($data && count($data) && array_key_exists('filters', $data)){
            foreach($data['filters'] as $filter){
                if($filter['key'] === 'tag'){
                    $selectedTag['value'] = $filter['value'];
                    $selectedTag['operator'] = $filter['operator'];
                }      
            }
        }
       
        if(!count($data) || !array_key_exists('sort', $data)) $data['sort'] = 'desc';

        if(!count($data) || !array_key_exists('limit', $data)) $data['limit'] = 2;

        $images = $this->image::with('tags')
        ->when($selectedTag, function($query, $selectedTag){
            if($selectedTag['operator'] === '='){ 
                $query->whereHas('tags', function($query) use($selectedTag) {
                    $query->where('tags.name', '=', [$selectedTag['value']]);
                });
            }else{
                $query->whereDoesntHave('tags', function($query) use($selectedTag) {
                    $query->where('tags.name', '=', [$selectedTag['value']]);
                });
            }
        })
        ->orderBy('images.created_at', $data['sort'])->paginate($data['limit']);

        return $images;
    }

    public function save($data){
        $image = new $this->image;

        $image->name = $data['name'];
        $image->description = $data['description'];
        $image->author = $data['author'];
        $image->imageUrl = $data['imageUrl'];
        $image->width = $data['width'];
        $image->height = $data['height'];

        $image->save();

        if(array_key_exists('tags', $data)) $image->tags()->attach($data['tags']);

        return $image->fresh('tags');

    }

    public function getById($id, $data){
        if($data && array_key_exists('date', $data))
            return $this->history->getHistory($data); 
        return $this->image->where('id', $id)->get();
    }

    public function update($data, $id){
        $image = $this->image->find($id);

        $data['data'] = $image;
        $data['entity'] = 'image';
    
        $this->history->createHystory($data);

        $image->name = $data['name'];
        $image->description = $data['description'];
        $image->author = $data['author'];
        $image->imageUrl = $data['imageUrl'];
        $image->width = $data['width'];
        $image->height = $data['height'];

        $image->update();

        return $image;
    }

    public function delete($id){
        
        $image = $this->image->find($id);
        $image->delete();
        
        return $image;
    }
}