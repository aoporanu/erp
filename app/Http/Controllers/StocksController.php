<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $stocks = Stock::paginate(15);

        return response()->json([$stocks, 200]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'qty' => 'required|min:1',
            'product_id' => 'required|exists:products,id',
            'lot' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'request' => $request->all()], 406);
        }

        $stock = Stock::create($request->all());
        // create movement
        $movement = new Movement;
        $movement->stock_id = $stock->id;
        $movement->product_id = $request->get('product_id');
        $movement->moved_to = 'moved to ' . $stock->name;
        $movement->save();
        // return movement
        return response()->json(['stock' => $stock, 'movement' => $movement], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $stock = Stock::findOrFail($id);

        if (!$stock) {
            return response()->json(['error' => true]);
        }
        return response()->json($stock, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        if ($stock) {
            $stock->delete();
        }
        else {
            return response()->json(['error' => 'Stock not found']);
        }

        return response()->json([null]);
    }
}
