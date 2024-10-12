<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateComplainceRequest;
use App\Http\Resources\Complaince\ComplainceResource;
use App\Models\Complaince;
// use Illuminate\Http\Request;

class ComplainceController extends Controller
{
    // public function index()
    // {
    //     $complaince = Complaince::paginate();

    //     return ComplainceResource::collection($complaince);
    // }
    
    public function store(StoreUpdateComplainceRequest $request)
    {
        set_time_limit(60); 

        $complaince = Complaince::create($request->validated());

        return new ComplainceResource($complaince);
    } 

}
