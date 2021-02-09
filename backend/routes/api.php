<?php

use Illuminate\Http\Request;
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

// API Version 1.0
Route::prefix('/api/v1')->group(function () {

    // Public routes
    Route::get('/images/{file_name}', 'GeneralController@images');
    Route::get('/vehicle-photos/{file_name}', 'GeneralController@vehiclePhotos');
    Route::get('/parking-photos/{file_name}', 'GeneralController@parkingPhotos');

    // User end-point (public)
    Route::prefix('/user')->group(function () {

        // General Routes
        Route::post('/signup', 'UserController@signUp');
        Route::post('/authenticate', 'UserController@authenticate');
        Route::middleware('auth:api')->post('/check-authentication', 'UserController@checkAuthentication');

        // Specific user related routes
        Route::get('/{user_id}/profile-pic', 'UserController@profilePic');
    });

    // Pusher authenticate
    Route::post('/pusher-authenticate', 'GeneralController@pusherAuthenticate');

    // X User (private)
    Route::prefix('/x-user')
        ->middleware([
            'auth:api',
            // 'user.activity.status'
        ])
        ->group(function () {

            // Send a post
            Route::post('/send-post', 'XUserController@sendPost');

            // Log out
            Route::post('/log-out', 'XUserController@logOut');

            // Update status
            Route::post('/update-status', 'XUserController@updateStatus');

            // Feed
            Route::get('/feed', 'XUserController@feed');

            // Posts related end-points
            Route::prefix('/post')
                // ->middleware(['post.access'])
                ->group(function () {
                    Route::get('/all', 'XUserController@getAllPosts');
                    Route::get('/{id}', 'XUserController@getPost');
                    Route::post('/{id}/like', 'XUserController@likePost');
                    Route::post('/{id}/send-comment', 'XUserController@sendComment');
                    Route::get('/{id}/comments', 'XUserController@getComments');
                });

            
            Route::prefix('/activity')
                // ->middleware(['post.access'])
                ->group(function () {
                    Route::get('/all', 'ActivityController@getAllActivities');
                    Route::post('/add', 'ActivityController@addActivity');
                });

            Route::prefix('/task')
                // ->middleware(['post.access'])
                ->group(function () {
                    Route::get('/all', 'TaskController@getAllTasks');
                    Route::post('/add', 'TaskController@addTask');
                });

            // Chat
            Route::prefix('/chat')->group(function() {
                Route::get('/getChat', 'ChatController@getChat');
                Route::get('/getUsers', 'ChatController@getUsers');
                Route::post('/send-message', 'ChatController@sendMessage');
                Route::post('/upload-voice', 'ChatController@uploadVoice');
                Route::post('/upload-image', 'ChatController@uploadImage');
                Route::post('/upload-video', 'ChatController@uploadVideo');
                Route::post('/mark-as-read', 'ChatController@markAsRead');

                // Files
                Route::get('/get-voice/{file_name}', 'ChatController@getVoiceFile');
                Route::get('/get-image/{file_name}', 'ChatController@getImageFile');
                Route::get('/get-video/{file_name}', 'ChatController@getVideoFile');

                // Call
                Route::post('/call-request', 'ChatController@callRequest');
            });

            // Profile end-point
            Route::prefix('/profile')->group(function () {
                Route::get('/info', 'XUserController@profileInfo');
                Route::get('/pic', 'XUserController@profilePic');
            });

            // Public profile
            Route::get('/profile/{plate_number}', 'XUserController@publicProfile');

            // Friends end-point
            Route::prefix('/friends')->group(function () {
                Route::get('/list', 'XUserController@friendsList');
            });

            // Add friend
            Route::prefix('/friend')->group(function () {
                Route::post('/add', 'XUserController@addFriend');
                Route::post('/remove', 'XUserController@removeFriend');
                Route::post('/update-friend-request', 'XUserController@updateFriendRequest');
                Route::post('/decline-friend-request', 'XUserController@declineFriendRequest');
            });
            
            // Get notifications
            Route::get('/notifications', 'XUserController@getNotifications');

            // Mark notifications as read
            Route::post('/delete-notifications', 'XUserController@deleteNotifications');

            // Find someone
            Route::get('/find-someone', 'XUserController@findSomeone');

            // Edit profile
            Route::prefix('/edit-profile')->group(function() {
                Route::get('/info', 'XUserController@getEditInfo');
                Route::post('/account-info', 'XUserController@editAccountInfo');
                Route::post('/change-password', 'XUserController@changePassword');
                Route::post('/change-profile-pic', 'XUserController@changeProfilePic');
                Route::post('/submit-driver-documentations', 'XUserController@submitDriverDocumentations');
                Route::get('/get-driver-documentations', 'XUserController@getDriverDocumentations');
            });

            // Drivers
            Route::prefix('/drivers')->group(function() {
                Route::get('/availability', 'DriversController@availability');
                Route::post('/update-availability', 'DriversController@updateAvailability');
                Route::get('/available-drivers', 'DriversController@getAvailableDrivers');
            });

            Route::prefix('/driver-service')->group(function() {
                Route::get('/get-service',     'DriverServiceController@getDriverService');
                Route::post('/add-service',     'DriverServiceController@addDriverService');
                Route::post('/update-service',  'DriverServiceController@updateDriverService');
            });

            // Renting
            Route::prefix('/renting')->group(function() {
                Route::post('/create', 'RentingController@create');
                Route::get('/search', 'RentingController@search');
            });

            // Selling
            Route::prefix('/selling')->group(function() {
                Route::post('/create', 'SellingController@create');
                Route::get('/get', 'SellingController@get');
                Route::get('/price-range', 'SellingController@priceRange');
                Route::get('/search', 'SellingController@search');
            });

            // Parking
            Route::prefix('/parking')->group(function() {
                Route::post('/create', 'ParkingController@create');
                Route::get('/get', 'ParkingController@get');
                Route::get('/search', 'ParkingController@search');
            });

            // Plates
            Route::prefix('/plate')->group(function() {
                Route::get('/list', 'XUserController@getPlatesList');
                Route::post('/change-plate-number', 'XUserController@changePlateNumber');
                Route::post('/request-access-code', 'XUserController@requestAccessCode');
                Route::post('/change-guest-plate-number', 'XUserController@changeGuestPlateNumber');
                Route::post('/revoke-guest-plate-number', 'XUserController@revokeGuestPlateNumber');
                Route::post('/delete', 'XUserController@deletePlateNumber');
                Route::post('/quit-using-guest-plate', 'XUserController@quitUsingGuestPlate');
            });

            Route::prefix('/billing')->group(function() {
                Route::get('/info', 'BalanceController@getBillingInfo');
                Route::get('/get-stripe-pk', 'BalanceController@getStripePublicKey');
                Route::post('/stripe-charge', 'BalanceController@stripeCharge');
                Route::post('/transfer-funds', 'BalanceController@transferFunds');
            });
        });

    // Errors
    Route::prefix('/error')->group(function () {
        Route::get('/authentication-error', 'ErrorsController@authenticationError')->name('authentication-error');
    });

    // Test
//    Route::get('/test', 'ErrorsController@test');
});

