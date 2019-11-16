<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Stock;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Location;

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
            'lot' => 'required|unique:stocks' // a product cannot exist multiple times with the same lot on the same stock
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'request' => $request->all()], 406);
        }

        $stock = Stock::create($request->all());
        // create movement
        $movement = $this->createMovement($request, $stock);
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
        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        return response()->json(['stock' => $stock], 200);
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

    /**
     * @param Request $request
     * @param $stock
     * @return Movement
     */
    private function createMovement(Request $request, $stock): Movement
    {
        $movement = new Movement;
        $movement->stock_id = $stock->id;
        $movement->product_id = $request->get('product_id');
        $movement->moved_to = 'moved to ' . $stock->name;
        $movement->save();
        return $movement;
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function addStockOnLocation(Request $request)
    {
        $stock = Stock::findOrFail($request->get('stock_id'));
        if (!$stock) {
            return response()->json(['error' => 'Stock not found']);
        }
        if ($stock->qty <= 0) {
            return response()->json(['error' => 'QTY was zero for the selected stock. Please choose another']);
        }
        $qty = $request->get('qty');

        $location = Location::find($request->get('location_id'));
        $location->createStockOnLocation($location, $stock, $qty);

        return response(['stock' => $stock, 'location' => $location, 'qty' => $qty], 201);
    }
}
