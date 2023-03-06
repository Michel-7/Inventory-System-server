<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Http\Resources\ProductType as ProductTypeResource;
use App\Models\Item;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTypes = ProductType::where('user_id', Auth::user()->id)->get();
        foreach ($productTypes as $productType) {
            $count = Item::where([
                ['products_type_id', $productType->id],
                ['sold', '!=', '1']
            ])->count();
            $productType['count'] = $count;
        }
        return new ProductTypeResource($productTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //create a new productType record
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'description' => 'required|string|between:2,500',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $productType = new ProductType();
        $productType->name = $request->input('name');
        $productType->description = $request->input('description');
        $image = $request->file('image');
        $stored_image = $image->store('public');
        $productType->image = asset('storage/' . pathinfo($stored_image)['basename']);
        $productType->user_id = Auth::user()->id;

        $productType->save();
        return new ProductTypeResource($productType);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'description' => 'required|string|between:2,500',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $productType = ProductType::findOrFail($id);
        $productType->name = $request->input('name');
        $productType->description = $request->input('description');
        $image = $request->file('image');
        $stored_image = $image->store('public');
        $productType->image = asset('storage/' . pathinfo($stored_image)['basename']);

        $productType->save();
        return new ProductTypeResource($productType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productType = ProductType::findOrFail($id);
        if ($productType->delete()) {
            return new ProductTypeResource($productType);
        }
    }
}