<?php

namespace Modules\WareHouse\Services\Frontend;

use Illuminate\Http\JsonResponse;
use Auth;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use CheckoutErrorsHelper;
use Modules\Users\Repositories\AddressRepository;
use Modules\WareHouse\Repositories\CartItemRepository;
use Modules\WareHouse\Repositories\CartRepository;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Illuminate\Validation\ValidationException;
use Modules\WareHouse\Transformers\Frontend\Cart\CartResource;
use Modules\WareHouse\Transformers\Frontend\Checkout\CartItem\PrepareCartResource;

class CartService extends LaravelServiceClass
{
    private $cart_repo;
    private $cart_item;
    private $district_repository;
    private $warehouse_repository;
    private $address_repo;

    public function __construct(
        CartRepository $cart_repo,
        DistrictRepository $district_repository,
        WarehouseRepository $warehouse_repository,
        AddressRepository $address_repo,
        CartItemRepository $cart_item
    )
    {
        $this->cart_repo = $cart_repo;
        $this->cart_item = $cart_item;
        $this->district_repository = $district_repository;
        $this->warehouse_repository = $warehouse_repository;

        $this->address_repo = $address_repo;
    }

    public function index()
    {
        $district_id = UtilitiesHelper::getDistrictId();
        $district_id = trim($district_id);
        $district_id = (int)$district_id;

        list($items) = $this->getCart($district_id);

        list($message, $items) = $this->checkInActiveItem($items);
        $message = $message ?? '';

        return ApiResponse::format(200, $items, $message);
    }

    public function getCart($district_id, $checkout = 0)
    {
        $this->cart_repo->updateOrCreate(['user_id' => Auth::id()], []);

        $cart = $this->cart_repo->get(Auth::id(), [], 'user_id');

        $warehouses = $this->detectWarehouse($district_id);

        $cart_items = $cart->cartItems;

        foreach ($cart_items as $cart_item) {
            $cart_item->warehouse = $warehouses;
        }
        if ($checkout) {
            $items = collect(PrepareCartResource::collection($cart_items));
            return [$items, $cart_items, $warehouses];
        }

        $items = collect(CartResource::collection($cart_items));
        return [$items];
    }

    public function checkInActiveItem($cart_items)
    {
        $message = '';
        $items = collect([]);
        $count_items = count($cart_items) - 1;

        $deleted_target_items = collect([]);

        foreach ($cart_items as $index => $cart_item) {
            if ($cart_item['is_active'] == 0) {
                $and = ($index != $count_items) ? '' : ' and ';
                $new_message = $and . 'Product ' . $cart_item['name'];
                $message .= $new_message;
                $deleted_target_items->push($cart_item['cart_item_id']);
            } else {
                $items->push($cart_item);
            }
        }

        $deleted_target_items = $deleted_target_items->toArray();
        if (count($deleted_target_items)) {
            $this->cart_item->deleteBulk($deleted_target_items);
        }

        if ($message) {
            $message .= ' become not active';
        }

        return [$message, $items];
    }

    /**
     * get Target Warehouse
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store()
    {
        $this->validateQuantity(request('products'));
        // create or update Cart table
        $cart = $this->cart_repo->updateOrCreate(['user_id' => Auth::id()], []);

        foreach (request('products') as $product) {
            $product_id = $product['id'];
            $new_quantity = $product['quantity'];
            $has_toppings = (isset($product['toppings'])) ? 1 : 0;

            if (isset($product['cart_item_id'])) { // update or delete
                $cart_item_id = $product['cart_item_id'];
                if ($new_quantity == 0) { // delete cart_item
                    $this->cart_item->delete($cart_item_id);
                } else {
                    $this->updateCartItem($product, $cart_item_id, $new_quantity);
                }
            } else { // Added
                if ($new_quantity != 0) {
                    $this->addCartItem($cart, $product_id, $new_quantity, $has_toppings, $product);
                }
            }
        }

        return $this->index();
    }

    private function addCartItem($cart, $product_id, $new_quantity, $has_toppings, $product)
    {
        $cart_product = $this->cart_item->create([
            'cart_id' => $cart->id,
            'product_id' => $product_id,
            'quantity' => $new_quantity,
            'has_toppings' => $has_toppings,
        ]);

        if (isset($product['toppings'])) {
            $this->cart_item->attachTopping($cart_product, $product['toppings']);
        }
    }

    private function updateCartItem($product, $cart_item_id, $new_quantity)
    {
        $has_toppings = isset($product['toppings']) ? 1 : 0;

        $cart_product = $this->cart_item->update($cart_item_id, [
            'quantity' => $new_quantity,
            'has_toppings' => $has_toppings,
        ]);

        $this->cart_item->detachTopping($cart_product);
        if (isset($product['toppings'])) {
            $this->cart_item->attachTopping($cart_product, $product['toppings']);
        }
    }

    /**
     * get Target Warehouse
     *
     * @param $products
     * @return void
     * @throws ValidationException
     */
    private function validateQuantity($products)
    {
        $warehouses = $this->warehouse_repository->whereIn(\Session::get('warehouses_id'));


        if (!$warehouses) {
            throw ValidationException::withMessages([
                'quantity' => 'product quantity are not available'
            ]);
        }
        $warehouses->load('products');

        $product_available = collect([]);

        foreach ($products as $product) {
            if ($product['quantity'] == 0) {
                $product_available->push(true);
                continue;
            }

            foreach ($warehouses as $warehouse){
                // product is eager loading in detectWarehouse method
                $warehouse_product = $warehouse->products->where('id', $product['id'])->first();
                if (isset($warehouse_product)) {
                    $projected_quantity = $warehouse_product->pivot->projected_quantity;
                    if ($projected_quantity >= $product['quantity']
                        || ($warehouse_product->is_sell_with_availability &&
                            $warehouse_product->pivot->available &&
                            $warehouse_product->max_quantity_per_order >= $product['quantity'])) {

                        $product_available->push(true);
                        break;
                    }
                }
            }
        }


        if (count($product_available) != count($products)) {
            throw ValidationException::withMessages([
                'quantity' => 'product quantity are not available'
            ]);
        }
    }

    private function detectWarehouse($district_id = null)
    {
        $district_exist = false;
        $warehouse = null;

        if ($district_id) {
            $district = $this->district_repository->getDistrict($district_id, 'warehouse');
            if ($district) {
                $warehouse = $district->warehouse;
                if ($warehouse) {
                    $district_exist = true;
                }
            }
        }

        if ($district_exist == false) {
            $warehouse = $this->warehouse_repository->getDefault();
        }
        return $warehouse;
    }

    public function calculateCart($cartItems, $district_id = null)
    {
        $total_price = 0;
        $cartItems->load('product.priceLists', 'toppings.priceLists');
        $district = $this->district_repository->getDistrict($district_id);

        if (isset($district) && isset($district->shippingRule)) {
            $shipping_rule = $district->shippingRule->price;
            $shipping_rule_id = $district->shippingRule->id;
        } else {
            $shipping_rule = 0;
            $shipping_rule_id = null;
        }

        foreach ($cartItems as $cartItem) {
            $product_price = $this->calculateProductPrice($cartItem);

            $toppings_price = $this->calculateProductToppingPrice($cartItem);

            $cartItem_price = $product_price + $toppings_price;

            $total_price += $cartItem_price;
        }

        return [$total_price, $shipping_rule, $shipping_rule_id];
    }

    private function calculateProductPrice($cartItem)
    {
        // product price
        $product_price_list = $cartItem->product->priceLists->where('key', 'STANDARD_SELLING_PRICE')->first();
        if (!$product_price_list) {
            CheckoutErrorsHelper::noSellingPriceList();
        }
        $product_price = $product_price_list->pivot->price;

        return $product_price * $cartItem->quantity;
    }

    private function calculateProductToppingPrice($cartItem)
    {
        $topping_price = 0;

        foreach ($cartItem->toppings as $topping) {
            $topping_price_list = $topping->priceLists->where('key', 'STANDARD_SELLING_PRICE')->first();
            if (!$topping_price_list) {
                CheckoutErrorsHelper::noSellingPriceListForTopping();
            }
            $product_topping_price = $topping_price_list->pivot->price;
            $product_topping_price = $product_topping_price * $cartItem->quantity;

            $topping_price += $product_topping_price;
        }

        return $topping_price;
    }
}
