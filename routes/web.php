<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ContentController;

Route::prefix('api')->group(function () {
    Route::get('/bootstrap',[AppController::class,'bootstrap']);
    Route::get('/public-notes',[AppController::class,'publicNotes']);
    Route::get('/csrf-token',fn()=>response()->json(['token'=>csrf_token()])->header('Cache-Control','no-store, private'));
    Route::get('/daily-videos',[AppController::class,'dailyVideos']);
    Route::get('/public-content',[ContentController::class,'publicContent']);
    Route::get('/stories/{slug}',[ContentController::class,'publicStory']);
    Route::get('/resources/{slug}',[ContentController::class,'publicResource']);
    Route::get('/masail/{slug}',[ContentController::class,'publicMasala']);
    Route::post('/login',[AppController::class,'login']);

    Route::middleware('auth')->group(function () {
        Route::post('/logout',[AppController::class,'logout']);
        Route::get('/dashboard',[AppController::class,'dashboard']);
        Route::get('/duas',[AppController::class,'index']);
        Route::post('/duas',[AppController::class,'store']);
        Route::get('/duas/{dua}',[AppController::class,'show']);
        Route::put('/duas/{dua}',[AppController::class,'update']);
        Route::delete('/duas/{dua}',[AppController::class,'destroy']);
        Route::get('/notes',[AppController::class,'notes']);
        Route::post('/notes',[AppController::class,'storeNote']);
        Route::put('/notes/{note}',[AppController::class,'updateNote']);
        Route::delete('/notes/{note}',[AppController::class,'destroyNote']);
        Route::get('/categories',[AppController::class,'categories']);
        Route::post('/categories',[AppController::class,'saveCategory']);
        Route::delete('/categories/{category}',[AppController::class,'deleteCategory']);

        Route::prefix('admin')->group(function () {
            Route::get('/stories',[ContentController::class,'adminStories']);
            Route::post('/stories',[ContentController::class,'storeStory']);
            Route::put('/stories/{story}',[ContentController::class,'updateStory']);
            Route::delete('/stories/{story}',[ContentController::class,'destroyStory']);
            Route::get('/story-schedules',[ContentController::class,'adminSchedules']);
            Route::post('/story-schedules',[ContentController::class,'storeSchedule']);
            Route::put('/story-schedules/{schedule}',[ContentController::class,'updateSchedule']);
            Route::delete('/story-schedules/{schedule}',[ContentController::class,'destroySchedule']);
            Route::get('/resources',[ContentController::class,'adminResources']);
            Route::post('/resources',[ContentController::class,'storeResource']);
            Route::put('/resources/{resource}',[ContentController::class,'updateResource']);
            Route::delete('/resources/{resource}',[ContentController::class,'destroyResource']);
            Route::get('/masail',[ContentController::class,'adminMasail']);
            Route::post('/masail',[ContentController::class,'storeMasala']);
            Route::put('/masail/{masala}',[ContentController::class,'updateMasala']);
            Route::delete('/masail/{masala}',[ContentController::class,'destroyMasala']);
        });
    });
});
Route::get('/{any?}',fn()=>view('app'))->where('any','.*');
