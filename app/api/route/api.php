<?php

use think\facade\Route;

Route::rule('smscode', 'sms/code');
Route::rule('login', 'login/index');
//资源路由
Route::resource('user', 'User');
Route::rule('/category/search/:id', 'category/search');
Route::rule('subcategory/:id', 'category/subcategory');
Route::rule("lists", "mall.lists/index");
Route::rule("detail/:id", "mall.detail/index");


Route::resource("order", "order.index");