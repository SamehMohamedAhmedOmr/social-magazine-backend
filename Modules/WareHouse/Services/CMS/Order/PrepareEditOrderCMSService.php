<?php

namespace Modules\WareHouse\Services\CMS\Order;

use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\WareHouse\Facades\CheckoutErrorsHelper;
use Modules\WareHouse\Repositories\ProductWarehouseRepository;

class PrepareEditOrderCMSService
{
    private $productRepository;
    private $product_warehouse_repository;



    public function __construct(
        ProductRepository $productRepository,
        ProductWarehouseRepository $product_warehouse_repository
    )
    {
        $this->productRepository = $productRepository;
        $this->product_warehouse_repository =  $product_warehouse_repository;
    }

    public function prepareSingleProduct($request)
    {
        $product = $this->productRepository->getOne('id', $request->product_id);
        $product->load('priceLists');

        $price = $this->preparePrice($product->priceLists);

        $toppings = isset($request->toppings) ? $request->toppings : [];

        return[
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'has_toppings' => isset($request->toppings) ? 1 : 0,
            'price' => $price,
            'toppings' => $this->prepareTopping($toppings, $request->quantity)
        ];
    }

    public function prepareProducts($products)
    {
        $product_ids = $this->getProductPayloadIds($products);

        $products_model = $this->productRepository->getBulk('id', $product_ids);

        $products_model->load('priceLists');

        $products_object = collect([]);
        foreach ($products as $product) {
            $target_product = $products_model->where('id', $product->product_id)->first();
            $price = $this->preparePrice($target_product->priceLists);

            $toppings = isset($product->toppings) ? $product->toppings : [];
            $products_object->push([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'has_toppings' => isset($product->toppings) ? 1 : 0,
                'price' => $price,
                'toppings' => $this->prepareTopping($toppings, $product->quantity, $products_model)
            ]);
        }

        return $products_object;
    }

    public function calculateItemsPrice($products)
    {
        $total_price = 0;

        foreach ($products as $product) {
            $item_price = $this->calculateSingleItemPrice($product);

            $total_price += $item_price;
        }

        return $total_price;
    }

    public function calculateSingleItemPrice($new_product)
    {
        $product_price = $this->calculateProductPrice($new_product);

        $toppings_price = $this->calculateProductToppingPrice($new_product);

        return $product_price + $toppings_price;
    }

    public function restoreWarehouseQuantities($items, $warehouse, $type)
    { // 1 add , 0 minus
        $warehouse->load('products');
        $products_id = [];
        $product_warehouse = [];

        foreach ($items as $item) {
            list($single_products_id,
                $single_product_warehouse) = $this->restoreSingleProductQuantity($item, $warehouse, $type, 0, 1);

            $product_warehouse = array_merge($product_warehouse, $single_product_warehouse);

            $products_id = array_merge($products_id, $single_products_id);
        }
        $this->product_warehouse_repository->updateQuantity($warehouse, $products_id, $product_warehouse);
    }

    public function restoreSingleProductQuantity($product, $warehouse, $type, $single_flag = 0, $new_item = 0)
    {
        $products_id = [];
        $product_warehouse = [];


        if ($single_flag) {
            $warehouse->load('products');
        }

        $product_id = $product['product_id'];
        $quantity = $product['quantity'];
        $toppings = $product['toppings'];


        $item_warehouse = $this->product_warehouse_repository->getProduct($warehouse, $product_id);
        if ($item_warehouse) {
            $products_id [] = $item_warehouse->id;

            $new_quantity = ($type) ? $item_warehouse->pivot->projected_quantity + $quantity : $item_warehouse->pivot->projected_quantity - $quantity;

            $product_warehouse[] = array(
                'product_id' => $item_warehouse->id,
                'projected_quantity' => $new_quantity,
            );

            foreach ($toppings as $topping) {
                if ($new_item) {
                    $topping_id = $topping['topping_id'];
                } else {
                    $topping_id = $topping->pivot->topping_id;
                }

                $product_topping = $this->product_warehouse_repository->getProduct($warehouse, $topping_id);
                $products_id [] = $product_topping->id;
                $topping_new_quantity = ($type) ? $product_topping->pivot->projected_quantity + $quantity : $product_topping->pivot->projected_quantity - $quantity;

                $product_warehouse[] = array(
                    'product_id' => $product_topping->id,
                    'projected_quantity' => $topping_new_quantity,
                );
            }
        }

        if ($single_flag) {
            $this->product_warehouse_repository->updateQuantity($warehouse, $products_id, $product_warehouse);
        }

        return [$products_id, $product_warehouse];
    }

    /* Helper functions */
    private function prepareTopping($toppings, $quantity, $products_model = null)
    {
        $toppings_object = collect([]);
        foreach ($toppings as $topping) {
            if ($products_model) {
                $target_product = $products_model->where('id', $topping->id)->first();
            } else {
                $target_product = $this->productRepository->getOne('id', $topping);
            }

            $price = $this->preparePrice($target_product->priceLists);

            $toppings_object->push([
                'topping_id' => ($products_model) ? $topping->id : $topping,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
        return $toppings_object;
    }

    private function preparePrice($price_lists)
    {
        $price = 0;
        foreach ($price_lists as $price_list) {
            if ($price_list->key == 'STANDARD_SELLING_PRICE') {
                $price = $price_list->pivot->price;
                break;
            }
        }

        if ($price == 0) {
            CheckoutErrorsHelper::noSellingPriceList();
        }

        return $price;
    }

    private function getProductPayloadIds($products)
    {
        $ids = [];
        foreach ($products as $product) {
            $ids [] = $product->product_id;

            if (isset($product->toppings)) {
                foreach ($product->toppings as $topping) {
                    $ids [] = $topping->id;
                }
            }
        }
        return $ids;
    }


    private function calculateProductPrice($product)
    {
        return $product['price'] * $product['quantity'];
    }

    private function calculateProductToppingPrice($product)
    {
        $topping_price = 0;

        foreach ($product['toppings'] as $topping) {
            $product_topping_price = $topping['price'] * $topping['quantity'];

            $topping_price += $product_topping_price;
        }

        return $topping_price;
    }
}
