<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemToCartRequest;
use App\Http\Requests\RemoveCartItemRequest;
use App\Http\Resources\Cart\CartCollectionResource;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart(Request $request): CartCollectionResource
    {
        /** @var User $user */
        $user = $request->get('keyable');

        $currentCart = $user->cart->keyBy('product_id')->where('deleted_at', null);

        return new CartCollectionResource($currentCart);
    }

    public function addItem(AddItemToCartRequest $request): CartCollectionResource
    {
        /** @var User $user */
        $user = $request->get('keyable');

        /** @var integer $productId */
        $productId = $request->get('productId');

        $currentCart = $user->cart->keyBy('product_id')->where('deleted_at', null);

        if($currentCart->has($productId)) {
            /** @var Cart $item */
            $item = $currentCart->get($productId);
            $item->quantity = $request->get('quantity');
            $item->save();
            $currentCart->forget($productId);
            $currentCart->push($item);
        } else {
            $item = $user->cart()->create([
                'product_id' => $productId,
                'quantity' => $request->get('quantity')
            ]);
            $currentCart->push($item);
        }

        return new CartCollectionResource($currentCart);
    }

    public function removeItem(RemoveCartItemRequest $request): CartCollectionResource
    {
        /** @var User $user */
        $user = $request->get('keyable');

        $currentCart = $user->cart->keyBy('product_id')->where('deleted_at', null);

        if($currentCart->has($request->get('productId'))) {
            $item = $currentCart->get($request->get('productId'));
            if($item instanceof Cart) {
                $item->delete();
                $currentCart->forget($request->get('productId'));
            }
        }

        return new CartCollectionResource($currentCart);
    }

    public function flushCart(Request $request)
    {
        /** @var User $user */
        $user = $request->get('keyable');

        $currentCartItems = $user->cart->keyBy('product_id')->where('deleted_at', null);

        $currentCartItems->each(function ($item, $key) {
            if($item instanceof Cart) {
                $item->delete();
            }
        });

        return new CartCollectionResource(collect());
    }
}
