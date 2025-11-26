<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\middleware\RoleCheckMiddleware;
use app\middleware\TokenCheckMiddleware;
use think\facade\Route;

Route::get('/', function () {
    return 'API 1.0';
});
Route::get('test', 'a.Index/testDb');

// 管理端
Route::group('a', function () {
    // 需要认证
    Route::group(function () {

        // User
        Route::get('admin', 'a.User/getInfo');

        // Menu
        Route::get('menu/active', 'a.Menu/getActiveMenus'); // 获取当前用户的菜单
        Route::get('menu/table', 'a.Menu/getTable'); // 获取所有菜单
        Route::post('menu', 'a.Menu/add');
        Route::put('menu/:id', 'a.Menu/edit');
        Route::delete('menu/:id', 'a.Menu/delete');

        // Order
        Route::get('order/list', 'a.Order/getListByPage');
        Route::get('order/:id/info', 'a.Order/getDetail');
        Route::post('order', 'a.Order/add');
        Route::put('order/:id$', 'a.Order/edit');
        Route::delete('order/:id', 'a.Order/delete');
        Route::put('order/:id/deliver', 'a.Order/setDeliver');
        Route::get('order/:id/deliver', 'a.Order/getDeliver');
        Route::put('order/:id/done', 'a.Order/setDone');
        Route::put('order/:id/cancel', 'a.Order/setCancel');

        // City
        Route::get('city/table', 'a.City/getListAll');
        Route::get('city/:id', 'a.City/getInfo');
        Route::post('city', 'a.City/add');
        Route::put('city/:id', 'a.City/edit');
        Route::delete('city/:id', 'a.City/delete');

        // Goods
        Route::get('goods/list', 'a.Goods/getListByPage');
        Route::get('goods/all', 'a.Goods/getAll');
        Route::get('goods/:id', 'a.Goods/getInfo');
        Route::post('goods', 'a.Goods/add');
        Route::put('goods/:id', 'a.Goods/edit');
        Route::delete('goods/:id', 'a.Goods/delete');

        // Refund
        Route::get('refund/list', 'a.Refund/getListByPage');
        Route::get('refund/:id', 'a.Refund/getDetail');
        Route::post('refund/add', 'a.Refund/add');
        Route::put('refund/:id/update', 'a.Refund/edit');
        Route::delete('refund/:id', 'a.Refund/delete');

        // Statistic
        // 订单基础
        Route::get('statistic/order/basic', 'a.Statistic/getOrderBasic');
        // 订单趋势
        Route::get('statistic/order/trend', 'a.Statistic/getOrderTrend');
        // 订单累计
        Route::get('statistic/order/accumulate', 'a.Statistic/getOrderAccumulate');
        // 商品排行
        Route::get('statistic/goods/rank', 'a.Statistic/getGoodsRank');

        // OCR
        Route::post('ocr/order/add', 'a.ocr/getOrder');

        Route::get('start', 'a.Login/loginInit');
        Route::get('reserve/list', 'a.Reserve/getListByPage');
        Route::get('shop/list', 'a.Shop/getList');
        Route::put('shop/image', 'a.Shop/setImage');
        Route::get('shop/roomtree', 'a.Shop/getShopAndRoom');
        Route::put('shop/other', 'a.shop/setOther');
        Route::get('shop/other/:id', 'a.shop/getOther');
        Route::put('shop/:id', 'a.shop/edit');
        Route::post('shop$', 'a.shop/add');
        Route::put('room/:id', 'a.room/edit');
        Route::post('room$', 'a.room/add');
        Route::delete('room/:id', 'a.room/delete');
        Route::post('reserve', 'a.Reserve/add');
        Route::put('reserve/:id', 'a.Reserve/edit');
        Route::delete('reserve/:id', 'a.Reserve/delete');
        Route::post('upload$', 'a.Upload/index');
        Route::put('user/changePwd', 'a.User/changePassword');
    })->middleware([TokenCheckMiddleware::class, RoleCheckMiddleware::class]);

    // 直接放行
    Route::group(function () {
        Route::post('login$', 'a.Login/login');
        Route::post('logout$', 'a.Login/logout');
    });

    Route::group(function () {
        Route::get('test/get', 'a.Index/testGet');
        Route::post('test/post', 'a.Index/testPost');
    });

});

// 业务端
Route::group('u', function () {
    // 需要认证
    Route::group(function () {

    })->middleware([TokenCheckMiddleware::class, RoleCheckMiddleware::class]);

    // 直接放行
    Route::group(function () {
        // 登录
        Route::post('login/wxapp', 'u.Login/wxappLogin');
        // 店铺列表
        Route::get('shop$', 'u.shop/getList');
        // 预定列表
        Route::get('reserve$', 'u.Reserve/getListByPage');
        // 按日期获取预定列表
        Route::post('reserve/day', 'u.Reserve/getListByDate');
        // 预定详情
        Route::get('reserve/:id', 'u.Reserve/getDetail');
        // 新增预定
        Route::post('reserve$', 'u.Reserve/add');
        // 修改预定
        Route::put('reserve/:id', 'u.Reserve/edit');
        // 删除预定
        Route::delete('reserve/:id', 'a.Reserve/delete');
    });
});
