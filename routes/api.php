<?php

use App\Http\Controllers\Api\BrandController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $expiration = Carbon::now()->addDay();
        $token = $user->createToken('api-token', ['*'], $expiration);

        return response()->json([
            'token' => $token->plainTextToken,
            'expiration' => $expiration,
        ]);
    }

    throw ValidationException::withMessages([
        'username' => trans('auth.failed'),
    ]);
});

Route::controller(BrandController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/buscar', 'initialize');

    Route::get('/marcas', 'brands');
    Route::get('/marcas/{idMarca}', 'brand')->whereNumber('idMarca');

    Route::get('/marcas/{idMarca}/modelos', 'models')->whereNumber('idMarca');

    Route::post('/marcas/{idMarca}/modelos/{idModelo}', 'updateModel')->whereNumber('idMarca')->whereNumber('idModelo');
});
