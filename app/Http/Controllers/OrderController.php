<?php

namespace App\Http\Controllers;

use App\Http\Resources\Cart\CartCollectionResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        /** @var User $user */
        $user = $request->get('keyable');

        $currentCart = $user->cart->keyBy('product_id')->where('deleted_at', null);

        $cart = (new CartCollectionResource($currentCart))->toArray($request);

        DB::beginTransaction();

        try {
            /** @var Order $order */
            $order = $user->orders()->create([
                'status' => Order::ORDER_RECEIVED,
                'tax' => $cart['tax'] ?? 0,
                'sub_total' => $cart['subTotal'] ?? 0,
                'shipping' => $cart['shipping'] ?? 0,
                'total' => $cart['grandTotal'] ?? 0
            ]);

            $currentCart->each(function($item, $key) use ($order){
                if($item instanceof Cart) {
                    $order->products()->attach($key, [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);
                    $item->delete();
                }
            });

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ha ocurrido un error al realizar el pedido',
                'code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        return new OrderResource($order);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getOrders(): AnonymousResourceCollection
    {
        $orders = Order::query()->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * @param Order $order
     * @return OrderResource
     */
    public function getOrder(Order $order): OrderResource
    {
        return new OrderResource($order);
    }
}
