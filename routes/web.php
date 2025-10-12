<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicCenterController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Models\ClinicCenter;
use App\Models\Specialization;
use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

/* auth route */

Route::get("/" , [AuthController::class , "ShowLoginPage"])->name("login");
Route::post("/" , [AuthController::class , "login"])->name("login");

/* super admin dashboard: */

Route::middleware(["auth" , "role:super admin"])->prefix("/SuperAdmin/Dashborad/")->group(function (){

    /* Details route */

    Route::get("Details/" , [SuperAdminDashboardController::class , "index"])->name("SuperAdmin.Detials.index");

    /* specialization route */

    Route::get("/       " , [SpecializationController::class , "index"])->name("SuperAdmin.specialization.index");
    Route::get("/AddNew/Specialization" , [SpecializationController::class , "create"])->name("SuperAdmin.specialization.create");
    Route::post("/AddNew/Specialization" , [SpecializationController::class , "store"])->name("SuperAdmin.specialization.store");
    Route::get("/update/specialization/{specialization}" , [SpecializationController::class , "edit"])->name("SuperAdmin.specialization.edit");
    Route::put("/update/specialization/{specialization}" , [SpecializationController::class , "update"])->name("SuperAdmin.specialization.update");
    Route::delete("/delete/specialization/{specialization}" , [SpecializationController::class , "destroy"])->name("SuperAdmin.specialization.destroy");

    /* doctors route */
    Route::get("/doctors" , [DoctorController::class , "index"])->name("SuperAdmin.doctor.index");
    Route::get("addNew/doctor" , [DoctorController::class , "create"])->name("SuperAdmin.doctor.create");
    Route::post("/addNew/doctor" , [DoctorController::class , "store"])->name("SuperAdmin.doctor.store");
    Route::get("/edit/doctor/{doctor}" , [DoctorController::class , "edit"])->name("SuperAdmin.doctor.edit");
    Route::put("/update/doctor/{doctor}" , [DoctorController::class , "update"])->name("SuperAdmin.doctor.update");
    Route::delete("/delete/doctor/{doctor}" , [DoctorController::class , "destroy"])->name("SuperAdmin.doctor.destroy");

    /* clinic centers route */

    Route::get("/clinic_centers" , [ClinicCenterController::class , "index"])->name("SuperAdmin.ClinicCenter.index");
    Route::get("/addNew/clinic_center" , [ClinicCenterController::class , "create"])->name("SuperAdmin.clinicCenter.create");
    Route::post("/addNew/clinic_center" , [ClinicCenterController::class , "store"])->name("SuperAdmin.clinic_center.store");
    Route::get("/update/clinic_center/{clinicCenter}" , [ClinicCenterController::class ,"edit"])->name("SuperAdmin.clinic_center.edit");
    Route::put("/update/clinic_center/{clinicCenter}" , [ClinicCenterController::class , "update"])->name("SuperAdmin.clinic_center.update");

    Route::delete("/delete/clinic_center/{clinicCenter}" , [ClinicCenterController::class ,"destroy"])->name("SuperAdmin.clinic_center.destroy");


    

});

Route::middleware("auth")->group(function(){
    Route::post("/logout" , [AuthController::class , "logout"])->name("logout");
});


