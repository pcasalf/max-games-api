<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AddProductRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Get all products
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function all(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()->paginate(10);

        return ProductResource::collection($products);
    }

    /**
     * Get one product
     *
     * @param Product $product
     * @return ProductResource|JsonResponse
     */
    public function getProduct(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Create Product
     *
     * @param AddProductRequest $request
     * @return ProductResource|JsonResponse
     */
     public function createProduct(AddProductRequest $request)
     {
        DB::beginTransaction();

        try {
             $product = new Product();
             $product->name = $request->get('name');
             $product->cover = $request->get('cover');
             $product->description = $request->get('description');
             $product->price = $request->get('price');
             $product->featured = $request->get('featured') ?? false;
             $product->online = $request->get('online') ?? true;

             $product->save();

             DB::commit();

             return new ProductResource($product);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                 'error' => 'Ha ocurrido un error al crear el producto',
                 'code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
     }

    /**
     * @param Product $product
     * @param EditProductRequest $request
     * @return ProductResource|JsonResponse
     */
     public function editProduct(Product $product, EditProductRequest $request)
     {
         DB::beginTransaction();

         try {
             $product->name = $request->get('name');
             $product->cover = $request->get('cover');
             $product->description = $request->get('description');
             $product->price = $request->get('price');
             $product->featured = $request->get('featured') ?? false;
             $product->online = $request->get('online') ?? true;

             $product->save();

             DB::commit();

             return new ProductResource($product);
         } catch (QueryException $e) {
             DB::rollBack();
             return response()->json([
                 'error' => 'Ha ocurrido un error al editar el producto',
                 'code' => Response::HTTP_BAD_REQUEST
             ], Response::HTTP_BAD_REQUEST);
         }
     }
}
