<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    protected $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function save($data){
        $tag = new $this->tag;

        $tag->name = $data['name'];

        $tag->save();

        return $tag->fresh();
    }

    public function getAllTags(){
        return $this->tag->get();
    }

    public function getById($id){
        return $this->tag->where('id', $id)->get();
    }

    public function update($data, $id){
        $tag = $this->tag->find($id);

        $tag->name = $data['name'];

        $tag->update();

        return $tag;
    }

    public function delete($id){
        
        $tag = $this->tag->find($id);
        $tag->delete();
        
        return $tag;
    }
}