<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
Route::prefix('api')->group(function(){
 Route::get('/bootstrap',[AppController::class,'bootstrap']); Route::get('/public-notes',[AppController::class,'publicNotes']); Route::get('/csrf-token',fn()=>response()->json(['token'=>csrf_token()])->header('Cache-Control','no-store, private')); Route::get('/daily-videos',[AppController::class,'dailyVideos']); Route::post('/login',[AppController::class,'login']);
 Route::middleware('auth')->group(function(){
  Route::post('/logout',[AppController::class,'logout']); Route::get('/dashboard',[AppController::class,'dashboard']);
  Route::get('/duas',[AppController::class,'index']); Route::post('/duas',[AppController::class,'store']); Route::get('/duas/{dua}',[AppController::class,'show']); Route::put('/duas/{dua}',[AppController::class,'update']); Route::delete('/duas/{dua}',[AppController::class,'destroy']);
  Route::get('/notes',[AppController::class,'notes']); Route::post('/notes',[AppController::class,'storeNote']); Route::put('/notes/{note}',[AppController::class,'updateNote']); Route::delete('/notes/{note}',[AppController::class,'destroyNote']);
  Route::get('/categories',[AppController::class,'categories']); Route::post('/categories',[AppController::class,'saveCategory']); Route::delete('/categories/{category}',[AppController::class,'deleteCategory']);
 });
});
Route::get('/{any?}',fn()=>view('app'))->where('any','.*');
