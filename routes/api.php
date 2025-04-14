<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyApiHeaders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/token/create', function (Request $request) {

    // $token = $request->user()->createToken($request->token_name);

    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Token created successfully',
    //     'data' => [
    //         'token' => $token->plainTextToken,
    //         'expires_at' => $token->accessToken->expires_at,
    //     ]
    // ], 200);
    return response()->json([
        'status' => 200,
        'message' => 'Token created successfully',
        'data' => [
            'token' => "XXX",
            'expires_at' => "XXX",
        ]
    ], 200);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// The 'auth:sanctum' middleware checks if the user is authenticated using Laravel Sanctum.
// This means that only authenticated users with a valid token can access the route.
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/* The line below create the APi resource routes for the BusinessController
This will automatically create routes for the standard CRUD operations (index, store, show, update, destroy) */
// Route::apiResource('business', BusinessController::class);


/* The 'api' middleware group typically includes middleware for handling API requests, such as
authentication, rate limiting, and request logging. By grouping routes under this middleware,
you can apply the same set of middleware to all routes within the group, ensuring consistent
behavior for API requests. */
// Route::middleware('api')->group(function () {
Route::middleware([VerifyApiHeaders::class])->group(function () {

    /* The line `Route::prefix('business')->group(function () {` in Laravel is grouping a set of routes
    under a common prefix 'business'. This means that all routes defined within this group will have
    the prefix '/business' added to their URLs. This helps in organizing and structuring routes
    related to a specific resource or functionality within your application. */
    Route::prefix('business')->group(function () {

        // Business CRUD endpoints

        /* This line of code is defining a GET route in Laravel that maps to the root URL ("/") of the
        application. When a user accesses the root URL, the `index` method of the
        `BusinessController` class will be called to handle the request and return a response. */
        Route::get('/', [BusinessController::class, 'index']);

        /* This line of code in Laravel is defining a POST route that maps to the root URL ("/") of the
        application. When a POST request is made to the root URL, it will be handled by the `store`
        method of the `BusinessController` class. This route is typically used for creating new
        resources or entities in the system. */
        Route::post('/', action: [BusinessController::class, 'store']);

        /* Route::post('/dummy', [BusinessController::class, 'dummy']); is defining a POST route in
        Laravel that maps to the '/dummy' URL endpoint under the 'business' prefix. When a POST
        request is made to the '/dummy' endpoint, it will be handled by the 'dummy' method of the
        BusinessController class. This route is typically used for specific actions or
        functionalities that are not part of the standard CRUD operations for the 'business'
        resource. */
        Route::post('/dummy', [BusinessController::class, 'dummy']);

        /* This line of code is defining a GET route in Laravel that includes a route parameter `{id}`.
        When a GET request is made to a URL that matches this route pattern, Laravel will capture
        the value provided in place of `{id}` and pass it as an argument to the `show` method of the
        `BusinessController` class. This route is typically used to retrieve and display a specific
        resource identified by its unique identifier (in this case, the `id` parameter) from the
        database. */
        Route::get('/{id}', [BusinessController::class, 'show']);

        /* Route::patch('/{id}', [BusinessController::class, 'update']); is defining a PATCH route in
        Laravel that includes a route parameter `{id}`. When a PATCH request is made to a URL that
        matches this route pattern, Laravel will capture the value provided in place of `{id}` and
        pass it as an argument to the `update` method of the `BusinessController` class. This route
        is typically used to update a specific resource identified by its unique identifier (in this
        case, the `id` parameter) in the database. */
        Route::patch('/{id}', [BusinessController::class, 'update']);

        /* This line of code in Laravel is defining a DELETE route that includes a route parameter
        `{id}`. When a DELETE request is made to a URL that matches this route pattern, Laravel will
        capture the value provided in place of `{id}` and pass it as an argument to the `destroy`
        method of the `BusinessController` class. This route is typically used to delete a specific
        resource identified by its unique identifier (in this case, the `id` parameter) from the
        database. */
        Route::delete('/{id}', [BusinessController::class, 'destroy']);
    });
});
