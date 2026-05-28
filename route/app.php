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
use app\middleware\AllowCrossDomain;
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

        /**********  User  **********/
        Route::get('admin', 'a.User/getInfo');

        /**********  Menu  **********/
        Route::get('menu/active', 'a.Menu/getActiveMenus'); // 获取当前用户的菜单
        Route::get('menu/table', 'a.Menu/getTable');        // 获取所有菜单
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

    })->middleware([TokenCheckMiddleware::class, RoleCheckMiddleware::class]);

    // 直接放行
    Route::group(function () {
        Route::post('login$', 'a.Login/login');
        Route::post('logout$', 'a.Login/logout');
    });

    Route::group(function () {
        Route::get('test/get', 'a.Index/testGet');
        Route::post('test/post', 'a.Index/testPost');
        Route::get('test/db', 'a.Index/test');
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

        /**********  Banner  **********/
        Route::get('banner/index', 'u.Banner/getIndexBanner');

        /**********  Goods  **********/
        Route::get('goods/tj', 'u.Goods/getIndexTj');
        Route::get('goods/hot', 'u.Goods/getIndexHot');
        Route::get('goods/floor', 'u.Goods/getIndexFloor');

        /**********  GoodsCate  **********/
        Route::get('cate/hot', 'u.GoodsCate/getIndexHot');

        /**********  Article  **********/
        Route::get('article/index', 'u.Article/getIndexNews');

        /**********  Ad  **********/
        Route::get('ad/getAll', 'u.Ad/getAll');
    })->middleware([AllowCrossDomain::class]);;
});
