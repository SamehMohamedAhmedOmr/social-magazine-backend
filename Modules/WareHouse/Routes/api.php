<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/* Authenticated APIs */
Route::group(['middleware' => ['auth:api']], function () {
    Route::namespace('CMS')->prefix('admins')->as('admins.')->group(function () {
        Route::namespace('Shipment')->prefix('shipments')->as('shipments.')->group(function () {
            Route::apiResource('companies', 'CompaniesController');
            Route::prefix('companies')->as('companies.')->group(function () {
                Route::get('sheet/export', 'CompaniesController@export')->name('export');
            });

            Route::post('/', 'ShipmentsController@store')->name('store');
            Route::post('webHook', 'ShipmentsController@webHook')->name('webHook');
            Route::get('{shipment}/receipt', 'ShipmentsController@shipmentReceipt')->name('receipt');
            Route::get('{shipment}/track', 'ShipmentsController@track')->name('track');
            Route::get('{shipment}', 'ShipmentsController@show')->name('show');
        });

        Route::apiResource('countries', 'CountryController');
        Route::prefix('countries')->as('countries.')->group(function () {
            Route::get('sheet/export', 'CountryController@export')->name('export');
        });

        Route::apiResource('districts', 'DistrictController');
        Route::prefix('districts')->as('districts.')->group(function () {
            Route::get('sheet/export', 'DistrictController@export')->name('export');
        });
        Route::get('districts-list', 'DistrictController@listDistrict')->name('districts-list.list');

        Route::apiResource('warehouses', 'WarehouseController');
        Route::prefix('warehouses')->as('warehouses.')->group(function () {
            Route::get('sheet/export', 'WarehouseController@export')->name('export');
        });

        Route::apiResource('purchase-orders', 'PurchaseOrderController');
        Route::post('purchase-orders/{purchase_order}/email', 'PurchaseOrderController@sendEmail')
            ->name('purchase-orders.send.email');
        Route::get('purchase-orders/{purchase_order}/pdf', 'PurchaseOrderController@getWithPdf')
            ->name('purchase-orders.generate.pdf');

        Route::apiResource('purchase-receipts', 'PurchaseReceiptController');
        Route::put('purchase-receipts/{purchase_receipt}/status', 'PurchaseReceiptController@changeStatus')
            ->name('purchase-receipts.change.status');
        Route::get('purchase-receipts/{purchase_receipt}/pdf', 'PurchaseReceiptController@generatePDf')
            ->name('purchase-receipts.generate.pdf');

        Route::apiResource('purchase-invoices', 'PurchaseInvoiceController');

        Route::apiResource('payment-entries', 'PaymentEntryController');
        Route::get('payment-entries/{payment_entry}/pdf', 'PaymentEntryController@generatePDf')
            ->name('payment-entries.generate.pdf');

        Route::prefix('stocks')->as('stocks.')->group(function () {
            Route::post('/sell-with-availability', 'StockController@sellWithAvailability')->name('products.sell-with-availability');
            Route::post('upload_sheet', 'StockController@uploadStockSheet')->name('upload.sheet');
            Route::post('import', 'StockController@store')
                ->name('sheet.import');
            Route::post('notifications', 'StockController@sendNotification')
                ->name('notifications.sheet');

            Route::get('/{product}/list', 'StockController@allProductQuantity')->name('products.quantity');
            Route::get('/{product}', 'StockController@availableProductQuantity')->name('product.available');
            Route::get('/{product}/log', 'StockController@productStockLogs')->name('product.logs');
            Route::post('/add', 'StockController@addQuantity')->name('product.quantity.add');
            Route::post('/move', 'StockController@moveQuantity')->name('product.quantity.move');
            Route::get('/{product}/quantity', 'StockController@productWarehouseQuantity')->name('get.product.quantity');

        });

            Route::namespace('Order')->group(function () {
                Route::prefix('orders')->group(function () {
                    Route::match(
                        ['PATCH', 'PUT'],
                        'statuses/{status}/restore',
                        'OrderStatusController@restore'
                    )->name('statuses.restore');
                    Route::apiResource('statuses', 'OrderStatusController');
                    Route::prefix('statuses')->as('statuses.')->group(function () {
                        Route::get('sheet/export', 'OrderStatusController@export')->name('export');
                    });

                    Route::post('/', 'OrderCMSController@checkout')->name('orders.store');
                    Route::get('/{order}', 'OrderCMSController@get')->name('orders.show');
                    Route::get('/', 'OrderCMSController@index')->name('orders.index');
                    Route::put('/{order}', 'OrderCMSController@edit')->name('order.edit');
                    Route::post('/status', 'OrderCMSController@editStatus')->name('orders.bulk.edit');
                    Route::post('/sheet/export', 'OrderCMSController@exportOrders')->name('orders.export');
                });

            Route::apiResource('orders-notes', 'OrderNotesController');
                Route::prefix('orders-notes')->as('orders-notes.')->group(function () {
                    Route::get('sheet/export', 'OrderNotesController@export')->name('export');
                });

            Route::prefix('orders-items')->group(function () {
                Route::post('/', 'OrderItemCMSController@edit')->name('orders.item.edit');
                Route::delete('/{order_item_id}', 'OrderItemCMSController@delete')->name('orders.item.delete');
            });
        });
    });
});


Route::namespace('Frontend')->prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::prefix('cart')->group(function () {
            Route::get('/', 'CartController@index');;
            Route::post('/', 'CartController@store');
            Route::post('calculation', 'CartController@cartCalculation');
        });

        Route::prefix('checkout')->group(function () {
            Route::post('/', 'CheckoutController@checkout');
            Route::post('/callback', 'CheckoutController@callback');
        });

        Route::prefix('orders')->group(function () {
            Route::get('/{order}', 'CheckoutController@get');
            Route::get('/', 'CheckoutController@index');
        });
    });

    Route::prefix('countries')->group(function () {
        Route::get('/', 'CountryController@index');
        Route::get('{country}/districts', 'DistrictController@districtsInCountry');
    });

    Route::get('districts', 'DistrictController@districtTree');

    Route::namespace('Shipment')->prefix('shipment')->group(function () {
        Route::get('companies', 'CompaniesController@index');
    });

    Route::prefix('payments/callbacks')->group(function () {
        Route::post('we-accept/processed', 'PaymentsCallbackControllers@weAcceptProcessed');
        Route::get('we-accept/response', 'PaymentsCallbackControllers@weAcceptResponse');
    });
});
