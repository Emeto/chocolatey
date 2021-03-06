<?php

/*
|--------------------------------------------------------------------------
| Habbo Main Route
|--------------------------------------------------------------------------
|
| Here it's registered the main Route
| This Route redirects to HabboWEB
|
*/

# Main Request Route

use Illuminate\Support\Facades\Config;

$app->get('/', 'HomePageController@show');

/*
|--------------------------------------------------------------------------
| Habbo API Routes
|--------------------------------------------------------------------------
|
| Here are registered all main HabboWEB api routes
| You can simply went to their respective controllers.
|
*/

# Main API Request is Forbidden
$app->get('/api', function () {
    return response('Unauthorized.', 401);
});

# Go to Help Page
$app->get('api/public/help', function () {
    return redirect(Config::get('chocolatey.help'));
});

# Get Data from a Room
$app->get('api/public/rooms/{room}', 'RoomsController@getRoom');

# Get User Public Data
$app->get('api/public/users', 'ProfileController@getPublicData');

# Get User Public Data
$app->get('api/public/users/{userId}/profile', 'ProfileController@getPublicProfile');

# Create an User Request
$app->post('api/public/registration/new', 'LoginController@register');

# Confirm E-mail
$app->post('api/public/registration/activate', 'AccountController@confirmActivation');
// URI: /activate/{{TOKEN}}
// POST - token
// Invalid Token: 400 {"error":"activation.invalid_token"}
// Success: 200 {"email":"claudio.santoro@uiot.org","emailVerified":true,"identityVerified":true}

# Confirm E-mail
$app->post('api/public/forgotPassword/changePassword', 'AccountSecurityController@confirmChangePassword');
// URI: /reset-password/{{TOKEN}}
// POST - password, token
// Invalid Token: 404 - No Content
// Used password in past: 400 - {"error":"password.used_earlier"}
// Success: 204 - No Content

# Authenticate User
$app->post('api/public/authentication/login', 'LoginController@login');

# Middleware that Requires Authentication
$app->group(['middleware' => 'auth'], function () use ($app) {
    # Logout User
    $app->post('api/public/authentication/logout', 'LoginController@logout');

    # Client URL
    $app->get('api/client/clienturl', 'ClientController@getUrl');

    # Change Password Request
    $app->post('api/settings/password/change', 'AccountSecurityController@changePassword');

    # Resend E-mail Verification
    $app->post('api/settings/email/verification-resend', 'MailController@verify');

    # User Privacy Settings
    $app->get('api/user/preferences', 'AccountController@getPreferences');

    # User Privacy Settings Changes
    $app->post('api/user/preferences/save', 'AccountController@savePreferences');

    # User Security Settings Request
    $app->get('api/safetylock/featureStatus', 'AccountSecurityController@featureStatus');

    # User Security Settings Save
    $app->post('api/safetylock/save', 'AccountSecurityController@saveQuestions');

    # User Security Settings Disable
    $app->get('api/safetylock/disable', 'AccountSecurityController@disable');

    # User Security Settings getQuestions
    $app->get('api/safetylock/questions', 'AccountSecurityController@getQuestions');

    # User Security Settings Verify Questions
    $app->post('api/safetylock/unlock', 'AccountSecurityController@verifyQuestions');

    # User Security Settings Reset Devices
    $app->get('api/safetylock/resetTrustedLogins', 'AccountSecurityController@reset');

    # Resend E-mail Verification
    $app->post('api/settings/email/change', 'AccountSecurityController@changeMail');

    # Habbo Client Loginstep
    $app->post('/api/log/loginstep', function () {
        return response(null, 204);
    });

    # New User Client Check
    $app->post('api/newuser/name/check', 'AccountController@checkName');

    # New User Client Select Username
    $app->post('api/newuser/name/select', 'AccountController@selectName');

    # Save User Look
    $app->post('api/user/look/save', 'AccountController@saveLook');

    # Get User (AzureID) Avatars
    $app->get('api/user/avatars', 'AccountController@getAvatars');

    # Get User Public Data
    $app->get('api/user/profile', 'ProfileController@getProfile');

    # Create a New User Avatar
    $app->post('api/user/avatars', 'AccountController@createAvatar');

    # Select an User Avatar
    $app->post('api/user/avatars/select', 'AccountController@selectAvatar');

    # Get User (AzureID) Avatars
    $app->get('api/user/avatars/check-name', 'AccountController@checkNewName');

    # User Messenger Not Read Discussions
    $app->get('api/user/discussions', 'AccountController@getDiscussions');

    # New User Client Select Room
    $app->post('api/newuser/room/select', 'AccountController@selectRoom');
});


/*
|--------------------------------------------------------------------------
| Habbo ShopAPI Routes
|--------------------------------------------------------------------------
|
| Here are registered all shop pages related routes
| You can simply went to their respective controllers.
|
*/

# Main ShopAPI Request is Forbidden
$app->get('/shopapi', function () {
    return response('Unauthorized.', 401);
});

# Get a List of all Shop Countries
$app->get('/shopapi/public/countries', 'ShopController@listCountries');

# Get the Inventory of a specific Country
$app->get('/shopapi/public/inventory/{countryCode}', 'ShopController@getInventory');

# Middleware that Requires Authentication
$app->group(['middleware' => 'auth'], function () use ($app) {
    # Get User Purse
    $app->get('/shopapi/purse', 'ShopController@getPurse');

    # Get User Purchase History
    $app->get('/shopapi/history', 'ShopController@getHistory');

    # Get a List of all Shop Countries
    $app->get('/shopapi/countries', 'ShopController@listCountries');

    # Redeem a Voucher
    $app->post('/shopapi/voucher/redeem', 'ShopController@redeem');

    # Get the Inventory of a specific Country
    $app->get('/shopapi/inventory/{countryCode}', 'ShopController@getInventory');

    # Redirect to Purchase Proceed
    $app->get('/shopapi/proceed/{paymentCategory}/{countryCode}/{shopItem}/{paymentMethod}', 'ShopController@proceed');
});

/*
|--------------------------------------------------------------------------
| Habbo Extradata Routes
|--------------------------------------------------------------------------
|
| Here we registered all Extradata Routes
| You can simply went to their respective controllers.
|
*/

# Main Extradata Request is Forbidden
$app->get('/extradata', function () {
    return response('Unauthorized.', 401);
});

# Show All Registered HabboWEB Photos
$app->get('/extradata/public/photos', 'PublicPhotosController@show');

# Get User Stories
$app->get('extradata/public/users/{userId}/stories', 'ProfileController@getStories');

# Get User Stories
$app->get('extradata/public/users/{userId}/photos', 'ProfileController@getPhotos');

# Public Stories
$app->get('/extradata/public/users/stories', function () {
    return response()->json('aaa');
});

# Middleware that Requires Authentication
$app->group(['middleware' => 'auth'], function () use ($app) {
    # Report a Specific Photo
    $app->post('/extradata/private/creation/{photo}/report', 'PublicPhotosController@report');

    # Like a Specific Photo
    $app->post('/extradata/private/like/{photo}', 'PublicPhotosController@likePhoto');

    # Like a Specific Photo
    $app->post('/extradata/private/unlike/{photo}', 'PublicPhotosController@unlikePhoto');

    # Recent Photo Moderations
    # @TODO: Synchronize with Photo Moderations
    $app->get('/extradata/private/moderations/recentModerations', function () {
        return response()->json([]);
    });
});

/*
|--------------------------------------------------------------------------
| Habbo WebPages Routes
|--------------------------------------------------------------------------
|
| Here are registered all HabboWEB Pages Routes
| Those Routes are special, like News, Advertisement, and others.
|
*/

# Main HabboPAGES Request is Forbidden
$app->get('/habbo-web-pages/', function () {
    return response('Unauthorized.', 401);
});

# Request a Specific View of HabboWEB Pages
$app->get('/habbo-web-pages/production/{category}/{view}', 'PageController@show');

# Request a Specific View of HabboWEB Pages
$app->get('/habbo-web-pages/production/{category}/{subcategory}/{view}', 'PageController@showWithSub');

# Middleware that Requires Authentication
$app->group(['middleware' => 'auth'], function () use ($app) {
    # Request the Client URL
    $app->get('/client/{view}', 'PageController@getClient');
});

/*
|--------------------------------------------------------------------------
| Habbo WebNEWS Routes
|--------------------------------------------------------------------------
|
| Here are registered all HabboWEB Pages Routes
| Those Routes are special, like News, Advertisement, and others.
|
*/

# Main HabboNEWS Request is Forbidden
$app->get('/habbo-web-news/', function () {
    return response('Unauthorized.', 401);
});

# Request a set of Articles, generally a category or front page
$app->get('/habbo-web-news/{country}/production/{view}', 'ArticleController@many');

# Request a single Article generally when you access directly an Article
$app->get('/habbo-web-news/{country}/production/articles/{article}', 'ArticleController@one');

/*
|--------------------------------------------------------------------------
| Habbo WEB Languages
|--------------------------------------------------------------------------
|
| Here are registered all main HabboWEB Language Pages
| You can simply went to their respective controllers.
|
*/

# Main HabboLanguages Request is Forbidden
$app->get('/habbo-web-l10n/', function () {
    return response('Unauthorized.', 401);
});

# Render a specific Language Ecosystem
$app->get('/habbo-web-l10n/{language}', 'LanguageController@render');

/*
|--------------------------------------------------------------------------
| Habbo WEB Leaderboards
|--------------------------------------------------------------------------
|
| Here are the Rooms Routes
|
*/

# Render a specific Language Ecosystem
$app->get('/habbo-web-leaderboards/{countryId}/visited-rooms/{range}/{data}', 'RoomsController@getLeader');
$app->get('/habbo-web-leaderboards//{countryId}/visited-rooms/{range}/{data}', 'RoomsController@getLeader');

/*
|--------------------------------------------------------------------------
| Habbo WEB ADS
|--------------------------------------------------------------------------
|
| Here comes the Advertisement Routes
|
*/

$app->get('/habbo-web-ads/{interstitial}', 'ClientController@getInterstitial');