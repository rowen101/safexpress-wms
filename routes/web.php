<?php

use App\Models\Wms\Locationgroup;

use App\Models\Wms\StorageLocation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TechController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Wms\TruckerController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\PalletController;
use App\Http\Controllers\Wms\CustomerController;
use App\Http\Controllers\Wms\SupplierController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Wms\ListtableController;
use App\Http\Controllers\Wms\WarehouseController;
use App\Http\Controllers\Admin\MenuListController;
use App\Http\Controllers\Admin\UserMenuController;
use App\Http\Controllers\Wms\ItemMasterController;
use App\Http\Controllers\Admin\VirtualASController;
use App\Http\Controllers\Wms\TruckertypeController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\MyClosePrioController;
use App\Http\Controllers\Wms\LocationGroupController;
use App\Http\Controllers\Wms\WarehouseUserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Wms\StorageLocationController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\SliassetmonitoringController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// });

Route::middleware('auth')->group(function () {
    Route::get('/api/stats/appointments', [DashboardStatController::class, 'appointments']);
    Route::get('/api/stats/users', [DashboardStatController::class, 'users']);

    Route::get('/api/users', [UserController::class, 'index']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
    Route::put('/api/users/{user}', [UserController::class, 'update']);
    Route::delete('/api/users/{user}', [UserController::class, 'destory']);
    Route::delete('/api/users', [UserController::class, 'bulkDelete']);
    Route::get('/api/users/userlist', [UserController::class, 'listuser']);
    Route::patch('/api/users/{user}/change-sitehead', [UserController::class, 'changesitehead']);

    //setting
    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);

    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);

    Route::resource('/api/tech-recommendations', TechController::class);
    Route::delete('/api/tech-recommendations', [TechController::class, 'bulkDelete']);
    Route::post('/api/tech-getaction', [TechController::class, 'getAction']);


    //menu controller
    Route::resource('/api/menulist', MenuListController::class);
    Route::get('/api/GetParentId', [MenuListController::class, 'GetParentId']);

    Route::resource('/api/menu', MenuController::class);

    //menu username
    Route::resource('/api/usermenu', UserMenuController::class);

    Route::get('/api/showusermenu/{id}', [UserMenuController::class, 'showusermenu']);
    Route::get('/api/usermenu/retrieve/{id}', [UserMenuController::class, 'retrieveUserMenu']);


    Route::get('/api/chart', [DashboardStatController::class, 'getChartData']);

    Route::resource('/api/notifications', NotificationController::class);
    Route::put('/api/notifications/{id}/markAsRead', [NotificationController::class, 'markAsRead']);

    Route::resource('/web/asset-monitoring', SliassetmonitoringController::class);
    Route::delete('/web/bulkDeleteAsset', [SliassetmonitoringController::class, 'bulkDelete']);
    Route::get('/web/warehouseuser/{id}', [WarehouseUserController::class, 'index']);

    Route::get('/web/getsitewithoutuser', [WarehouseUserController::class, 'getsitewthuserid']);

    Route::post('/web/onSaveupdate', [WarehouseUserController::class, 'onSaveupdate']);

    Route::resource('/web/pallet', PalletController::class);
    Route::delete('/web/bulkDeletePallet', [PalletController::class, 'bulkDelete']);

    //warehouse routes
    Route::resource('/web/warehouse', WarehouseController::class);

    //WMS Customer
    Route::resource('/web/customer', CustomerController::class);

    //WMS supplier
      Route::resource('/web/supplier', SupplierController::class);

    //WMS trucker
    Route::resource('/web/trucker', TruckerController::class);

    //WMS Location Group
    Route::resource('/web/locationgroup', LocationGroupController::class);

    //WMS Location
    Route::resource('/web/storage-location', StorageLocationController::class);

    //WMS Trucktype
    Route::resource('/web/trucktype', TruckertypeController::class);

     //WMS Item Master
     Route::resource('/web/item-master', ItemMasterController::class);

     //listtable
     Route::get('/web/getkey/{key}', [ListtableController::class, 'getkey']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
Route::get('/web/getwarehouseuser', [WarehouseController::class, 'getAuthWarehouse']);
