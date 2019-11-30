<?php

namespace App\Http\Controllers;

use App\Location;
use App\Order;
use App\Stock;
use Exception as ExceptionClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orders = Order::paginate(15);

        return response()->json([$orders], 200);
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
            'client' => 'required',
            'tp' => 'required|min:1|max:31|integer',
            'creator' => 'required',
            'agent' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'request' => $request->all()], 406);
        }

        $order = Order::create($request->all());

        return response()->json(['order' => $order], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return Response
     */
    public function show(Order $order)
    {
        return response()->json($order, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return Response
     */
    public function populate(Order $order)
    {
        $locations = Location::all();

        return response()->json(['order' => $order, 'locations' => $locations], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToOrder(Request $request)
    {
        $order = Order::find($request->json('order'));
        $data = [];
        foreach ($request->json('products') as $product) {
            $location = Location::find($product['id']);
            $stock = Stock::find($location->product_id);
            $order->product()->attach($location->id, ["from_location" => $product["id"], "from_stock" => $stock->id, "qty" => $product['qty'], "price" => $location->price * $product['qty']]);
            $data[] = 'Added ' . $product['qty'] . ' from ' . $location['id'] . ' to order ' . $order->id;
            $location->qty -= $product['qty'];
            $location->save();
        }

        return \response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->all());

        return response()->json(['order', $order], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return Response
     * @throws ExceptionClass
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
        } catch (ExceptionClass $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json([null]);
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function processOrder(Order $order)
    {
        if ($order->status == 'processed') {
            return response()->json(['error' => 'Order is already processed']);
        }

        // dd($order->populatedOK($order));

        if (!$order->populatedOK($order)) {
            return response()->json(['error' => 'Products are not following promotional mechanisms'], 400);
        }

        // $order->status = 'processed';
        // $order->save();
        // return response()->json(['message' => 'Order marked as processed', 'order' => $order]);
    }
}
