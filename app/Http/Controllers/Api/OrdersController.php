<?php

namespace App\Http\Controllers\Api;

use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemsToOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders=Order::with('items')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', '=', $request->status);
            })->paginate(6);

        return OrderResource::collection($orders);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddItemsToOrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create(['status' => 'pending', 'total_amount' => 0]);

        $totalAmount = 0;

        foreach ($validated['items'] as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $totalAmount += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        $order->update(['total_amount' => $totalAmount]);

        return new OrderResource($order->load('items'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $order = Order::find($id);

        if ($order) {
            return new OrderResource(($order->load('items')));
        } else {
            return response()->json(['error' => 'Order not found'], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->delete();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } else {
            return response()->json(['error' => 'Order not found'], 404);
        }

    }

    public function pay(string $id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::where('id', $id)->lockForUpdate()->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            if ($order->status !== OrderStatus::PENDING->value) {
                return response()->json(['error' => 'Order is already paid or canceled'], 400);
            }

            $order->update(['status' => 'paid']);

            return response()->json(['message' => 'Order paid successfully']);
        });
    }

    public function cancel(string $id)
    {
        return DB::transaction(function () use ($id) {

            $order = Order::where('id', $id)->lockForUpdate()->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            if ($order->status !== 'pending') {
                return response()->json(['error' => 'Order is already paid or canceled'], 400);
            }

            $order->update(['status' => 'canceled']);

            return response()->json(['message' => 'Order canceled successfully']);
        });
    }
}
