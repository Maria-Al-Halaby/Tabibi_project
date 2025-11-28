<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClinicCenterController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\GetAllController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProfileSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */


/* auth api'n route */
Route::post("/register" , [AuthController::class , "register"]);
Route::post("/login" , [AuthController::class , "login"]);


/* patient api's route  */
Route::middleware(["auth:sanctum" , "role:patient"])->group(function()
{
    /* profile api's route */
    Route::put("update_profile" , [ProfileSettingController::class , "update"]);
    Route::get("get_profile" , [ProfileSettingController::class , "get_profile"]);
    Route::post("delete_account" , [ProfileSettingController::class , "delete_account"]);

    /* home screen api route */
    Route::get("/home" , [HomeController::class , "home"]);

    /* get all api's route */
    Route::get("/get_all_specialties" , [GetAllController::class , "get_all_specialties"]);
    Route::get("/get_all_doctors/{specialization_id?}/{center_id?}" , [GetAllController::class , "get_all_doctor"]);
    Route::get("/get_all_clinic_centers" , [GetAllController::class , "get_all_centers"]);

    Route::get("/get_doctor/{doctor_id}" , [DoctorController::class , "get_doctor"]);
    Route::get("/get_clinic_center/{clinic_center_id}" , [ClinicCenterController::class , "show"]);
});

Route::middleware("auth:sanctum")->group(function(){
    Route::post("logout" , [AuthController::class , "logout"]);
});
