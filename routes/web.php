<?php

use App\Demand;
use App\Http\Middleware\CheckUserStatus;
use App\Notifications\VerifyDemandNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('test-dates', function () {
//     $banners = \App\Banner::where('status', 1)->get();

//     if ($banners->count() < 1) {
//         return 'niciun banner activ';
//     }

//     foreach ($banners as $banner) {
//         $banner_ends = Carbon::parse($banner->ends_at);
//         if ($banner_ends->lt(now())) {
//             // return $banner;
//             $banner->status = 0;
//             $banner->editable = 1;
//             $banner->paid = 0;
//             $banner->processing = 0;
//             $banner->rejected = 0;
//             $banner->save();
//         }
//     }
// });

// Route::get('test-stripe', [
//     'uses' => 'StripeCheckoutController@index',
//     'as' => 'stripe.checkout.index',
// ]);

// Route::get('plata/anulare', [
//     'uses' => 'StripeCheckoutController@cancel',
//     'as' => 'stripe.checkout.cancel',
// ]);

// Route::get('plata/success', [
//     'uses' => 'StripeCheckoutController@success',
//     'as' => 'stripe.checkout.success',
// ]);

Route::get('test-demands', function () {

    $professionals = \App\Professional::all();

    $last_24_hours_demands = Demand::where('created_at', '>',
        Carbon::now()->subHours(24)->toDateTimeString()
    )->get();

    foreach ($professionals as $pro) {
        if ($pro->categories) { // check if pro has categories attached

            if ($pro->categories->count() > 0) {

                $pro_categories = $pro->categories;
                echo 'Profesionist: ' . $pro->user->email;
                echo '<br/>Categorii pro';
                echo '<pre>';
                echo $pro_categories;
                echo '</pre>';

                $unlocked_demands = [];

                foreach ($last_24_hours_demands as $demand) {

                    // $demand_categories = [];
                    // return $demand->categories()->some($pro_categories);

                    echo 'Categorii demand';
                    echo '<pre>';
                    echo $demand->categories;
                    echo '</pre>';

                    foreach ($demand->categories as $category) {
                        // verifica daca profesionistul contine vreo categorie din demand
                        if ($pro->categories->contains($category)) {
                            if (!$demand->hasBuyer($pro->user)) {
                                array_push($unlocked_demands, $demand);
                            }
                            break;
                        }
                    }

                }

                echo 'Demands nedeblocate <br/>';
                echo 'Numar cereri: ' . count($unlocked_demands);
                echo '<pre>';
                var_dump($unlocked_demands);
                echo '</pre>';

            }
        }
    }

});

// Route::get('test-notificare', function () {
//     // return 'notificare';

//     $code = rand(1000, 9999);

//     $demand_verification = new DemandVerification;
//     $demand_verification->uuid = Str::uuid();
//     $demand_verification->code = $code;
//     $demand_verification->save();

//     return Notification::route('nexmo', '40756472072')->notify(new VerifyDemandNotification($code));

// });

// Route::get('server-name', function () {
//     return $_SERVER['SERVER_NAME'];
// });

Route::get('send-code', function () {

    $code = rand(1000, 9999);

    $demand_verification = new \App\PhoneVerification;
    $demand_verification->uuid = Str::uuid();
    $demand_verification->code = $code;
    $demand_verification->save();

    return Notification::route('nexmo', '40756472072')->notify(new VerifyDemandNotification($code));

    // return $demand_verification->uuid;
});

// get $demand_verification->uuid with $request. User inputs the CODE.
Route::get('verify-code/{request_id}/{code}', function ($request_id, $code) {

    $element = \App\PhoneVerification::where('uuid', $request_id)->first();

    if ($element) {
        if ($element->code == $code) {
            return 'cod valabil - success';
        } else {
            return 'cod invalid';
        }
    } else {
        return 'eroare';
    }
});

// Route::get('abt', function () {
//     return view('volgh.index');
// });

// Route::get('empty', function () {
//     return view('volgh.empty');
// });

// -- sfarsit test.

// lansare cerere
Route::get('lansare/cerere', [
    'uses' => 'PublicDemandsController@register',
    'as' => 'public.demands.register',
]);

Route::get('cerere/public/{uuid}/{unique}', [
    'uses' => 'PublicDemandsController@single',
    'as' => 'public.demands.single',
]);

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

// Route::get('/verifica-email', function () {
//     return 'verifica emailul';
// })->name('verifica.email');

Auth::routes(['verify' => true, 'register' => false]);

Route::group(['middleware' => ['auth', 'verified', 'checkUserStatus']], function () {

    // API KEYS
    // Announcements
    Route::get('chei-api', [
        'uses' => 'ApiKeyController@index',
        'as' => 'keys.index',
    ]);

    // Announcements
    Route::get('anunturi/tot', [
        'uses' => 'AnnouncementController@index',
        'as' => 'announcement.index',
    ]);

// Home - Dashboard
    Route::get('/start', 'HomeController@index')->name('home');

// Company reviews
    Route::get('recenzii/companii', [
        'uses' => 'CompanyReviewsController@index',
        'as' => 'company_reviews.index',
    ]);

    // Banners Anunturi

    // -- admin
    Route::get('publicitate/admin/banner', [
        'uses' => 'BannerController@index',
        'as' => 'advertising.banners.index',
    ]);

    Route::get('publicitate/admin/banner/creare', [
        'uses' => 'BannerController@create',
        'as' => 'advertising.banners.create',
    ]);

    Route::get('publicitate/admin/banner/detalii/{uuid}', [
        'uses' => 'BannerController@show',
        'as' => 'advertising.banners.show',
    ]);

    // -- end admin

    // stripe banner checkout
    Route::post('plata/banner/checkout/{banner_uuid}/{period_id}', [
        'uses' => 'StripeCheckoutController@checkout',
        'as' => 'stripe.checkout',
    ]);

    Route::get('publicitate/banner', [
        'uses' => 'BannerPersonalController@index',
        'as' => 'advertising.banners.personal.index',
    ]);

    Route::get('publicitate/banner/creare', [
        'uses' => 'BannerPersonalController@create',
        'as' => 'advertising.banners.personal.create',
    ]);

    Route::get('publicitate/banner/detalii/{uuid}', [
        'uses' => 'BannerPersonalController@show',
        'as' => 'advertising.banners.personal.show',
    ]);

    Route::get('publicitate/banner/detalii/{uuid}/activare', [
        'uses' => 'BannerPersonalController@activate',
        'as' => 'advertising.banners.personal.activate',
    ]);

    Route::get('publicitate/banner/{banner_uuid}/succes', [
        'uses' => 'BannerPersonalController@success_payment_banner',
        'as' => 'advertising.banners.personal.success.payment',
    ]);

    Route::get('publicitate/banner/detalii/{banner_uuid}/succes/{period_id}', [
        'uses' => 'BannerPersonalController@success',
        'as' => 'advertising.banners.personal.success',
    ]);

    // Periods

    // Banners Anunturi
    Route::get('publicitate/perioade', [
        'uses' => 'PeriodsController@index',
        'as' => 'advertising.periods.index',
    ]);

// Publicitate Anunturi firme recomandate

    // -- admin
    Route::get('publicitate/admin/anunturi-recomandate', [
        'uses' => 'AdRecommendController@index',
        'as' => 'advertising.admin.ad_recommend.index',
    ]);

    Route::get('publicitate/admin/anunturi-recomandate/creare', [
        'uses' => 'AdRecommendController@create',
        'as' => 'advertising.admin.ad_recommend.create',
    ]);

    Route::get('publicitate/admin/anunturi-recomandate/detalii/{uuid}', [
        'uses' => 'AdRecommendController@show',
        'as' => 'advertising.admin.ad_recommend.show',
    ]);

    // -- end admin

    // stripe anunturi checkout
    Route::post('plata/anunturi-recomandate/checkout/{ad_uuid}/{period_id}', [
        'uses' => 'StripeCheckoutController@checkout_ad_recommend',
        'as' => 'stripe.checkout.ad',
    ]);

    Route::get('publicitate/anunturi-recomandate', [
        'uses' => 'AdRecommendPersonalController@index',
        'as' => 'advertising.ad_recommend.personal.index',
    ]);

    Route::get('publicitate/anunturi-recomandate/creare', [
        'uses' => 'AdRecommendPersonalController@create',
        'as' => 'advertising.ad_recommend.personal.create',
    ]);

    Route::get('publicitate/anunturi-recomandate/detalii/{uuid}', [
        'uses' => 'AdRecommendPersonalController@show',
        'as' => 'advertising.ad_recommend.personal.show',
    ]);

    Route::get('publicitate/anunturi-recomandate/detalii/{uuid}/activare', [
        'uses' => 'AdRecommendPersonalController@activate',
        'as' => 'advertising.ad_recommend.personal.activate',
    ]);

    Route::get('publicitate/anunturi-recomandate/{ad_uuid}/succes', [
        'uses' => 'AdRecommendPersonalController@success_payment_banner',
        'as' => 'advertising.ad_recommend.personal.success.payment',
    ]);

    Route::get('publicitate/anunturi-recomandate/detalii/{ad_uuid}/succes/{period_id}', [
        'uses' => 'AdRecommendPersonalController@success',
        'as' => 'advertising.ad_recommend.personal.success',
    ]);

    // end publicitate anunturi firme recomandate

// Demands

    // Route::get('demands/personal', [
    //     'uses' => 'DemandsController@personalDemands',
    //     'as' => 'demands.personal',
    // ]);

    // Route::get('demands/explore', [
    //     'uses' => 'DemandsController@explore',
    //     'as' => 'demands.explore',
    // ]);

    // Route::get('demands/explore/vue', [
    //     'uses' => 'DemandsController@exploreVue',
    //     'as' => 'demands.explore.vue',
    // ]);

    Route::get('cereri/explorare', [
        'uses' => 'DemandsController@exploreVueFinal',
        'as' => 'demands.explore.vue.final',
    ]);

    // Route::get('demands/explore/algolia', [
    //     'uses' => 'DemandsController@exploreAlgolia',
    //     'as' => 'demands.explore.algolia',
    // ]);

    // Route::get('demands/explore/results', [
    //     'uses' => 'DemandsController@exploreResults',
    //     'as' => 'demands.explore.results',
    // ]);

    // Route::get('cereri/deblocate', [
    //     'uses' => 'DemandsController@unlockedDemands',
    //     'as' => 'demands.unlocked',
    // ]);

    // Route::get('cereri/reclamate', [
    //     'uses' => 'DemandsController@reportedDemands',
    //     'as' => 'demands.reported',
    // ]);

    // Route::get('cereri/proprietar/{id}', [
    //     'uses' => 'DemandsController@showForOwner',
    //     'as' => 'demands.show.owner',
    // ]);

    // Demands pro
    Route::get('cereri/pro/detalii/{uuid}', [
        'uses' => 'DemandsController@showForProVue',
        'as' => 'demands.show.pro',
    ]);

    Route::get('cereri/pro/reclamate/toate', [
        'uses' => 'DemandsController@getReportedDemands',
        'as' => 'demands.pro.reported.list.all',
    ]);

    Route::get('cereri/pro/deblocate/toate', [
        'uses' => 'DemandsController@getUnlockedDemands',
        'as' => 'demands.pro.unlocked.list.all',
    ]);

    // end Demands pro

    // Route::get('cereri/explorare/id/{uuid}', [
    //     'uses' => 'DemandsController@showUUIDNotPro',
    //     'as' => 'demands.show.not.pro',
    // ]);

    // Route::get('cereri/proprietar/id/{uuid}', [
    //     'uses' => 'DemandsController@showForOwnerUUID',
    //     'as' => 'demands.show.owner.uuid',
    // ]);

    // Route::delete('cereri/id/{uuid}', [
    //     'uses' => 'DemandsController@destroyUUID',
    //     'as' => 'demands.destroy.uuid',
    // ]);

    // Route::get('cereri/id/{uuid}', [
    //     'uses' => 'DemandsController@showUUID',
    //     'as' => 'demands.show.uuid',
    // ]);

    // Route::post('cereri/{demand}/quote', [
    //     'uses' => 'DemandQuotesController@sendQuoteTo',
    //     'as' => 'demands.sendQuote',
    // ]);

    // Route::post('demands/{demand}/quote', [
    //     'uses' => 'QuotesController@store',
    //     'as' => 'demand.quote.store',
    // ]);

    // Route::post('demands/{demand}/quote/many/{timeline}', [
    //     'uses' => 'QuotesController@storeMany',
    //     'as' => 'demand.quote.store.many',
    // ]);

    // Route::post('demands/{demand}/quote/message/many/{timeline}', [
    //     'uses' => 'QuotesController@storeMessageMany',
    //     'as' => 'demand.quote.store.message.many',
    // ]);

    // Route::post('demands/{demand}/buy', [
    //     'uses' => 'DemandsController@buyDemand',
    //     'as' => 'demands.buy',
    // ]);

    // Route::post('demands/id/{uuid}/buy', [
    //     'uses' => 'DemandsController@buyDemandUUID',
    //     'as' => 'demands.buy.uuid',
    // ]);

    // Route::put('demands/{demand}/status', [
    //     'uses' => 'DemandsController@changeStatus',
    //     'as' => 'demands.changeStatus',
    // ]);

    // Route::put('demands/id/{uuid}/status', [
    //     'uses' => 'DemandsController@changeStatusUUID',
    //     'as' => 'demands.changeStatus.uuid',
    // ]);

    // Route::put('demands/{id}/relauch', [
    //     'uses' => 'DemandsController@relaunch',
    //     'as' => 'demands.relaunch',
    // ]);

    // Route::put('demands/id/{id}/relauch', [
    //     'uses' => 'DemandsController@relaunchUUID',
    //     'as' => 'demands.relaunch.uuid',
    // ]);

    // Route::put('demands/{demand_id}/professional/{professional_id}/timeline/{timeline_id}', [
    //     'uses' => 'DemandsController@markAsProspect',
    //     'as' => 'demands.prospect',
    // ]);

    // Route::put('demands/{demand_id}/winner/{professional_id}', [
    //     'uses' => 'DemandsController@markAsWinner',
    //     'as' => 'demands.winner',
    // ]);

    // Route::put('demands/{demand_id}/{prospect_id}/winner/{professional_id}', [
    //     'uses' => 'DemandsController@confirmWinner',
    //     'as' => 'demands.winner.confirm',
    // ]);

    // Route::put('demands/{demand_id}/prospect/{prospect_id}', [
    //     'uses' => 'DemandsController@refuseWinner',
    //     'as' => 'demands.prospect.refuse',
    // ]);

    // Route::put('demands/{demand_id}/winner/new/{professional_id}', [
    //     'uses' => 'DemandsController@markAsNewWinner',
    //     'as' => 'demands.winner.new',
    // ]);

    // Route::put('demands/{demand_id}/accept/{prospect_id}', [
    //     'uses' => 'DemandsController@proAcceptDemand',
    //     'as' => 'demands.pro.accept',
    // ]);

    // Route::put('demands/{demand_id}/refuse/{prospect_id}', [
    //     'uses' => 'DemandsController@proRefuseDemand',
    //     'as' => 'demands.pro.refuse',
    // ]);

    // Route::get('demands/client/all', [
    //     'uses' => 'DemandsController@clientIndex',
    //     'as' => 'demands.client.index',
    // ]);

    // Route::get('demands/register', [
    //     'uses' => 'DemandsController@register',
    //     'as' => 'demands.register',
    // ]);

    // Route::get('demands/register/vue', [
    //     'uses' => 'DemandsController@registerVue',
    //     'as' => 'demands.register.vue',
    // ]);

    // Route::put('demands/{demand}/change/state', [
    //     'uses' => 'DemandsController@changeState',
    //     'as' => 'demands.change.state',
    // ]);

    // Route::put('demands/id/{uuid}/change/state', [
    //     'uses' => 'DemandsController@changeStateUUID',
    //     'as' => 'demands.change.state.uuid',
    // ]);

    // Route::put('demands/{demand}/change/status/verified', [
    //     'uses' => 'DemandsController@changeStatusToVerified',
    //     'as' => 'demands.change.status.verified',
    // ]);

    // Route::put('demands/id/{uuid}/change/status/verified', [
    //     'uses' => 'DemandsController@changeStatusToVerifiedUUID',
    //     'as' => 'demands.change.status.verified.uuid',
    // ]);

    // Route::put('demands/{demand}/change/status/unverified', [
    //     'uses' => 'DemandsController@changeStatusToUnverified',
    //     'as' => 'demands.change.status.unverified',
    // ]);

    // Route::put('demands/id/{uuid}/change/status/unverified', [
    //     'uses' => 'DemandsController@changeStatusToUnverifiedUUID',
    //     'as' => 'demands.change.status.unverified.uuid',
    // ]);

    // Route::put('demands/{demand}/change/status/false', [
    //     'uses' => 'DemandsController@changeStatusToFalse',
    //     'as' => 'demands.change.status.false',
    // ]);

    // Route::put('demands/id/{uuid}/change/status/false', [
    //     'uses' => 'DemandsController@changeStatusToFalseUUID',
    //     'as' => 'demands.change.status.false.uuid',
    // ]);

    // Route::delete('demands/delete/{demand}/owner', [
    //     'uses' => 'DemandsController@destroyByOwner',
    //     'as' => 'demands.destroy.owner',
    // ]);

    // Route::delete('demands/delete/{demand}/owner/conversations', [
    //     'uses' => 'DemandsController@destroyByOwnerWithConversations',
    //     'as' => 'demands.destroy.owner.conversations',
    // ]);

    //ALL?
    // Route::resource('demands', 'DemandsController');

// DemandReportsController

    // Route::get('reports/demands', [
    //     'uses' => 'DemandReportsController@index',
    //     'as' => 'demands_reports.index',
    // ]);

    // Route::get('reports/{id}', [
    //     'uses' => 'DemandReportsController@show',
    //     'as' => 'demands_reports.show',
    // ]);

    // Route::get('reports/{id}/demand/create', [
    //     'uses' => 'DemandReportsController@create',
    //     'as' => 'demands_reports.create',
    // ]);

    // Route::post('reports/{id}/demand/store', [
    //     'uses' => 'DemandReportsController@store',
    //     'as' => 'demands_reports.store',
    // ]);

    // Route::put('reports/{id}/complete', [
    //     'uses' => 'DemandReportsController@complete',
    //     'as' => 'demands_reports.complete',
    // ]);

    // Route::put('reports/{id}/close', [
    //     'uses' => 'DemandReportsController@close',
    //     'as' => 'demands_reports.close',
    // ]);

// Route::resource('demand_reports', 'DemandReportsController');

// ClientMessageController

    // Route::post('client/messages/{demand_id}', [
    //     'uses' => 'ClientMessageController@storeMessage',
    //     'as' => 'client.messages.store',
    // ]);

    // Route::post('client/messages/{demand_id}/many', [
    //     'uses' => 'ClientMessageController@storeMessageMany',
    //     'as' => 'client.messages.store.many',
    // ]);

    // Route::resource('clientMessages', 'ClientMessageController');

// Files Controller

    Route::get('file/download/{type}/{file_name}', [
        'uses' => 'FilesController@download',
        'as' => 'files.download',
    ]);

// Notifications controller
    // Route::get('notifications/all', [
    //     'uses' => 'NotificationsController@index',
    //     'as' => 'notifications.all',
    // ]);

    Route::get('notificari/personal', [
        'uses' => 'NotificationsController@indexVue',
        'as' => 'notifications.all.vue',
    ]);

    // Route::get('notifications/messages/index', [
    //     'uses' => 'NotificationsController@indexMessages',
    //     'as' => 'notifications.messages',
    // ]);

    // Route::get('notifications/messages/index/second', [
    //     'uses' => 'NotificationsController@indexMessagesSecond',
    //     'as' => 'notifications.messages.second',
    // ]);

    // Route::get('notifications/settings', [
    //     'uses' => 'NotificationsController@settings',
    //     'as' => 'notifications.settings',
    // ]);

    // Route::get('notifications/{id}', [
    //     'uses' => 'NotificationsController@show',
    //     'as' => 'notifications.show',
    // ]);

    // Route::get('notifications/message/{id}', [
    //     'uses' => 'NotificationsController@showMessage',
    //     'as' => 'notifications.show.message',
    // ]);

    // Route::delete('notifications/{id}', [
    //     'uses' => 'NotificationsController@destroy',
    //     'as' => 'notifications.delete',
    // ]);

// Settings COntroller
    // DEZACTIVARE MODUL PROFEWIONIST.
    // Route::get('setari/modul/profesionist', [
    //     'uses' => 'SettingsController@pro_module',
    //     'as' => 'settings.pro.module',
    // ]);

// Quotes
    // Route::get('quotes/personal', [
    //     'uses' => 'QuotesController@personalQuotes',
    //     'as' => 'quotes.personal',
    // ]);

    // Route::resource('quotes', 'QuotesController')->except('store');

// POSTS???
    // Route::get('posts', [
    //     'uses' => 'PostController@index',
    //     'as' => 'posts.index',
    // ]);

    // Route::get('posts/create', [
    //     'uses' => 'PostController@create',
    //     'as' => 'posts.create',
    // ]);

    // Route::get('posts/{id}', 'PostController@show')->name('posts.show');

    // Route::post('posts', [
    //     'uses' => 'PostController@store',
    //     'as' => 'posts.store',
    // ]);

    // Route::get('posts/{post}/edit', [
    //     'uses' => 'PostController@edit',
    //     'as' => 'posts.edit',
    // ]);

    // Route::put('posts/{post}', [
    //     'uses' => 'PostController@update',
    //     'as' => 'posts.update',
    // ]);

    // Route::delete('posts/{post}', [
    //     'uses' => 'PostController@destroy',
    //     'as' => 'posts.delete',
    // ]);

    // Route::get('/contact', function () {
    //     return view('contact', ['param' => 'asadar']);
    // });

// // Users
    // Route::get('/users', function(){
    //     return \App\User::all();
    // });

// Route::get('/users/{id}', function($id){
    //     return \App\User::findOrFail($id);
    // });

// Profile
    // Route::get('profil', [
    //     'uses' => 'ProfileController@view',
    //     'as' => 'user.profile',
    // ]);

// de schimbat id cu username
    // Route::get('profil/profesionist/{id}', [
    //     'uses' => 'ProfileController@viewProProfile',
    //     'as' => 'user.pro.profile',
    // ]);

    // Route::get('profil/user/{id}', [
    //     'uses' => 'ProfileController@viewUserProfile',
    //     'as' => 'user.default.profile',
    // ])->middleware('role:admin');

    // Route::get('profil/user/editare/{id}', [
    //     'uses' => 'ProfileController@editUserProfile',
    //     'as' => 'user.admin.profile.edit',
    // ])->middleware('role:admin');

    // Route::get('profil/editare', [
    //     'uses' => 'ProfileController@edit',
    //     'as' => 'user.profile.edit',
    // ]);

    // Route::get('profil/editare/parola', [
    //     'uses' => 'ProfileController@edit_password',
    //     'as' => 'user.profile.edit.password',
    // ]);

    // Route::get('profil/editare/social', [
    //     'uses' => 'ProfileController@edit_socials',
    //     'as' => 'user.profile.edit.socials',
    // ]);

    // Route::get('profil/editare/personal', [
    //     'uses' => 'ProfileController@edit_personal',
    //     'as' => 'user.profile.edit.personal',
    // ]);

    Route::get('profil/recenzii', [
        'uses' => 'ProfileController@personal_reviews',
        'as' => 'user.profile.reviews.personal',
    ]);

    Route::get('profil/setari/personale', [
        'uses' => 'ProfileController@edit_personal_vue',
        'as' => 'user.profile.settings.personal',
    ]);

    // Route::get('profil/setari/pro', [
    //     'uses' => 'ProfileController@settings',
    //     'as' => 'user.profile.settings',
    // ]);

    // Route::get('profil/setari/pro/vue', [
    //     'uses' => 'ProfileController@settings_vue',
    //     'as' => 'user.profile.settings.vue',
    // ]);

    // Route::get('profil/setari/firma', [
    //     'uses' => 'ProfileController@company_profile',
    //     'as' => 'user.profile.company',
    // ]);

    Route::get('profil/setari/companie', [
        'uses' => 'ProfileController@settings_company',
        'as' => 'user.profile.settings.company',
    ]);

    Route::get('profil/setari/profesionist', [
        'uses' => 'ProfileController@settings_pro',
        'as' => 'user.profile.settings.pro',
    ]);

// Route::get('test-dirct', function(){
    //     if(!File::isDirectory(public_path('images/avatars/thumbnails'))){
    //         File::makeDirectory(public_path('images/avatars/thumbnails'), 0777, true, true);
    //     }
    // });

// Route::get('profile2', [
    //     'uses'  => 'ProfileController@view',
    //     'as'    => 'user.profile'
    // ]);

    // Route::put('profile/password/change', [
    //     'uses' => 'ProfileController@changePassword',
    //     'as' => 'user.password.change',
    // ]);

    // Route::put('profile/update', [
    //     'uses' => 'ProfileController@changeInformation',
    //     'as' => 'user.profile.update',
    // ]);

    // Route::put('profile/update/photo', [
    //     'uses' => 'ProfileController@changeProfilePhoto',
    //     'as' => 'user.profile.update.photo',
    // ]);

    // Route::delete('profile/photo/{id}', [
    //     'uses' => 'ProfileController@deletePhoto',
    //     'as' => 'profile.photo.delete',
    // ]);

    // Route::post('profile/company/save', [
    //     'uses' => 'ProfileController@saveCompanyProfile',
    //     'as' => 'profile.company.save',
    // ]);

    // Route::post('profile/user/{id}/company/save', [
    //     'uses' => 'ProfileController@saveUserCompanyProfile',
    //     'as' => 'profile.user.company.save',
    // ]);

// Invoices

    // Route::get('invoices/all', [
    //     'uses' => 'InvoicesController@stripe_invoices',
    //     'as' => 'invoices.all',
    // ]);

    // Route::get('invoices/download/{id}', [
    //     'uses' => 'InvoicesController@download',
    //     'as' => 'invoices.download',
    // ]);

    // Route::get('invoices/download/{id}/{charge_id}', [
    //     'uses' => 'InvoicesController@download_charge',
    //     'as' => 'invoices.download.charge',
    // ]);

    // Route::get('invoices/view/{invoice_id}', [
    //     'uses' => 'InvoicesController@show_invoice',
    //     'as' => 'invoices.show.invoice',
    // ]);

    // Route::get('invoices/{id}/charges/{charge_id}', [
    //     'uses' => 'InvoicesController@show_charge',
    //     'as' => 'invoices.show.charge',
    // ]);

    // Route::get('invoices/{id}/{customer_id}', [
    //     'uses' => 'InvoicesController@show',
    //     'as' => 'invoices.show',
    // ]);

    // Route::get('charges', [
    //     'uses' => 'ChargesController@index',
    //     'as' => 'charges.index',
    // ]);

    // Route::get('charges/{charge_id}', [
    //     'uses' => 'ChargesController@show',
    //     'as' => 'charges.show',
    // ]);

// SocialProfiles

    // Route::post('social/{id}/profiles/save', [
    //     'uses' => 'SocialProfileController@saveSocialProfiles',
    //     'as' => 'user.social.profiles.save',
    // ]);

// Work Projects

    // Route::post('work-projects/{project}/categories', [
    //     'uses' => 'WorkProjectsController@updateCategories',
    //     'as' => 'work-projects.update.categories',
    // ]);

    Route::get('proiecte-lucrari/personal', [
        'uses' => 'WorkProjectsController@personal',
        'as' => 'work-projects.personal',
    ]);

    Route::get('proiecte-lucrari/nou', [
        'uses' => 'WorkProjectsController@create',
        'as' => 'work-projects.new',
    ]);

    Route::get('proiecte-lucrari/{uuid}', [
        'uses' => 'WorkProjectsController@show',
        'as' => 'work-projects.show.uuid',
    ]);

    // Route::resource('work-projects', 'WorkProjectsController');

    // Route::resource('work-projects-photos', 'WorkProjectPhotoController');

// Work Project Categories

    Route::get('categorii-proiecte', [
        'uses' => 'WorkProjectCategoryController@index',
        'as' => 'work-project-categories.index',
    ]);

    Route::get('categorii-proiecte/{slug}', [
        'uses' => 'WorkProjectCategoryController@show',
        'as' => 'work-project-categories.show',
    ]);

    // Route::resource('categorii-proiecte', 'WorkProjectCategoryController');

// Categories
    Route::get('categorii/listare', [
        'uses' => 'CategoryController@indexVue',
        'as' => 'categories.index.vue',
    ]);

    Route::get('categorii/detalii/{slug}', [
        'uses' => 'CategoryController@showVue',
        'as' => 'categories.show.vue',
    ]);

    // Route::resource('categories', 'CategoryController');

// Credits

    // Route::post('credits/add', [
    //     'uses' => 'CreditsController@add',
    //     'as' => 'credits.add',
    // ]);

    // Route::get('credits', [
    //     'uses' => 'CreditsController@index',
    //     'as' => 'credits.index',
    // ]);

    // Route::get('credits/personal', [
    //     'uses' => 'CreditsController@personal',
    //     'as' => 'credits.personal',
    // ]);

    Route::get('credit/personal', [
        'uses' => 'CreditsController@simple',
        'as' => 'credits.simple',
    ]);

// Subscriptions

    // Route::resource('subscriptions', 'SubscriptionController');

// Notification Settings
    Route::get('setari/notificari', [
        'uses' => 'NotificationSettingsController@index',
        'as' => 'notification.settings.index',
    ]);

// Users

    // Route::get('cont/inactiv', function () {
    //     return view('volgh.users.inactive');
    // })->name('users.inactive')->withoutMiddleware([CheckUserStatus::class]);

    Route::post('users/{id}/permissions', [
        'uses' => 'UsersController@giveUserPermissions',
        'as' => 'users.permissions',
    ]);

    Route::post('users/{id}/permissions/reset', [
        'uses' => 'UsersController@resetUserPermissions',
        'as' => 'users.permissions.reset',
    ]);

    Route::post('users/{id}/roles', [
        'uses' => 'UsersController@giveUserRoles',
        'as' => 'users.roles',
    ]);

    Route::post('users/{id}/roles/reset', [
        'uses' => 'UsersController@resetUserRoles',
        'as' => 'users.roles.reset',
    ]);

    Route::put('users/{user}/admin/change/password/', [
        'uses' => 'UsersController@adminChangePassword',
        'as' => 'users.admin.ChangePassword',
    ]);

    Route::put('users/{user}/admin/update/profile/', [
        'uses' => 'UsersController@adminUpdateUserProfile',
        'as' => 'users.admin.updateProfile',
    ]);

    Route::put('users/{user}/admin/update/photo', [
        'uses' => 'UsersController@adminUpdateUserPhoto',
        'as' => 'users.admin.update.photo',
    ]);

    Route::delete('users/admin/delete/photo/{id}', [
        'uses' => 'UsersController@adminDeleteUserPhoto',
        'as' => 'users.admin.photo.delete',
    ]);

    Route::put('users/{user}/admin/update/subscription', [
        'uses' => 'UsersController@adminUpdateSubscription',
        'as' => 'users.admin.updateSubscription',
    ]);

    Route::put('users/{id}/change/status', [
        'uses' => 'UsersController@adminChangeStatus',
        'as' => 'users.admin.change.status',
    ]);

    Route::get('users/create/new', [
        'uses' => 'UsersController@adminCreateNewUserView',
        'as' => 'users.admin.create.new.view',
    ]);

    Route::post('users/create/new', [
        'uses' => 'UsersController@adminCreateNewUser',
        'as' => 'users.admin.create.new',
    ]);

    Route::get('users/all', [
        'uses' => 'UsersController@all',
        'as' => 'users.all',
    ]);

    Route::get('users/all/pros', [
        'uses' => 'UsersController@allPros',
        'as' => 'users.all.pros',
    ]);

    Route::get('users/admin/show/{id}', [
        'uses' => 'UsersController@showForAdmin',
        'as' => 'users.show.for.admin',
    ]);

    Route::resource('users', 'UsersController');

    // Inactive Controller

    Route::get('cont/inactiv', [
        'uses' => 'InactiveController@inactive',
        'as' => 'users.inactive',
    ])->withoutMiddleware([CheckUserStatus::class]);

// Professionals

    // Route::post('professionals/activate', [
    //     'uses' => 'ProfessionalsController@activate',
    //     'as' => 'professionals.activate',
    // ]);

    // Route::put('professionals/update/pro', [
    //     'uses' => 'ProfessionalsController@updatePro',
    //     'as' => 'professionals.updatePro',
    // ]);

    // Route::put('professionals/update/categories', [
    //     'uses' => 'ProfessionalsController@updateCategories',
    //     'as' => 'professionals.update.categories',
    // ]);

    // Route::post('professionals/detach/categories', [
    //     'uses' => 'ProfessionalsController@eliminateCategories',
    //     'as' => 'professionals.detach.categories',
    // ]);

    // Route::resource('professionals', 'ProfessionalsController');

// Payments

    // Route::get('payments/show/{id}', [
    //     'uses' => 'PaymentsController@show_payment',
    //     'as' => 'payments.show.single',
    // ]);

    // Route::get('payments/single-later', [
    //     'uses' => 'PaymentsController@singleViewLater',
    //     'as' => 'payments.single.later',
    // ]);

    // Route::post('payments/single-later/checkout', [
    //     'uses' => 'PaymentsController@singleLaterCheckout',
    //     'as' => 'payments.single-later.checkout',
    // ]);

    // Route::get('payments/single', [
    //     'uses' => 'PaymentsController@singleView',
    //     'as' => 'payments.single',
    // ]);

    // Route::post('payments/single/checkout', [
    //     'uses' => 'PaymentsController@singleCheckout',
    //     'as' => 'payments.single.checkout',
    // ]);

    // admin

    // invoice
    Route::get('facturi/utilizatori', [
        'uses' => 'InvoicesController@index',
        'as' => 'invoices.index',
    ]);

    Route::get('plati/utilizatori', [
        'uses' => 'PaymentsController@all',
        'as' => 'payments.all',
    ]);

    Route::get('plati/detalii/{uuid}', [
        'uses' => 'PaymentsController@show',
        'as' => 'payments.show.single',
    ]);
    // end admin

    Route::get('facturare', [
        'uses' => 'PaymentsController@index',
        'as' => 'payments.index',
    ]);

    Route::get('facturare/descarca/factura/{uuid}', [
        'uses' => 'PaymentsController@download',
        'as' => 'payments.download.invoice',
    ]);

    // Route::resource('payments', 'PaymentsController');

// Refunds Demands

    // Route::put('refundsdemands/mark/approve/{id}', [
    //     'uses' => 'RefundsDemandsController@markApprove',
    //     'as' => 'refundsdemands.mark.approve',
    // ]);

    // Route::put('refundsdemands/mark/deny/{id}', [
    //     'uses' => 'RefundsDemandsController@markDeny',
    //     'as' => 'refundsdemands.mark.deny',
    // ]);

    // Route::resource('refundsdemands', 'RefundsDemandsController');

// Roles
    // Route::post('roluri/{id}/permisiuni', [
    //     'uses' => 'RolesController@giveRolePermissions',
    //     'as' => 'roles.permissions',
    // ]);

    // Route::post('roluri/{id}/permisiuni/resetare', [
    //     'uses' => 'RolesController@resetRolePermissions',
    //     'as' => 'roles.permissions.reset',
    // ]);

    // Route::resource('roluri', 'RolesController');

    Route::get('roluri', [
        'uses' => 'RolesController@index',
        'as' => 'roles.index',
    ]);

    Route::get('roluri/{name}', [
        'uses' => 'RolesController@showByName',
        'as' => 'roles.show',
    ]);

    Route::delete('roluri/{id}', [
        'uses' => 'RolesController@destroy',
        'as' => 'roles.destroy',
    ]);

// Permissions

    // Route::resource('permissions', 'PermissionsController');

// Reviews

    // Route::post('reviews/save/{demand_id}', [
    //     'uses' => 'ReviewsController@saveNew',
    //     'as' => 'reviews.save',
    // ]);

    Route::get('reviews/all', [
        'uses' => 'ReviewsController@all',
        'as' => 'reviews.all',
    ]);

    Route::get('reviews/reported', [
        'uses' => 'ReviewsController@reported',
        'as' => 'reviews.reported',
    ]);

    // Route::resource('reviews', 'ReviewsController');

// Tickets

    // Route::post('tickets/{id}/respond', [
    //     'uses' => 'TicketsController@respond',
    //     'as' => 'tickets.respond',
    // ]);

    // Route::post('tickets/{id}/respond/many', [
    //     'uses' => 'TicketsController@respondMany',
    //     'as' => 'tickets.respond.many',
    // ]);

    // Route::put('tickets/{id}/changeStatus', [
    //     'uses' => 'TicketsController@changeStatus',
    //     'as' => 'tickets.changeStatus',
    // ]);

    // Route::post('tickets/store/many', [
    //     'uses' => 'TicketsController@storeMany',
    //     'as' => 'tickets.store.many',
    // ]);

    // Route::post('tickets/id/{uuid}/respond', [
    //     'uses' => 'TicketsController@respondUUID',
    //     'as' => 'tickets.respond.uuid',
    // ]);

    // Route::post('tickets/id/{uuid}/respond/many', [
    //     'uses' => 'TicketsController@respondManyUUID',
    //     'as' => 'tickets.respond.many.uuid',
    // ]);

    // Route::put('tickets/id/{uuid}/changeStatus', [
    //     'uses' => 'TicketsController@changeStatusUUID',
    //     'as' => 'tickets.changeStatus.uuid',
    // ]);

    // Route::get('tickets/id/{uuid}', [
    //     'uses' => 'TicketsController@showUUID',
    //     'as' => 'tickets.show.uuid',
    // ]);

    // Route::get('tichete/detalii/id/{uuid}', [
    //     'uses' => 'TicketsController@showVue',
    //     'as' => 'tickets.show.vue.uuid',
    // ]);

    // Route::delete('tickets/id/{uuid}', [
    //     'uses' => 'TicketsController@destroyUUID',
    //     'as' => 'tickets.destroy.uuid',
    // ]);

    // Route::post('tickets/resolve/{id}', [
    //     'uses' => 'TicketsController@resolve',
    //     'as' => 'tickets.resolve',
    // ]);

    // Route::post('tickets/delegate/{ticket_id}/user/{user_id}', [
    //     'uses' => 'TicketsController@delegate',
    //     'as' => 'tickets.delegate.user',
    // ]);

    // Route::post('tickets/subscribing/{uuid}', [
    //     'uses' => 'TicketsController@subscribing',
    //     'as' => 'tickets.subscribing.single',
    // ]);

    // Route::get('tichete/listare', [
    //     'uses' => 'TicketsController@list',
    //     'as' => 'tickets.list.all',
    // ]);

    // Route::get('tichete/inregistrare/nou', [
    //     'uses' => 'TicketsController@createVue',
    //     'as' => 'tickets.create.vue',
    // ]);

    // Route::resource('tickets', 'TicketsController');

    // Help
    Route::get('ajutor', [
        'uses' => 'HelpController@index',
        'as' => 'help.index',
    ]);

// TImeline

    // Route::get('timeline/view/{id}', [
    //     'uses' => 'TimelineController@showById',
    //     'as' => 'timeline.showById',
    // ]);

    // Route::get('timeline/pro/{id}', [
    //     'uses' => 'TimelineController@showByIdForPro',
    //     'as' => 'timeline.show.pro',
    // ]);

    // Route::get('timeline/client/{id}', [
    //     'uses' => 'TimelineController@showByIdForClient',
    //     'as' => 'timeline.show.client',
    // ]);

    // Route::get('timeline/pro/id/{uuid}', [
    //     'uses' => 'TimelineController@showByIdForProUUID',
    //     'as' => 'timeline.show.pro.uuid',
    // ]);

    // Route::get('timeline/client/id/{uuid}', [
    //     'uses' => 'TimelineController@showByIdForClientUUID',
    //     'as' => 'timeline.show.client.uuid',
    // ]);

    // Route::get('timelines/pro', [
    //     'uses' => 'TimelineController@indexPro',
    //     'as' => 'timeline.index.pro',
    // ]);

    // Route::get('timelines/client', [
    //     'uses' => 'TimelineController@indexClient',
    //     'as' => 'timeline.index.client',
    // ]);

    // Route::put('timelines/{id}/status/change', [
    //     'uses' => 'TimelineController@changeStatus',
    //     'as' => 'timeline.change.status',
    // ]);

    // Route::put('timelines/id/{id}/status/change', [
    //     'uses' => 'TimelineController@changeStatusUUID',
    //     'as' => 'timeline.change.status.uuid',
    // ]);

    // Route::delete('timelines/{id}/delete/client', [
    //     'uses' => 'TimelineController@deleteByClient',
    //     'as' => 'timeline.delete.client',
    // ]);

    // Route::delete('timelines/{id}/delete/pro', [
    //     'uses' => 'TimelineController@deleteByPro',
    //     'as' => 'timeline.delete.pro',
    // ]);

    // Route::resource('timeline', 'TimelineController');

    // AdminController
    Route::get('admin/demands/all', [
        'uses' => 'AdminController@getDemands',
        'as' => 'admin.demands.list.all',
    ]);

    Route::get('admin/demands/reported/all', [
        'uses' => 'AdminController@getReportedDemands',
        'as' => 'admin.demands.reported.list.all',
    ]);

    Route::get('admin/demands/show/{uuid}', [
        'uses' => 'AdminController@showDemand',
        'as' => 'admin.demands.show',
    ]);

    // CouponsController

    Route::get('cupoane', [
        'uses' => 'CouponsController@index',
        'as' => 'coupons.index',
    ]);

    Route::get('cupoane/personal', [
        'uses' => 'CouponsController@personal',
        'as' => 'coupons.personal',
    ]);

    Route::get('cupoane/solicitari', [
        'uses' => 'CouponsController@requests',
        'as' => 'coupons.requests',
    ]);

    Route::get('cupoane/detalii/{id}', [
        'uses' => 'CouponsController@show_pro',
        'as' => 'coupons.show.pro',
    ]);

    Route::get('cupoane/{uuid}', [
        'uses' => 'CouponsController@show',
        'as' => 'coupons.show',
    ]);

    // for admin
    Route::get('cupoane/toate/solicitari', [
        'uses' => 'CouponsController@all_requests',
        'as' => 'coupons.requests.all',
    ]);

    // ActivityController

    Route::get('activitate/personal', [
        'uses' => 'ActivityController@personal',
        'as' => 'activities.personal',
    ]);

    // Companies COntroller

    Route::get('companii/cereri-inscriere', [
        'uses' => 'CompaniesController@pending',
        'as' => 'companies.pending',
    ]);

    Route::get('companii/creare', [
        'uses' => 'CompaniesController@create',
        'as' => 'companies.create',
    ]);

    Route::get('companii/detalii/{id}', [
        'uses' => 'CompaniesController@details',
        'as' => 'companies.details.single',
    ]);

}); // end middleware CheckUserStatus

// for inactive users.
// Route::get('tichete/afisare/tot', [
//     'uses' => 'TicketsInactiveController@getAll',
//     'as' => 'tickets.get.all',
// ]);

// Route::get('tichete/creare/nou', [
//     'uses' => 'TicketsInactiveController@createNew',
//     'as' => 'tickets.create.new',
// ]);

// Route::get('tichete/detalii/{uuid}', [
//     'uses' => 'TicketsInactiveController@show',
//     'as' => 'tickets.details.uuid',
// ]);

// Route::post('tichete/salvare/nou', [
//     'uses' => 'TicketsInactiveController@storeNew',
//     'as' => 'tickets.store.new',
// ]);

/**
 * Emails
 */

//  Route::get('emails/contact', [
//      'uses' => 'ContactController@show',
//      'as'   => 'emails.contact'
//  ]);

//  Route::post('emails/send', [
//      'uses' => 'ContactController@store',
//      'as'   => 'emails.send'
//  ]);

//  Route::get('notifications', [
//      'uses' => 'UserNotificationsController@index',
//      'as'   => 'notifications.index'
//  ]);

// Register User
// Route::get('inregistrare/firma', [
//     'uses' => 'RegisterController@register_pro',
//     'as' => 'register.show.pro',
// ]);
// Route::post('inregistrare/firma', [
//     'uses' => 'RegisterController@store_pro',
//     'as' => 'register.store.pro',
// ]);

// Public
Route::get('public/profil/profesionist/{username}', [
    'uses' => 'PublicProfessionalController@profile',
    'as' => 'public.professional.profile',
]);

Route::get('public/profil/profesionist/{username}/proiecte/{uuid}', [
    'uses' => 'PublicProjectsController@get',
    'as' => 'public.project.show',
]);

// Route::get('/insert-json-file-to-database-table', function () {
//     $json = file_get_contents(storage_path('localitati-3.json'));
//     $objs = json_decode($json, true);
//     foreach ($objs as $obj) {
//         foreach ($obj as $key => $value) {
//             $insertArr[str_slug($key, '_')] = $value;
//         }
//         DB::table('localitates')->insert($insertArr);
//     }
//     dd("Finished adding data in examples table");
// });
