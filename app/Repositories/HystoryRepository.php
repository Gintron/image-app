<?php

namespace App\Repositories;

use App\Models\Hystory;

class HystoryRepository

{

    protected $history;

    public function __construct(Hystory $history){
        $this->history = $history;
    }

    public function createHystory($data){
        $history = new $this->history;
        
        $history->data = $data['data'];
        $history->entity_updated_at = $data['data']['updated_at'];
        $history->entity = $data['entity'];
        $history->entity_id = $data['data']['id'];
        
        $history->save();
    }

    public function getHistory($data){
        $history =  $this->history::where('created_at', '>=', $data['date'])
        ->where('entity_updated_at', '<=', $data['date'])
        ->where('entity_id', $data['id'])
        ->where('entity', 'image')->first();
        $history->data = json_decode($history->data);
        return $history;
    }
}