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

// Route::get('/test-open/{cui}', function (Request $request, $cui) {
//     // return $request->user();

//     $url = "https://api.openapi.ro/api/companies/" . $cui;

//     $response = \Illuminate\Support\Facades\Http::withHeaders([
//         'x-api-key' => config('services.openapi.api_key'),
//         'Access-Control-Allow-Origin' => '*',
//         'Accept' => 'application/json',
//         'Content-Type' => 'application/json',
//     ])->get($url);

//     return response()->json(json_decode($response));

// });

Route::get('test/host/name', function () {
    // return response()->json(['$_SERVER' => $_SERVER['SERVER_NAME'], 'request()' => request()->header(), '$request->header("X-Authorization")' => request()->header('X-Authorization')]);
    $host = parse_url(request()->headers->get('referer'), PHP_URL_HOST);
    return response()->json(['host' => $host]);
});

// Api Key
Route::middleware('auth:api')->get('keys', [
    'uses' => 'API\ApiKeyController@index',
    'as' => 'api.keys.all',
]);

Route::middleware('auth:api')->post('keys/store', [
    'uses' => 'API\ApiKeyController@store',
    'as' => 'api.keys.store',
]);

Route::middleware('auth:api')->post('keys/delete/{id}', [
    'uses' => 'API\ApiKeyController@delete',
    'as' => 'api.keys.delete',
]);

// end Api Key

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/auth/user', function () {
    try {
        $user = auth()->userOrFail();
        $user['is_pro'] = $user->isPro();
        $user['is_admin'] = $user->isAdmin();
        $user['complete_name'] = $user->getTheName();
        // $ticket = \App\Ticket::findOrFail($id);
    } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
        return response()->json(['error' => $e->getMessage()]);
    }

    return response()->json(['auth_user' => $user]);
});

Route::get('notifications/count', [
    'uses' => 'API\NotificationsController@count',
    'as' => 'api.notifications.count',
]);

Route::get('notifications/load/personal', [
    'uses' => 'API\NotificationsController@loadPersonal',
    'as' => 'api.notifications.load.personal',
]);

Route::get('notifications/personal_paginated/{id}', [
    'uses' => 'API\NotificationsController@personalPaginated',
    'as' => 'api.notifications.personalPaginated',
]);

Route::get('notifications/personal', [
    'uses' => 'API\NotificationsController@personal',
    'as' => 'api.notifications.personal',
]);

Route::get('notifications/total_personal', [
    'uses' => 'API\NotificationsController@totalPersonal',
    'as' => 'api.notifications.personal.total',
]);

Route::get('notifications/total_personal_paginated/{page}', [
    'uses' => 'API\NotificationsController@totalPersonalPaginated',
    'as' => 'api.notifications.personal.total.paginated',
]);

Route::post('notifications/markAsRead/{id}', [
    'uses' => 'API\NotificationsController@markAsRead',
    'as' => 'api.notifications.markAsRead',
]);

Route::post('notifications/markAsReadSelected', [
    'uses' => 'API\NotificationsController@markAsReadSelected',
    'as' => 'api.notifications.markAsReadSelected',
]);

Route::post('notifications/deleteSelected', [
    'uses' => 'API\NotificationsController@deleteSelected',
    'as' => 'api.notifications.deleteSelected',
]);

Route::post('notifications/destroy/{id}', [
    'uses' => 'API\NotificationsController@destroy',
    'as' => 'api.notifications.destroy',
]);

// Notification Settings
Route::middleware('auth:api')->get('notification/settings', [
    'uses' => 'API\NotificationSettingsController@index',
    'as' => 'api.notification.settings.index',
]);

Route::middleware('auth:api')->post('notification/settings/toggle', [
    'uses' => 'API\NotificationSettingsController@toggle',
    'as' => 'api.notification.settings.toggle',
]);

// Advertising
Route::middleware('auth:api')->get('periods/all', [
    'uses' => 'API\PeriodsController@index',
    'as' => 'api.periods.index',
]);

Route::middleware('auth:api')->get('periods/get/client', [
    'uses' => 'API\PeriodsController@client',
    'as' => 'api.periods.get.client',
]);

Route::middleware('auth:api')->post('periods/store', [
    'uses' => 'API\PeriodsController@store',
    'as' => 'api.periods.store',
]);

Route::middleware('auth:api')->post('periods/update/{id}', [
    'uses' => 'API\PeriodsController@update',
    'as' => 'api.periods.update',
]);

Route::middleware('auth:api')->delete('periods/delete/{id}', [
    'uses' => 'API\PeriodsController@delete',
    'as' => 'api.periods.delete',
]);

// Banners Controller

Route::get('banners/all', [
    'uses' => 'API\BannersController@index',
    'as' => 'api.banners.index',
]);

Route::get('banners/get/{slug}', [
    'uses' => 'API\BannersController@getByCategory',
    'as' => 'api.banners.get.category',
]);

Route::post('banners/send/direct/message', [
    'uses' => 'API\BannersController@sendFormMessage',
    'as' => 'api.banners.send.direct.message',
]);

// end public banner

Route::middleware('auth:api')->get('banners/single/{uuid}', [
    'uses' => 'API\BannersController@getSingleBanner',
    'as' => 'api.banners.get.single',
]);

Route::get('banners/public/single/{uuid}', [
    'uses' => 'API\BannersController@getPublicSingleBanner',
    'as' => 'api.banners.get.public.single',
]);

Route::middleware('auth:api')->get('banners/load/{type}', [
    'uses' => 'API\BannersController@load',
    'as' => 'api.banners.load',
]);

Route::middleware('auth:api')->get('banners/processing/get', [
    'uses' => 'API\BannersController@loadProcessing',
    'as' => 'api.banners.processing.load',
]);

Route::middleware('auth:api')->post('banners/store', [
    'uses' => 'API\BannersController@store',
    'as' => 'api.banners.store',
]);

Route::middleware('auth:api')->post('banners/update_announce/{id}', [
    'uses' => 'API\BannersController@update_announce',
    'as' => 'api.banners.update_announce',
]);

Route::middleware('auth:api')->post('banners/update_announce_image/{id}', [
    'uses' => 'API\BannersController@update_announce_image',
    'as' => 'api.banners.update_announce_image',
]);

Route::middleware('auth:api')->post('banners/update_announce_options/{id}', [
    'uses' => 'API\BannersController@update_announce_options',
    'as' => 'api.banners.update_announce_options',
]);

Route::middleware('auth:api')->post('banners/update_announce_period/{id}', [
    'uses' => 'API\BannersController@update_announce_period',
    'as' => 'api.banners.update_announce_period',
]);

Route::middleware('auth:api')->post('banners/update_announce_categories/{id}', [
    'uses' => 'API\BannersController@update_announce_categories',
    'as' => 'api.banners.update_announce_categories',
]);

Route::middleware('auth:api')->delete('banners/delete/{id}', [
    'uses' => 'API\BannersController@delete',
    'as' => 'api.banners.delete',
]);

Route::middleware('auth:api')->post('banners/activate/{uuid}', [
    'uses' => 'API\BannersController@activate',
    'as' => 'api.banners.activate',
]);

Route::middleware('auth:api')->post('banners/reject/{uuid}', [
    'uses' => 'API\BannersController@reject',
    'as' => 'api.banners.reject',
]);

// BannersPersonalController
Route::middleware('auth:api')->get('banners/personal/get/all', [
    'uses' => 'API\BannersPersonalController@personal',
    'as' => 'api.banners.personal.get.all',
]);

Route::middleware('auth:api')->get('banners/personal/single/{uuid}', [
    'uses' => 'API\BannersPersonalController@getSingleBanner',
    'as' => 'api.banners.personal.get.single',
]);

Route::middleware('auth:api')->post('banners/personal/store', [
    'uses' => 'API\BannersPersonalController@store',
    'as' => 'api.banners.personal.store',
]);

Route::middleware('auth:api')->delete('banners/personal/delete/{id}', [
    'uses' => 'API\BannersPersonalController@delete',
    'as' => 'api.banners.personal.delete',
]);

Route::middleware('auth:api')->post('banners/personal/update_announce/{id}', [
    'uses' => 'API\BannersPersonalController@update_announce',
    'as' => 'api.banners.personal.update_announce',
]);

Route::middleware('auth:api')->post('banners/personal/update_announce_image/{id}', [
    'uses' => 'API\BannersPersonalController@update_announce_image',
    'as' => 'api.banners.personal.update_announce_image',
]);

Route::middleware('auth:api')->post('banners/personal/update_announce_options/{id}', [
    'uses' => 'API\BannersPersonalController@update_announce_options',
    'as' => 'api.banners.personal.update_announce_options',
]);

Route::middleware('auth:api')->post('banners/personal/update_announce_period/{id}', [
    'uses' => 'API\BannersPersonalController@update_announce_period',
    'as' => 'api.banners.personal.update_announce_period',
]);

Route::middleware('auth:api')->post('banners/personal/update_announce_categories/{id}', [
    'uses' => 'API\BannersPersonalController@update_announce_categories',
    'as' => 'api.banners.personal.update_announce_categories',
]);

Route::middleware('auth:api')->post('banners/personal/activate/{uuid}', [
    'uses' => 'API\BannersPersonalController@activate',
    'as' => 'api.banners.personal.activate.single',
]);

Route::middleware('auth:api')->post('banners/personal/request/validation/{uuid}', [
    'uses' => 'API\BannersPersonalController@requestValidation',
    'as' => 'api.banners.personal.request.validation',
]);

Route::middleware('auth:api')->post('banners/personal/calculate/{banner_uuid}/{period_id}', [
    'uses' => 'API\BannersPersonalController@calculate',
    'as' => 'api.banners.personal.calculate.cost',
]);

// Route::middleware('auth:api')->post('banners/personal/plata/{banner_uuid}/{period_id}', [
//     'uses' => 'API\StripeController@checkout',
//     'as' => 'api.banners.personal.payment.stripe',
// ]);

// -- Start ads recommend company

// Ads recommend Controller

Route::get('ads_recommend/all', [
    'uses' => 'API\AdsRecommendController@index',
    'as' => 'api.ads_recommend.index',
]);

Route::get('ads_recommend/get/{slug}', [
    'uses' => 'API\AdsRecommendController@getByCategory',
    'as' => 'api.ads_recommend.get.category',
]);

// used this or ad & banner form send message
Route::post('ads_recommend/send/direct/message', [
    'uses' => 'API\AdsRecommendController@sendFormMessage',
    'as' => 'api.ads_recommend.send.direct.message',
]);

// end public

Route::middleware('auth:api')->get('ads_recommend/single/{uuid}', [
    'uses' => 'API\AdsRecommendController@getSingleAd',
    'as' => 'api.ads_recommend.get.single',
]);

Route::get('ads_recommend/public/single/{uuid}', [
    'uses' => 'API\AdsRecommendController@getPublicSingleAd',
    'as' => 'api.ads_recommend.get.public.single',
]);

Route::middleware('auth:api')->get('ads_recommend/load/{type}', [
    'uses' => 'API\AdsRecommendController@load',
    'as' => 'api.ads_recommend.load',
]);

Route::middleware('auth:api')->get('ads_recommend/processing/get', [
    'uses' => 'API\AdsRecommendController@loadProcessing',
    'as' => 'api.ads_recommend.processing.load',
]);

Route::middleware('auth:api')->post('ads_recommend/store', [
    'uses' => 'API\AdsRecommendController@store',
    'as' => 'api.ads_recommend.store',
]);

Route::middleware('auth:api')->post('ads_recommend/update_announce/{id}', [
    'uses' => 'API\AdsRecommendController@update_announce',
    'as' => 'api.ads_recommend.update_announce',
]);

Route::middleware('auth:api')->post('ads_recommend/update_announce_options/{id}', [
    'uses' => 'API\AdsRecommendController@update_announce_options',
    'as' => 'api.ads_recommend.update_announce_options',
]);

Route::middleware('auth:api')->post('ads_recommend/update_announce_period/{id}', [
    'uses' => 'API\AdsRecommendController@update_announce_period',
    'as' => 'api.ads_recommend.update_announce_period',
]);

Route::middleware('auth:api')->post('ads_recommend/update_announce_categories/{id}', [
    'uses' => 'API\AdsRecommendController@update_announce_categories',
    'as' => 'api.ads_recommend.update_announce_categories',
]);

Route::middleware('auth:api')->delete('ads_recommend/delete/{id}', [
    'uses' => 'API\AdsRecommendController@delete',
    'as' => 'api.ads_recommend.delete',
]);

Route::middleware('auth:api')->post('ads_recommend/activate/{uuid}', [
    'uses' => 'API\AdsRecommendController@activate',
    'as' => 'api.ads_recommend.activate',
]);

Route::middleware('auth:api')->post('ads_recommend/reject/{uuid}', [
    'uses' => 'API\AdsRecommendController@reject',
    'as' => 'api.ads_recommend.reject',
]);

// AdRecommendPersonalController
Route::middleware('auth:api')->get('ads_recommend/personal/get/all', [
    'uses' => 'API\AdsRecommendPersonalController@personal',
    'as' => 'api.ads_recommend.personal.get.all',
]);

Route::middleware('auth:api')->get('ads_recommend/personal/single/{uuid}', [
    'uses' => 'API\AdsRecommendPersonalController@getSingleAd',
    'as' => 'api.ads_recommend.personal.get.single',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/store', [
    'uses' => 'API\AdsRecommendPersonalController@store',
    'as' => 'api.ads_recommend.personal.store',
]);

Route::middleware('auth:api')->delete('ads_recommend/personal/delete/{id}', [
    'uses' => 'API\AdsRecommendPersonalController@delete',
    'as' => 'api.ads_recommend.personal.delete',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/update_announce/{id}', [
    'uses' => 'API\AdsRecommendPersonalController@update_announce',
    'as' => 'api.ads_recommend.personal.update_announce',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/update_announce_options/{id}', [
    'uses' => 'API\AdsRecommendPersonalController@update_announce_options',
    'as' => 'api.ads_recommend.personal.update_announce_options',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/update_announce_period/{id}', [
    'uses' => 'API\AdsRecommendPersonalController@update_announce_period',
    'as' => 'api.ads_recommend.personal.update_announce_period',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/update_announce_categories/{id}', [
    'uses' => 'API\AdsRecommendPersonalController@update_announce_categories',
    'as' => 'api.ads_recommend.personal.update_announce_categories',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/activate/{uuid}', [
    'uses' => 'API\AdsRecommendPersonalController@activate',
    'as' => 'api.ads_recommend.personal.activate.single',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/request/validation/{uuid}', [
    'uses' => 'API\AdsRecommendPersonalController@requestValidation',
    'as' => 'api.ads_recommend.personal.request.validation',
]);

Route::middleware('auth:api')->post('ads_recommend/personal/calculate/{uuid}/{period_id}', [
    'uses' => 'API\AdsRecommendPersonalController@calculate',
    'as' => 'api.ads_recommend.personal.calculate.cost',
]);

// -- end ads recommend company

// payments
Route::middleware('auth:api')->get('payments/personal', [
    'uses' => 'API\PaymentsController@personal',
    'as' => 'api.payments.personal',
]);

Route::middleware('auth:api')->get('payments/all', [
    'uses' => 'API\PaymentsController@all',
    'as' => 'api.payments.all',
]);

Route::middleware('auth:api')->get('payments/all/invoices', [
    'uses' => 'API\PaymentsController@allInvoices',
    'as' => 'api.payments.all.invoices',
]);

Route::middleware('auth:api')->get('payments/single/{uuid}', [
    'uses' => 'API\PaymentsController@single',
    'as' => 'api.payments.single',
]);

// Invoices
// Route::middleware('auth:api')->post('invoices/download/{uuid}', [
//     'uses' => 'API\InvoicesController@download',
//     'as' => 'api.invoices.download',
// ]);

Route::middleware('auth:api')->post('invoices/upload/payment/{uuid}', [
    'uses' => 'API\InvoicesController@uploadForPayment',
    'as' => 'api.invoices.upload.payment',
]);

Route::middleware('auth:api')->post('invoices/download/{id}', [
    'uses' => 'API\InvoicesController@downloadInvoice',
    'as' => 'api.invoices.download.single',
]);

Route::middleware('auth:api')->post('invoices/delete/{id}', [
    'uses' => 'API\InvoicesController@delete',
    'as' => 'api.invoices.delete.single',
]);

Route::middleware('auth:api')->post('invoices/send/{id}', [
    'uses' => 'API\InvoicesController@sendToUser',
    'as' => 'api.invoices.send.single',
]);

// Announcements
Route::middleware('auth:api')->get('announcements/index', [
    'uses' => 'API\AnnouncementController@index',
    'as' => 'api.announcements.index',
]);

Route::middleware('auth:api')->get('announcements/get/active', [
    'uses' => 'API\AnnouncementController@getActive',
    'as' => 'api.announcements.get.active',
]);

Route::middleware('auth:api')->get('announcements/{id}', [
    'uses' => 'API\AnnouncementController@getSingle',
    'as' => 'api.announcements.get.single',
]);

Route::middleware('auth:api')->post('announcements/delete/{id}', [
    'uses' => 'API\AnnouncementController@deleteSingle',
    'as' => 'api.announcements.delete.single',
]);

Route::middleware('auth:api')->post('announcements/toggleStatus/{id}', [
    'uses' => 'API\AnnouncementController@toggleStatus',
    'as' => 'api.announcements.toggleStatus.single',
]);

Route::middleware('auth:api')->post('announcements/changeType/{id}', [
    'uses' => 'API\AnnouncementController@changeType',
    'as' => 'api.announcements.change.type',
]);

Route::middleware('auth:api')->post('announcements/store', [
    'uses' => 'API\AnnouncementController@store',
    'as' => 'api.announcements.store',
]);

// Demands

Route::middleware('auth:api')->post('demands/search', [
    'uses' => 'API\DemandsController@search',
    'as' => 'api.demands.search',
]);

Route::middleware('auth:api')->get('demands/personal', [
    'uses' => 'API\DemandsController@personal',
    'as' => 'api.demands.personal',
]);

Route::middleware('auth:api')->get('demands/{id}/review', [
    'uses' => 'API\DemandsController@get_review',
    'as' => 'api.demands.review.get',
]);

Route::middleware('auth:api')->get('demands/{demand_id}/winner/active', [
    'uses' => 'API\DemandsController@get_active_winner',
    'as' => 'api.demands.active.winner',
]);

Route::middleware('auth:api')->post('demands/explore', [
    'uses' => 'API\DemandsController@explore',
    'as' => 'api.demands.explore',
]);

Route::middleware('auth:api')->get('demands/explore/all', [
    'uses' => 'API\DemandsController@exploreALl',
    'as' => 'api.demands.explore.all',
]);

Route::middleware('auth:api')->apiResource('demands', 'API\DemandsController');

// DemandReports

Route::middleware('auth:api')->post('demands/reports/{id}/delete', [
    'uses' => 'API\DemandReportsController@destroy',
    'as' => 'api.demands.reports.delete',
]);

// Route::apiResources([
//     'api/demands' => 'API\DemandsController',
// ]);

// Categories COntroller

Route::middleware('auth:api')->get('categories/list/get/all', [
    'uses' => 'API\CategoriesController@getCategories',
    'as' => 'api.categories.list.all',
]);

Route::middleware('auth:api')->post('categories/store', [
    'uses' => 'API\CategoriesController@store',
    'as' => 'api.categories.store',
]);

Route::middleware('auth:api')->post('categories/update/{id}', [
    'uses' => 'API\CategoriesController@update',
    'as' => 'api.categories.update',
]);

Route::middleware('auth:api')->post('categories/delete/{id}', [
    'uses' => 'API\CategoriesController@destroyCategory',
    'as' => 'api.categories.delete',
]);

Route::middleware('auth:api')->post('categories/user/save', [
    'uses' => 'API\CategoriesController@saveUserCategories',
    'as' => 'api.categories.user.store',
]);

Route::middleware('auth:api')->post('categories/user/delete/', [
    'uses' => 'API\CategoriesController@deleteCategory',
    'as' => 'api.categories.user.delete',
]);

// Work Projects Controller

// Not public
Route::middleware('auth:api')->get('work-projects/initialize/personal', [
    'uses' => 'API\WorkProjectsController@initializePersonal',
    'as' => 'api.projects.initialize.personal',
]);

Route::middleware('auth:api')->get('work-projects/initialize/category/{uuid}', [
    'uses' => 'API\WorkProjectsController@initializeCategory',
    'as' => 'api.projects.initialize.category',
]);

Route::middleware('auth:api')->get('work-projects/get/{uuid}', [
    'uses' => 'API\WorkProjectsController@getProject',
    'as' => 'api.projects.get.project',
]);

Route::middleware('auth:api')->post('work-projects/store', [
    'uses' => 'API\WorkProjectsController@store',
    'as' => 'api.projects.store',
]);

Route::middleware('auth:api')->post('work-projects/update/{uuid}', [
    'uses' => 'API\WorkProjectsController@update',
    'as' => 'api.projects.update',
]);

Route::middleware('auth:api')->post('work-projects/{uuid}/upload/photos', [
    'uses' => 'API\WorkProjectsController@uploadPhotos',
    'as' => 'api.projects.upload.photos',
]);

Route::middleware('auth:api')->post('work-projects/delete/{uuid}', [
    'uses' => 'API\WorkProjectsController@destroy',
    'as' => 'api.projects.delete',
]);

// WorkProjectsController for Admin

Route::middleware('auth:api')->get('admin/work-projects/initialize', [
    'uses' => 'API\AdminWorkProjectsController@initialize',
    'as' => 'api.admin.projects.initialize',
]);

Route::middleware('auth:api')->get('admin/work-projects/get/{uuid}', [
    'uses' => 'API\AdminWorkProjectsController@getProject',
    'as' => 'api.admin.projects.get.project',
]);

Route::middleware('auth:api')->post('admin/work-projects/update/{uuid}', [
    'uses' => 'API\AdminWorkProjectsController@update',
    'as' => 'api.admin.projects.update',
]);

Route::middleware('auth:api')->post('admin/work-projects/{uuid}/upload/photos', [
    'uses' => 'API\AdminWorkProjectsController@uploadPhotos',
    'as' => 'api.admin.projects.upload.photos',
]);

Route::middleware('auth:api')->post('admin/work-projects/delete/{uuid}', [
    'uses' => 'API\AdminWorkProjectsController@destroy',
    'as' => 'api.admin.projects.delete',
]);

// Work Project Photos
Route::middleware('auth:api')->post('work-projects-photos/delete/{id}', [
    'uses' => 'API\WorkProjectPhotoController@destroy',
    'as' => 'api.projects_photo.delete',
]);

Route::middleware('auth:api')->post('admin/work-projects-photos/delete/{id}', [
    'uses' => 'API\WorkProjectPhotoController@destroyAsAdmin',
    'as' => 'api.admin.projects_photo.delete',
]);

// Project categories
Route::middleware('auth:api')->get('work-project-categories/init', [
    'uses' => 'API\WorkProjectCategoryController@initialize',
    'as' => 'api.work_project_categories.init',
]);

Route::middleware('auth:api')->post('work-project-categories/store', [
    'uses' => 'API\WorkProjectCategoryController@store',
    'as' => 'api.work_project_categories.store',
]);

Route::middleware('auth:api')->post('work-project-categories/{uuid}/update', [
    'uses' => 'API\WorkProjectCategoryController@update',
    'as' => 'api.work_project_categories.update',
]);

Route::middleware('auth:api')->post('work-project-categories/{uuid}/delete', [
    'uses' => 'API\WorkProjectCategoryController@delete',
    'as' => 'api.work_project_categories.delete',
]);

// Reviews Controller

Route::middleware('auth:api')->get('reviews/get/all', [
    'uses' => 'API\ReviewsController@all_reviews',
    'as' => 'api.reviews.get.all',
]);

Route::middleware('auth:api')->get('reviews/user/{id}/get/all', [
    'uses' => 'API\ReviewsController@all_user_reviews',
    'as' => 'api.reviews.user.get.all',
]);

Route::middleware('auth:api')->get('reviews/get/reported', [
    'uses' => 'API\ReviewsController@list_reported_reviews',
    'as' => 'api.reviews.get.reported',
]);

Route::middleware('auth:api')->get('reviews/get/personal', [
    'uses' => 'API\ReviewsController@personal_reviews',
    'as' => 'api.reviews.get.personal',
]);

Route::middleware('auth:api')->post('reviews/{timeline_id}/store', [
    'uses' => 'API\ReviewsController@store',
    'as' => 'api.reviews.store',
]);

Route::middleware('auth:api')->post('reviews/delete/{id}', [
    'uses' => 'API\ReviewsController@delete',
    'as' => 'api.reviews.delete',
]);

// ReportReviewsController
Route::middleware('auth:api')->post('reviews/reports/{id}', [
    'uses' => 'API\ReportReviewsController@report',
    'as' => 'api.reviews.reports.report',
]);

// Winner COntroller
Route::middleware('auth:api')->post('winners/{timeline_id}/confirm', [
    'uses' => 'API\WinnerController@confirm_winner',
    'as' => 'api.winners.confirm',
]);

Route::middleware('auth:api')->post('winners/{timeline_id}/cancel', [
    'uses' => 'API\WinnerController@cancel_winner',
    'as' => 'api.winners.cancel',
]);

Route::middleware('auth:api')->post('demands/{demand_uuid}/winners/change/to/pro/{pro_id}', [
    'uses' => 'API\WinnerController@winners_change',
    'as' => 'api.demands.winners.change',
]);

// timelines

Route::middleware('auth:api')->get('timelines/get/{uuid}', [
    'uses' => 'API\TimelinesController@getTimelineByUUID',
    'as' => 'api.timelines.get.uuid',
]);

Route::middleware('auth:api')->get('timelines/personal/client', [
    'uses' => 'API\TimelinesController@personalClient',
    'as' => 'api.timelines.personal.client',
]);

Route::middleware('auth:api')->get('timelines/personal/pro', [
    'uses' => 'API\TimelinesController@personalPro',
    'as' => 'api.timelines.personal.pro',
]);

Route::middleware('auth:api')->get('timelines/conversation/{uuid}', [
    'uses' => 'API\TimelinesController@conversation',
    'as' => 'api.timelines.conversation.get',
]);

Route::middleware('auth:api')->get('timelines/conversation-no-demand/{uuid}', [
    'uses' => 'API\TimelinesController@conversation_without_demand',
    'as' => 'api.timelines.conversation-no-demand.get',
]);

Route::middleware('auth:api')->get('timelines/conversation-no-demand/pro/{uuid}', [
    'uses' => 'API\TimelinesController@conversation_without_demand_pro',
    'as' => 'api.timelines.conversation-no-demand.pro.get',
]);

Route::middleware('auth:api')->post('timelines/{id}/response/storeQuote', [
    'uses' => 'API\TimelinesController@storeQuote',
    'as' => 'api.response.timelines.store',
]);

Route::middleware('auth:api')->post('timelines/{id}/response/storeMessage', [
    'uses' => 'API\TimelinesController@storeMessage',
    'as' => 'api.response.timelines.store.message',
]);

Route::middleware('auth:api')->post('timelines/{uuid}/change/status', [
    'uses' => 'API\TimelinesController@changeStatusUUID',
    'as' => 'api.timelines.change.status',
]);

Route::middleware('auth:api')->post('timelines/{uuid}/delete/client', [
    'uses' => 'API\TimelinesController@deleteByClient',
    'as' => 'api.timelines.delete.client',
]);

// FIles
Route::middleware('auth:api')->post('files/{id}/quote/delete', [
    'uses' => 'API\FilesController@delete_file_quote',
    'as' => 'api.files.quote.delete',
]);

Route::middleware('auth:api')->post('files/{id}/client_message/delete', [
    'uses' => 'API\FilesController@delete_file_client_message',
    'as' => 'api.files.client_message.delete',
]);

Route::middleware('auth:api')->post('files/{type}/{name}/download', [
    'uses' => 'API\FilesController@download',
    'as' => 'api.files.download',
]);

Route::middleware('auth:api')->get('get/files/{id}', [
    'uses' => 'API\FilesController@get_quotes',
    'as' => 'api.get.files.quotes',
]);

Route::middleware('auth:api')->post('files/{id}/demands/delete', [
    'uses' => 'API\FilesController@delete_file_demand_admin',
    'as' => 'api.files.demands.delete.admin',
]);

Route::middleware('auth:api')->post('attachments/{id}/demands/delete', [
    'uses' => 'API\FilesController@delete_attachment_demand_admin',
    'as' => 'api.attachments.demands.delete.admin',
]);

// Quotes Controller

Route::middleware('auth:api')->get('quotes/{id}/get', [
    'uses' => 'API\QuotesController@get',
    'as' => 'api.quotes.get',
]);

Route::middleware('auth:api')->delete('timelines/quote/{id}/delete', [
    'uses' => 'API\QuotesController@destroy',
    'as' => 'api.timelines.quotes.delete',
]);

// Client Messages Controller

Route::middleware('auth:api')->delete('timelines/client_message/{id}/delete', [
    'uses' => 'API\ClientMessageController@destroy',
    'as' => 'api.timelines.client_messages.delete',
]);

// Prospects Controller

Route::middleware('auth:api')->get('timelines/{uuid}/prospects/get', [
    'uses' => 'API\ProspectsController@get',
    'as' => 'api.timelines.prospects.get',
]);

Route::middleware('auth:api')->post('timelines/{uuid}/prospects/send', [
    'uses' => 'API\ProspectsController@send',
    'as' => 'api.timelines.prospects.send',
]);

Route::middleware('auth:api')->post('/prospects/{id}/accept', [
    'uses' => 'API\ProspectsController@accept_proposal_pro',
    'as' => 'api.prospects.accept',
]);

Route::middleware('auth:api')->post('/prospects/{id}/refuse', [
    'uses' => 'API\ProspectsController@refuse_proposal_pro',
    'as' => 'api.prospects.refuse',
]);

// Payments Controller

Route::middleware('auth:api')->get('/payments/existing/cards', [
    'uses' => 'API\PaymentsController@existing_cards',
    'as' => 'api.payments.existing.cards',
]);

// Profile
Route::middleware('auth:api')->post('profile/company/save', [
    'uses' => 'API\ProfileController@saveCompanyProfile',
    'as' => 'api.profile.company.save',
]);

Route::middleware('auth:api')->post('profile/company/inactive', [
    'uses' => 'API\ProfileController@saveInactiveCompanyProfile',
    'as' => 'api.profile.company.inactive.save',
]);

Route::middleware('auth:api')->post('profile/categories/save', [
    'uses' => 'API\ProfileController@saveCategories',
    'as' => 'api.profile.categories.save',
]);

Route::middleware('auth:api')->post('profile/categories/eliminate', [
    'uses' => 'API\ProfileController@eliminateCategories',
    'as' => 'api.profile.categories.eliminate',
]);

Route::middleware('auth:api')->post('profile/location/save', [
    'uses' => 'API\ProfileController@saveLocation',
    'as' => 'api.profile.location.save',
]);

// Tickets
Route::middleware('auth:api')->get('tickets/initialize/admin', [
    'uses' => 'API\TicketsController@initializeForAdmin',
    'as' => 'api.tickets.initialize.admin',
]);

Route::middleware('auth:api')->get('tickets/initialize/personal', [
    'uses' => 'API\TicketsController@initializePersonal',
    'as' => 'api.tickets.initialize.personal',
]);

Route::middleware('auth:api')->get('tickets/personal', [
    'uses' => 'API\TicketsController@getPersonalTickets',
    'as' => 'api.tickets.personal',
]);

Route::middleware('auth:api')->get('tickets/get/{uuid}', [
    'uses' => 'API\TicketsController@getTicket',
    'as' => 'api.tickets.get',
]);

Route::middleware('auth:api')->post('tickets/store/new', [
    'uses' => 'API\TicketsController@store',
    'as' => 'api.tickets.store.new',
]);

Route::middleware('auth:api')->post('tickets/{ticket_id}/add/user/{user_id}', [
    'uses' => 'API\TicketsController@addUserToTicket',
    'as' => 'api.ticket.add.user',
]);

Route::middleware('auth:api')->post('tickets/{ticket_id}/delegate/to/user/{user_id}', [
    'uses' => 'API\TicketsController@delegateTicketToUser',
    'as' => 'api.ticket.delegate.user',
]);

Route::middleware('auth:api')->get('ticket/existing/resolvers/get', [
    'uses' => 'API\TicketsController@getResolvers',
    'as' => 'api.ticket.resolvers.get',
]);
Route::middleware('auth:api')->get('ticket/{ticket_id}/existing/resolvers/get', [
    'uses' => 'API\TicketsController@getResolvers',
    'as' => 'api.ticket.resolvers.get',
]);

Route::middleware('auth:api')->get('tickets/{id}/responses/last/unread', [
    'uses' => 'API\ResponseTicketController@getLastUnreadResponse',
    'as' => 'api.ticket.responses.last.unread',
]);

Route::middleware('auth:api')->post('tickets/{id}/response/store', [
    'uses' => 'API\ResponseTicketController@store',
    'as' => 'api.response.ticket.store',
]);

Route::middleware('auth:api')->get('tickets/{id}/responses', [
    'uses' => 'API\ResponseTicketController@getAllByTicket',
    'as' => 'api.ticket.responses.all',
]);

Route::middleware('auth:api')->get('tickets/{uuid}/get/responses', [
    'uses' => 'API\ResponseTicketController@getTicketResponses',
    'as' => 'api.ticket.responses.get.all',
]);

Route::middleware('auth:api')->post('tickets/{id}/responses/markAsRead', [
    'uses' => 'API\ResponseTicketController@markAsRead',
    'as' => 'api.ticket.responses.markAsRead',
]);

Route::middleware('auth:api')->post('tickets/responses/delete/{message_id}', [
    'uses' => 'API\ResponseTicketController@destroy',
    'as' => 'api.ticket.responses.delete',
]);

// notification settings
Route::middleware('auth:api')->post('/users/settings/notifications', [
    'uses' => 'API\UserNotificationSettings@getNotificationSettings',
    'as' => 'api.users.notifications.settings',
]);

Route::middleware('auth:api')->post('/users/settings/notifications/activate', [
    'uses' => 'API\UserNotificationSettings@activateNotifications',
    'as' => 'api.users.notifications.activate',
]);

Route::middleware('auth:api')->post('users/settings/notifications/update', [
    'uses' => 'API\UserNotificationSettings@updateNotifications',
    'as' => 'api.users.notifications.update',
]);

// MessagesController
Route::middleware('auth:api')->get('messages/get/unread', [
    'uses' => 'API\MessagesController@totalUnreadMessages',
    'as' => 'api.messages.get.unread',
]);

// Logins

Route::post('login', [
    'uses' => 'API\Auth\LoginController@login',
    'as' => 'api.login',
]);

Route::post('relogin', [
    'uses' => 'API\Auth\LoginController@relogin',
    'as' => 'api.relogin',
]);

Route::post('refresh/token', [
    'uses' => 'API\Auth\LoginController@refresh',
    'as' => 'api.refresh.token',
]);

Route::post('logout', [
    'uses' => 'API\Auth\LoginController@logout',
    'as' => 'api.logout',
]);

// Users

Route::middleware('auth:api')->get('users/get/all', [
    'uses' => 'API\UsersController@getUsers',
    'as' => 'api.users.get.all',
]);

Route::middleware('auth:api')->get('users/get/all/pros', [
    'uses' => 'API\UsersController@getUsersPros',
    'as' => 'api.users.get.all.pros',
]);

Route::middleware('auth:api')->get('users/{id}/get/demands', [
    'uses' => 'API\UsersController@getUserDemands',
    'as' => 'api.users.get.demands',
]);

Route::middleware('auth:api')->get('users/{id}/get/unlocked/demands', [
    'uses' => 'API\UsersController@getUserUnlockedDemands',
    'as' => 'api.users.get.unlocked.demands',
]);

Route::middleware('auth:api')->get('users/moderators/get', [
    'uses' => 'API\UsersController@getModerators',
    'as' => 'api.users.get.moderators',
]);

Route::middleware('auth:api')->get('users/get/current', [
    'uses' => 'API\UsersController@getCurrent',
    'as' => 'api.users.get.current',
]);

Route::middleware('auth:api')->get('users/get/{id}', [
    'uses' => 'API\UsersController@get',
    'as' => 'api.users.get',
]);

// company questions
Route::middleware('auth:api')->get('company_questions/get/all', [
    'uses' => 'API\CompanyQuestionsController@index',
    'as' => 'api.company_questions.get.all',
]);

Route::middleware('auth:api')->post('company_questions/store', [
    'uses' => 'API\CompanyQuestionsController@store',
    'as' => 'api.company_questions.store',
]);

Route::middleware('auth:api')->post('company_questions/edit/{id}', [
    'uses' => 'API\CompanyQuestionsController@edit',
    'as' => 'api.company_questions.edit',
]);

Route::middleware('auth:api')->post('company_questions/delete/{id}', [
    'uses' => 'API\CompanyQuestionsController@delete',
    'as' => 'api.company_questions.delete',
]);

// Invoice Information
Route::middleware('auth:api')->get('/invoice/information/get/current', [
    'uses' => 'API\InvoiceInformationController@information',
    'as' => 'api.invoices.information.get.current',
]);

Route::middleware('auth:api')->post('/invoice/information/save/company', [
    'uses' => 'API\InvoiceInformationController@saveCompany',
    'as' => 'api.invoices.information.save.company',
]);

Route::middleware('auth:api')->post('/invoice/information/save/individual', [
    'uses' => 'API\InvoiceInformationController@saveIndividual',
    'as' => 'api.invoices.information.save.individual',
]);

// Companies Controller

Route::middleware('auth:api')->get('/companies/get/current', [
    'uses' => 'API\CompaniesController@getCurrentCompany',
    'as' => 'api.companies.get.current',
]);

Route::middleware('auth:api')->get('companies/get/inactive', [
    'uses' => 'API\CompaniesController@getInactive',
    'as' => 'api.companies.get.inactive',
]);

Route::middleware('auth:api')->post('companies/store', [
    'uses' => 'API\CompaniesController@store',
    'as' => 'api.companies.store',
]);

Route::middleware('auth:api')->post('companies/accept', [
    'uses' => 'API\CompaniesController@acceptCompany',
    'as' => 'api.companies.accept',
]);

Route::middleware('auth:api')->post('companies/refuse', [
    'uses' => 'API\CompaniesController@refuseCompany',
    'as' => 'api.companies.refuse',
]);

Route::middleware('auth:api')->post('companies/verify/{id}', [
    'uses' => 'API\CompaniesController@verifyCompany',
    'as' => 'api.companies.verify',
]);

Route::middleware('auth:api')->post('companies/unverify/{id}', [
    'uses' => 'API\CompaniesController@unverifyCompany',
    'as' => 'api.companies.unverify',
]);

// CompanyCardController
Route::middleware('auth:api')->get('company_card/get', [
    'uses' => 'API\CompanyCardController@getCard',
    'as' => 'api.company.card.get',
]);

Route::middleware('auth:api')->post('company_card/update/image', [
    'uses' => 'API\CompanyCardController@update',
    'as' => 'api.company.card.update.image',
]);

Route::middleware('auth:api')->post('company_card/delete/image', [
    'uses' => 'API\CompanyCardController@delete',
    'as' => 'api.company.card.delete.image',
]);

// company data controller
Route::middleware('auth:api')->get('company/data/getbycui/{cui}', [
    'uses' => 'API\CompanyDataController@get_by_cui',
    'as' => 'api.company.data.get.cui',
]);

// CompanyReviewsController

Route::middleware('auth:api')->get('company_reviews/get/all', [
    'uses' => 'API\CompanyReviewsController@getAll',
    'as' => 'api.company_reviews.get.all',
]);

Route::middleware('auth:api')->get('company_reviews/user/has/review', [
    'uses' => 'API\CompanyReviewsController@userHasReview',
    'as' => 'api.company_reviews.user.has.review',
]);

Route::middleware('auth:api')->post('company_reviews/store', [
    'uses' => 'API\CompanyReviewsController@store',
    'as' => 'api.company_reviews.store',
]);

Route::middleware('auth:api')->post('company_reviews/save/{review_id}', [
    'uses' => 'API\CompanyReviewsController@save',
    'as' => 'api.company_reviews.save',
]);

Route::middleware('auth:api')->post('company_reviews/toggleStatus/{id}', [
    'uses' => 'API\CompanyReviewsController@toggleStatus',
    'as' => 'api.company_reviews.toggleStatus',
]);

Route::middleware('auth:api')->post('company_reviews/delete/{id}', [
    'uses' => 'API\CompanyReviewsController@delete',
    'as' => 'api.company_reviews.delete',
]);

// personal information

Route::middleware('auth:api')->post('users/change/password', [
    'uses' => 'API\UsersController@changeCurrentPassword',
    'as' => 'api.users.change.password',
]);

Route::middleware('auth:api')->post('users/update/personal/information', [
    'uses' => 'API\UsersController@updatePersonalInformation',
    'as' => 'api.users.update.personal.information',
]);

// end personal information

// SOcial profiles
Route::middleware('auth:api')->get('users/social/profiles', [
    'uses' => 'API\UserSocialProfilesController@get',
    'as' => 'api.users.social.profiles.get',
]);

Route::middleware('auth:api')->post('users/social/profiles/update', [
    'uses' => 'API\UserSocialProfilesController@update',
    'as' => 'api.users.social.profiles.update',
]);

// Pro Module
Route::middleware('auth:api')->get('pro/categories/selected', [
    'uses' => 'API\ProModuleController@getSelectedCategories',
    'as' => 'api.pro.module.selected.categories',
]);

Route::middleware('auth:api')->get('pro/existing/location', [
    'uses' => 'API\ProModuleController@getExistingLocation',
    'as' => 'api.pro.module.existing.location',
]);

// User Avatar
Route::middleware('auth:api')->get('users/avatar/get', [
    'uses' => 'API\UserAvatarController@get',
    'as' => 'api.users.avatar.get',
]);

Route::middleware('auth:api')->get('users/avatar/delete', [
    'uses' => 'API\UserAvatarController@delete',
    'as' => 'api.users.avatar.delete',
]);

Route::middleware('auth:api')->post('users/avatar/update/photo', [
    'uses' => 'API\UserAvatarController@update',
    'as' => 'api.users.avatar.update',
]);

Route::middleware('auth:api')->post('users/account/delete', [
    'uses' => 'API\UsersController@deleteAccount',
    'as' => 'api.users.account.delete',
]);

Route::middleware('auth:api')->post('users/toggle/status/account', [
    'uses' => 'API\UsersController@toggleAccountStatus',
    'as' => 'api.users.account.status.toggle',
]);

// end user avatar

Route::middleware('auth:api')->post('users/admin/store', [
    'uses' => 'API\UsersController@storeUser',
    'as' => 'api.users.admin.store',
]);

Route::middleware('auth:api')->post('users/admin/update/{id}', [
    'uses' => 'API\UsersController@updateUser',
    'as' => 'api.admin.update.user',
]);

Route::middleware('auth:api')->get('/admin/users/get/company/{id}', [
    'uses' => 'API\UsersController@getUserCompany',
    'as' => 'api.admin.users.get.company',
]);

Route::middleware('auth:api')->post('/admin/users/{id}/profile/company/save', [
    'uses' => 'API\UsersController@saveCompanyProfile',
    'as' => 'api.admin.users.save.company.profile',
]);

Route::middleware('auth:api')->post('admin/users/{id}/generate/password', [
    'uses' => 'API\UsersController@generatePassword',
    'as' => 'api.admin.user.generate.password',
]);

Route::middleware('auth:api')->post('admin/users/{id}/change/password', [
    'uses' => 'API\UsersController@changePassword',
    'as' => 'api.admin.user.change.password',
]);

Route::middleware('auth:api')->post('admin/users/{id}/change/status', [
    'uses' => 'API\UsersController@changeStatus',
    'as' => 'api.admin.user.change.status',
]);

Route::middleware('auth:api')->post('admin/users/{id}/email/change/status', [
    'uses' => 'API\UsersController@changeEmailStatus',
    'as' => 'api.admin.user.email.change.status',
]);

Route::middleware('auth:api')->post('admin/users/{id}/update/roles', [
    'uses' => 'API\UsersController@updateUserRoles',
    'as' => 'api.admin.user.update.roles',
]);

Route::middleware('auth:api')->post('admin/users/{id}/delete', [
    'uses' => 'API\UsersController@deleteUser',
    'as' => 'api.admin.user.delete',
]);

Route::middleware('auth:api')->post('admin/users/{id}/activate/pro', [
    'uses' => 'API\UsersController@activateProAccount',
    'as' => 'api.admin.user.activate.pro',
]);

Route::middleware('auth:api')->post('admin/users/{id}/desactivate/pro', [
    'uses' => 'API\UsersController@desactivateProAccount',
    'as' => 'api.admin.user.desactivate.pro',
]);

// Roles
Route::middleware('auth:api')->get('roles/get/all', [
    'uses' => 'API\RolesController@getAllRoles',
    'as' => 'api.roles.get',
]);

Route::middleware('auth:api')->post('roles/store', [
    'uses' => 'API\RolesController@store',
    'as' => 'api.roles.store',
]);

Route::middleware('auth:api')->post('roles/update/{id}', [
    'uses' => 'API\RolesController@update',
    'as' => 'api.roles.update',
]);

// Admin

Route::middleware('auth:api')->get('admin/get/demand/{id}', [
    'uses' => 'API\AdminController@getDemand',
    'as' => 'api.admin.demands.get.single',
]);

Route::middleware('auth:api')->get('admin/demands/get/all', [
    'uses' => 'API\AdminController@getDemands',
    'as' => 'api.admin.demands.get',
]);

Route::middleware('auth:api')->get('admin/reported/demands/get/all', [
    'uses' => 'API\AdminController@getReportedDemands',
    'as' => 'api.admin.demands.reported.get',
]);

Route::middleware('auth:api')->post('admin/demands/{id}/change/state', [
    'uses' => 'API\AdminController@changeState',
    'as' => 'api.admin.demands.change.state',
]);

Route::middleware('auth:api')->post('admin/demands/{id}/update', [
    'uses' => 'API\AdminController@update',
    'as' => 'api.admin.demands.update',
]);

Route::middleware('auth:api')->post('admin/demands/{id}/update/location', [
    'uses' => 'API\AdminController@updateLocation',
    'as' => 'api.admin.demands.update.location',
]);

Route::middleware('auth:api')->post('admin/demands/{id}/update/categories', [
    'uses' => 'API\AdminController@updateCategories',
    'as' => 'api.admin.demands.update.categories',
]);

Route::middleware('auth:api')->post('admin/demands/{uuid}/mark/invalid', [
    'uses' => 'API\DemandsController@markAsInvalid',
    'as' => 'api.admin.demands.mark.invalid',
]);

Route::middleware('auth:api')->post('admin/demands/{uuid}/mark/valid', [
    'uses' => 'API\DemandsController@markAsValid',
    'as' => 'api.admin.demands.mark.valid',
]);

Route::middleware('auth:api')->post('admin/demands/{uuid}/remark/valid', [
    'uses' => 'API\DemandsController@remarkAsValid',
    'as' => 'api.admin.demands.remark.valid',
]);

Route::middleware('auth:api')->post('admin/demands/{uuid}/relaunch/', [
    'uses' => 'API\DemandsController@relaunchByAdmin',
    'as' => 'api.admin.demands.relaunch',
]);

// buyers of demand
Route::middleware('auth:api')->post('admin/demands/buyer/{id}/delete', [
    'uses' => 'API\AdminController@deleteBuyer',
    'as' => 'api.admin.demands.buyer.delete',
]);

Route::middleware('auth:api')->post('admin/demands/{id}/delete', [
    'uses' => 'API\AdminController@deleteDemand',
    'as' => 'api.admin.demands.delete',
]);

// DemandsController - Professional Actions

Route::middleware('auth:api')->post('pro/demands/{uuid}/unlock', [
    'uses' => 'API\DemandsController@unlockDemandVue',
    'as' => 'api.pro.demands.unlock',
]);

Route::middleware('auth:api')->post('/pro/demands/{id}/report', [
    'uses' => 'API\DemandsController@reportDemandVue',
    'as' => 'api.pro.demands.report',
]);

Route::middleware('auth:api')->get('pro/reported/demands/get/all', [
    'uses' => 'API\DemandsController@getReportedDemands',
    'as' => 'api.pro.demands.reported.get',
]);

Route::middleware('auth:api')->get('pro/demands/get/unlocked/all', [
    'uses' => 'API\DemandsController@getUnlockedDemands',
    'as' => 'api.pro.demands.unlocked.get',
]);

// Coupons

Route::middleware('auth:api')->get('coupons/initialize', [
    'uses' => 'API\CouponsController@initialize',
    'as' => 'api.coupons.initialize',
]);

Route::middleware('auth:api')->get('coupons/initialize/personal', [
    'uses' => 'API\CouponsController@initializePersonal',
    'as' => 'api.coupons.initialize.personal',
]);

Route::middleware('auth:api')->get('coupons/single/{uuid}', [
    'uses' => 'API\CouponsController@getCoupon',
    'as' => 'api.coupons.get.single',
]);

Route::middleware('auth:api')->get('coupons/user/{id}', [
    'uses' => 'API\CouponsController@getUserCoupons',
    'as' => 'api.coupons.user.get',
]);

Route::middleware('auth:api')->get('coupons/user/{id}/activated', [
    'uses' => 'API\CouponsController@getUserActivatedCoupons',
    'as' => 'api.coupons.user.activated.get',
]);

Route::middleware('auth:api')->get('coupons/personal/single/{uuid}', [
    'uses' => 'API\CouponsController@getPersonalCoupon',
    'as' => 'api.coupons.get.personal.single',
]);

Route::middleware('auth:api')->get('coupons/get/users', [
    'uses' => 'API\CouponsController@getUsers',
    'as' => 'api.coupons.get.users',
]);

Route::middleware('auth:api')->post('coupons/store/generate', [
    'uses' => 'API\CouponsController@storeGenerate',
    'as' => 'api.coupons.store.generate',
]);

Route::middleware('auth:api')->post('coupons/store/for/user', [
    'uses' => 'API\CouponsController@storeForUser',
    'as' => 'api.coupons.store.for.user',
]);

Route::middleware('auth:api')->post('coupons/attach/to/user', [
    'uses' => 'API\CouponsController@attachToUser',
    'as' => 'api.coupons.attach.to.user',
]);

Route::middleware('auth:api')->post('coupons/{id}/delete', [
    'uses' => 'API\CouponsController@delete',
    'as' => 'api.coupons.delete',
]);

Route::middleware('auth:api')->post('coupons/{id}/activate', [
    'uses' => 'API\CouponsController@activate',
    'as' => 'api.coupons.activate',
]);

Route::middleware('auth:api')->post('coupons/{id}/activate/pro', [
    'uses' => 'API\CouponsController@activate_pro',
    'as' => 'api.coupons.activate.pro',
]);

// Coupons Requests
Route::middleware('auth:api')->get('coupons/requests/initialize/all', [
    'uses' => 'API\CouponsRequestsController@initialize_all',
    'as' => 'api.coupons.requests.initialize.all',
]);

Route::middleware('auth:api')->get('coupons/requests/initialize/personal', [
    'uses' => 'API\CouponsRequestsController@initialize_personal',
    'as' => 'api.coupons.requests.initialize.personal',
]);

Route::middleware('auth:api')->get('coupons/requests/get/pending/personal', [
    'uses' => 'API\CouponsRequestsController@get_pending_requests',
    'as' => 'api.coupons.requests.get.pending.personal',
]);

Route::middleware('auth:api')->post('coupons/requests/store', [
    'uses' => 'API\CouponsRequestsController@store',
    'as' => 'api.coupons.requests.store',
]);

Route::middleware('auth:api')->post('coupons/requests/accept/{id}', [
    'uses' => 'API\CouponsRequestsController@accept',
    'as' => 'api.coupons.requests.accept',
]);

Route::middleware('auth:api')->post('coupons/requests/refuse/{id}', [
    'uses' => 'API\CouponsRequestsController@refuse',
    'as' => 'api.coupons.requests.refuse',
]);

// Credits

Route::middleware('auth:api')->get('credits/user/{id}', [
    'uses' => 'API\CreditsController@getCredit',
    'as' => 'api.credits.user.get',
]);

// Charges
Route::middleware('auth:api')->get('charges/user/{id}', [
    'uses' => 'API\ChargesController@userCharges',
    'as' => 'api.charges.user.get',
]);

Route::middleware('auth:api')->get('charges/personal', [
    'uses' => 'API\ChargesController@personal',
    'as' => 'api.charges.personal.get',
]);

Route::middleware('auth:api')->get('charges/user/{id}/last/payment', [
    'uses' => 'API\ChargesController@userLastPayment',
    'as' => 'api.charges.user.get.last.payment',
]);

// Activity

Route::middleware('auth:api')->get('activities/user/{id}', [
    'uses' => 'API\ActivityController@getUserActivities',
    'as' => 'api.activities.user.get',
]);

Route::middleware('auth:api')->get('activities/personal', [
    'uses' => 'API\ActivityController@getPersonalActivities',
    'as' => 'api.activities.personal.get',
]);

// Professional
Route::middleware('auth:api')->post('professionals/activate/account', [
    'uses' => 'API\ProfessionalsController@activate',
    'as' => 'api.professionals.activate.account',
]);

// usernames
Route::middleware('auth:api')->get('users/check/username', [
    'uses' => 'API\UsernamesController@check',
    'as' => 'api.users.check.username',
]);

Route::middleware('auth:api')->post('users/set/username', [
    'uses' => 'API\UsernamesController@set',
    'as' => 'api.users.set.username',
]);

// Judete

Route::middleware('auth:api')->get('judete/personal', [
    'uses' => 'API\JudeteController@getPersonal',
    'as' => 'api.judete.get.personal',
]);

Route::middleware('auth:api')->post('judete/personal/delete/', [
    'uses' => 'API\JudeteController@deleteJudet',
    'as' => 'api.judete.delete.singular',
]);

Route::middleware('auth:api')->post('judete/user/save', [
    'uses' => 'API\JudeteController@saveUserJudete',
    'as' => 'api.judete.user.save',
]);

// Public profile user
Route::middleware('auth:api')->get('public/user/description', [
    'uses' => 'API\PublicUserProfile@getDescription',
    'as' => 'api.public.user.description',
]);

Route::middleware('auth:api')->post('public/user/description', [
    'uses' => 'API\PublicUserProfile@saveUserDescription',
    'as' => 'api.public.user.save.description',
]);

Route::middleware('auth:api')->get('public/user/website', [
    'uses' => 'API\PublicUserProfile@getWebsite',
    'as' => 'api.public.user.website',
]);

Route::middleware('auth:api')->post('public/user/website', [
    'uses' => 'API\PublicUserProfile@saveUserWebsite',
    'as' => 'api.public.user.save.website',
]);

/* API TOKEN KEY */

// De folosit API_TOKEN
Route::post('phone/send/code', [
    'uses' => 'API\PhoneVerificationController@send',
    'as' => 'api.phone.send.code',
]);

Route::post('phone/delete/code/{uuid}', [
    'uses' => 'API\PhoneVerificationController@delete',
    'as' => 'api.phone.delete.code',
]);

Route::post('phone/verify/code', [
    'uses' => 'API\PhoneVerificationController@verify',
    'as' => 'api.phone.verify.code',
]);

// Public Demands Controller
Route::post('public/demands/store', [
    'uses' => 'API\PublicDemandsController@store',
    'as' => 'api.public.demands.store',
]);

Route::post('public/demands/relaunch/{uuid}/{unique}', [
    'uses' => 'API\PublicDemandsController@relaunch',
    'as' => 'api.public.demands.relaunch',
]);

Route::post('public/demands/delete/{uuid}/{unique}', [
    'uses' => 'API\PublicDemandsController@delete',
    'as' => 'api.public.demands.delete',
]);

Route::post('public/resize/image', [
    'uses' => 'API\PublicDemandsController@resizeImage',
    'as' => 'api.public.resize.image',
]);

/** End verificare numar telefon */

// Judete
Route::middleware('checkServer')->get('judete/get/all/listare', [
    'uses' => 'API\JudeteController@getAll',
    'as' => 'api.judete.get.all.listare',
]);

Route::get('judete/get/all', [
    'uses' => 'API\JudeteController@getAll',
    'as' => 'api.judete.get.all',
]);

// Public Files Controller
Route::middleware('checkServer')->get('files/check/{path}/{name}', [
    'uses' => 'API\FilesController@checkFileExists',
    'as' => 'files.check.exists',
]);

// Utilizat de reformex-listare

// Categories controller

Route::get('categories/get/all/local', [
    'uses' => 'API\CategoriesController@index',
    'as' => 'api.categories.all.local',
]);

Route::get('categories/get/all', [
    'uses' => 'API\CategoriesController@index',
    'as' => 'api.categories.all',
]);

Route::middleware('checkServer')->get('categories/get/all/listare', [
    'uses' => 'API\CategoriesController@index',
    'as' => 'api.categories.all.listare',
]);

Route::middleware('checkServer')->get('categories/get/single/{category_slug}', [
    'uses' => 'API\CategoriesController@getCategory',
    'as' => 'api.categories.get.single',
]);

// Work projects
Route::middleware('checkServer')->get('work-project/get/photos/{id}', [
    'uses' => 'API\WorkProjectsController@getProjectPhotosById',
    'as' => 'api.projects.get.photos',
]);

// Public company questions
Route::middleware('checkServer')->get('company_questions/public/get/{user_id}', [
    'uses' => 'API\CompanyQuestionsController@publicQuestions',
    'as' => 'api.company_questions.public.get.personal',
]);

// companies controller

Route::post('company/form/register', [
    'uses' => 'API\CompaniesController@registerForm',
    'as' => 'api.companies.form.register',
]);

Route::post('company/send/direct/message', [
    'uses' => 'API\CompaniesController@sendFormMessageToCompany',
    'as' => 'api.companies.send.direct.message',
]);

Route::middleware('checkServer')->get('locations/get/single/{location_slug}', [
    'uses' => 'API\JudeteController@getLocation',
    'as' => 'api.locations.get.single',
]);

Route::middleware('checkServer')->get('company/get/public/{username}', [
    'uses' => 'API\CompaniesController@getCompanyByUsername',
    'as' => 'api.company.get.username',
]);

Route::middleware('checkServer')->get('companies/category/get/{category_slug}/{page}', [
    'uses' => 'API\CompaniesController@getCompaniesByCategory',
    'as' => 'api.companies.get.by.category',
]);

Route::middleware('checkServer')->get('companies/location/category/get/{category_slug}/{location_slug}/{page}', [
    'uses' => 'API\CompaniesController@getCompaniesByCategoryAndLocation',
    'as' => 'api.companies.get.by.category.and.city',
]);

Route::middleware('checkServer')->get('companies/verified/location/category/get/{category_slug}/{location_slug}/{page}', [
    'uses' => 'API\CompaniesController@getVerifiedCompaniesByCategoryAndLocation',
    'as' => 'api.companies.verified.get.by.category.and.city',
]);

Route::middleware('checkServer')->get('companies/search/{category_uuid}/{location_code}/{page}', [
    'uses' => 'API\CompaniesController@searchCompanies',
    'as' => 'api.companies.search',
]);

Route::middleware('checkServer')->get('companies/search/verified/{category_uuid}/{location_code}/{page}', [
    'uses' => 'API\CompaniesController@searchVerifiedCompanies',
    'as' => 'api.companies.search.verified',
]);

Route::middleware('checkServer')->get('companies/get/top', [
    'uses' => 'API\CompaniesController@getTopCompanies',
    'as' => 'api.companies.get.top',
]);

Route::middleware('checkServer')->get('company_reviews/get/all/public', [
    'uses' => 'API\CompanyReviewsController@getAllPublic',
    'as' => 'api.company_reviews.get.all.public',
]);

// end for reformex-listare
