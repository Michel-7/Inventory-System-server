<?php

namespace App\Http\Controllers\api;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Item as ItemResource;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $items = Item::where('products_type_id', $id)->get();
        return new ItemResource($items);
    }

    public function sold($id)
    {
        $item = Item::findOrFail($id);
        if ($item['sold'] == '0') {
            $item['sold'] = '1';
        } else {
            $item['sold'] = 0;
        }
        $item->save();

        return new ItemResource($item);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'serial_number' => 'required|string|unique:items',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $item = new Item();
        $item->name = $request->input('name');
        $item->serial_number = $request->input('serial_number');
        $item->products_type_id = $request->input('products_type_id');

        $item->save();
        return new ItemResource($item);
    }

    public function bulkStore(Request $request)
    {
        $items = $request->input('items');
        foreach ($items as $i) :
            $validator = Validator::make($i, [
                'name' => 'required|string|between:2,100',
                'serial_number' => 'required|string|unique:items',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
        endforeach;

        if (is_array($items)) {
            DB::table('items')->insert($items);
        }
        return new ItemResource(($items));
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
            'serial_number' => 'required|string|unique:items',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $item = Item::findOrFail($id);
        $item->name = $request->input('name');
        $item->serial_number = $request->input('serial_number');

        $item->save();

        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return new ItemResource($item);
    }
}