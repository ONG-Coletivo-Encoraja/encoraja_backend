<?php

namespace App\Http\Resources\Complaince;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplainceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'name'=>strtoupper($this->name), 
            'email'=>$this->email, 
            'phone_number'=>$this->phone_number, 
            'description'=>$this->description,
            'relation'=>$this->relation,
            'motivation'=>$this->motivation,
        ];
    }
}
