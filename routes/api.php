<?php

use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\AuthorizationMiddleware;
use App\Http\Middleware\CheckItemExistenceMiddleware;
use App\User;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
    * User router
*/
Route::prefix('/users') -> group(function() {

    // * Signup route
    Route::post('/signup', 'AuthController@signup'); // done
    
    // * Login route
    Route::post('/login', 'AuthController@login'); // done

    // * Logout route
    Route::get('/logout', 'AuthController@logout'); // done

    // * Pass requests through authenticate middleware

    Route::middleware([AuthenticationMiddleware::class])->group(function() {

        // * Check logged in route
        Route::get('/isLoggedIn', 'AuthController@isLoggedIn'); // done

        // * Update passcode
        Route::patch('/updatePasscode', 'AuthController@updatePasscode'); // done

        // * Get user profile
        Route::get('/profile', 'UserController@getUserProfile'); // done

        // * Update user profile
        Route::patch('/profile/edit', 'UserController@updateUserProfile'); //done
    });
    
});

/*
* Items router
*/
Route::prefix('/items') -> group(function() {
  
    // * Get list of items route
    Route::get('/','ItemController@getListItems');

    //
    Route::post('','ItemController@takeItems');

        // Get item's detail route
        Route::get('/{id}', 'ItemController@getItem')
        ->middleware([CheckItemExistenceMiddleware::class]); // done

        Route::get('/{id}/details_item', 'ItemController@getDetailsItem')
        ->middleware([CheckItemExistenceMiddleware::class]);

        Route::get('/{id}/{idUser}','ItemController@checkLiked')
        ->middleware([CheckItemExistenceMiddleware::class]);
        
        Route::middleware([AuthenticationMiddleware::class])->group(function() {
        // Update item's like route
            Route::patch('/{id}', 'ItemController@updateLikeItem')
            ->middleware([CheckItemExistenceMiddleware::class]); // done

            Route::patch('/{id}/chosen_item', 'ItemController@chosenItem')
            ->middleware([CheckItemExistenceMiddleware::class]); // done
        });
});

//ChosenItem router
Route::prefix('/chosen_items') -> group(function() {
        Route::middleware([AuthenticationMiddleware::class])->group(function() {
            // * Get list of items route
            Route::get('/','ItemController@getChosenItem');
            Route::delete('/{id}/delete', 'ItemController@deleteChosenItem');
        });
});

//Bill router
Route::prefix('/bill') -> group(function() {
    Route::middleware([AuthenticationMiddleware::class])->group(function() {
        // * Get list of items route
        Route::post('/','BillController@createBill');
    });

    Route::middleware([AuthenticationMiddleware::class])->group(function() {
        // * Get list of items route
        Route::get('/','BillController@getBill');
    });
});
