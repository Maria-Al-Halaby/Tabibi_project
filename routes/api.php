<?php

use App\Http\Controllers\Api\AppointmentsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClinicCenterController;
use App\Http\Controllers\Api\DoctorAppointmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\GetAllController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProfileSettingController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */


/* auth api'n route */
Route::post("/register" , [AuthController::class , "register"]);
Route::post("/login" , [AuthController::class , "login"]);

 /* reset password api's */
Route::post("password/forget_password" , [ForgetPasswordController::class , "forgetPassword"]);
Route::post("password/reset_password" , [ResetPasswordController::class , "resetPassword"]);


/* patient api's route  */
Route::middleware(["auth:sanctum" , "role:patient"])->group(function()
{
    /* profile api's route */
    Route::put("update_profile" , [ProfileSettingController::class , "update"]);
    //Route::get("get_profile" , [ProfileSettingController::class , "get_profile"]);
    Route::post("delete_account" , [ProfileSettingController::class , "delete_account"]);

    /* home screen api route */
   // Route::get("/home" , [HomeController::class , "home"]);

    /* get all api's route */
    Route::get("/get_all_specialties" , [GetAllController::class , "get_all_specialties"]);
    Route::get("/get_all_doctors/{specialization_id?}/{center_id?}" , [GetAllController::class , "get_all_doctor"]);
    Route::get("/get_all_clinic_centers" , [GetAllController::class , "get_all_centers"]);

    Route::get("/get_doctor/{doctor_id}" , [DoctorController::class , "get_doctor"]);
    Route::get("/get_clinic_center/{clinic_center_id}" , [ClinicCenterController::class , "show"]);


    /* appointments route */

    Route::get("/get_doctor_centers/{doctor}" , [AppointmentsController::class , "get_doctor_centers"]);
    Route::get("/get_30_days/{doctor}/{center}" , [AppointmentsController::class , "getAtLeast30DaysAfterTodayForTheDoctorInThisCenter"]);
    Route::get("/get_times_today/{doctor}/{center}/{date}" , [AppointmentsController::class , "getAvailableTimes"]);
    Route::post("/appointment/{doctor}/{center}/{date}/{period}" , [AppointmentsController::class , "storeAppointment"]);
    Route::get("/appointments" , [AppointmentsController::class , "index"]);
});

/* doctor home screen route and appointments */
Route::middleware(["auth:sanctum" , "role:doctor|patient"])->group(function(){
    Route::get("/home" , [HomeController::class , "home"]);
    Route::get("/appointment_details/{appointment}" , [AppointmentsController::class , "appointment_details"]);
    Route::post('/appointments/cancel', [AppointmentsController::class, "cancelAppointment"]);

    /* get profile api */

    Route::get("get_profile" , [ProfileSettingController::class , "get_profile"]);



});


/* doctor api's */
Route::middleware(["auth:sanctum" , "role:doctor"])->group(function(){
    Route::get("/doctor/appointments/{center?}/{date?}" , [DoctorAppointmentController::class , "index"]);
    Route::post('/doctor/appointments/end', [DoctorAppointmentController::class, 'end_appointment']);
});

Route::middleware("auth:sanctum")->group(function(){
    Route::post("logout" , [AuthController::class , "logout"]);
});
