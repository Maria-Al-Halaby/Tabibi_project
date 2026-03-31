<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicCenterController;
use App\Http\Controllers\ClinicManagement;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorSchedulesController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\PromotController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\SuperAdmin\DoctorRatingController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\RadiologyDashboardController;
use App\Http\Controllers\LabDashboardController;
use App\Http\Controllers\PharmacyDashboardController;
use App\Http\Controllers\AdminPharmacyController;
use App\Models\Appointment;
use App\Models\ClinicCenter;
use App\Models\DoctorSchedules;
use App\Models\Promot;
use App\Models\Specialization;
use Illuminate\Support\Facades\Http;
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

    Route::get("specialization/" , [SpecializationController::class , "index"])->name("SuperAdmin.specialization.index");
    Route::get("/AddNew/specialization" , [SpecializationController::class , "create"])->name("SuperAdmin.specialization.create");
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


    Route::get("/promot" , [PromotController::class , 'index'])->name("SuperAdmin.Promot.index");
    Route::get("/add_new/promot" , [PromotController::class , 'create'])->name("SuperAdmin.Promot.create");
    Route::post("/add_new/promot" , [PromotController::class , "store"])->name("SuperAdmin.Promot.store");
    Route::get("/update/promot/{promot}" , [PromotController::class , 'edit'])->name("SuperAdmin.Promot.edit");
    Route::put("/update/promot/{promot}" , [PromotController::class , 'update'])->name("SuperAdmin.Promot.update");
    Route::get("delete/promot/{promot}" , [PromotController::class , 'destroy'])->name("SuperAdmin.Promot.destroy");


    /* doctor rating section */
    Route::get('/doctor-ratings', [DoctorRatingController::class, 'index'])->name('doctor_ratings.index');
    Route::post('/doctors/{doctor}/deactivate', [DoctorRatingController::class, 'deactivateDoctor'])->name('doctors.deactivate');
    Route::delete('/doctors/{doctor}', [DoctorRatingController::class, 'destroyDoctor'])->name('doctors.destroy');


    /* end point is not exists */
    Route::fallback(function () {
        return view("Super Admin.404_not_found_page");
    });

});

Route::middleware("auth")->group(function(){
    Route::post("/logout" , [AuthController::class , "logout"])->name("logout");
    Route::get("/notification" , [FirebaseController::class , "notification"]);


});

/* admin route */

Route::middleware(["auth" , "role:admin"])->prefix("Admin/Dashboard")->group(function(){
    Route::get("/" , [AdminDashboardController::class , "index"])->name("Admin.index");
    Route::get("/clinic_management" , [ClinicManagement::class , "index"])->name("Admin.ClinicManagement.index");
    Route::get("/clinic_management/create" , [ClinicManagement::class , "create"])->name("Admin.ClinicManagement.create");
    
    /* doctor schedule route */
    Route::get("/doctor_schedule/{doctor}" , [DoctorSchedulesController::class , "show"])->name("Admin.DoctorSchedule.show");
    Route::get("/doctor_schedule/create/{doctor}" , [DoctorSchedulesController::class , "create"] )->name("Admin.DoctorSchedule.create");
    Route::post("/doctor_schedule/store/{doctor}" , [DoctorSchedulesController::class , "store"])->name("Admin.DoctorSchedule.store");
    Route::get("/doctor_schedule/edit/{doctor}" , [DoctorSchedulesController::class , "edit"])->name("Admin.DoctorSchedule.edit");
    Route::put("/doctor_schedule/edit/{doctor}" , [DoctorSchedulesController::class , "update"])->name("Admin.DoctorSchedule.update");
    Route::delete("/doctor_schedule/delete/{doctor}" , [DoctorSchedulesController::class , "destroy"])->name("Admin.DoctorSchedule.destroy");


    /* appointment route  */
    Route::get("/appointment" , [AppointmentController::class , "index"])->name("Admin.Appointment.index");
    Route::get("/appointment/canceled/{appointments}" , [AppointmentController::class , "cancel"])->name("Admin.Appointment.cancel");

    /* pharmacist routes */
    Route::get('/pharmacy', [AdminPharmacyController::class, 'index'])->name('Admin.Pharmacy.index');
    Route::post('/pharmacy/store', [AdminPharmacyController::class, 'store'])->name('Admin.Pharmacy.store');
    Route::get('/pharmacy/edit/{user}', [AdminPharmacyController::class, 'edit'])->name('Admin.Pharmacy.edit');
    Route::put('/pharmacy/update/{user}', [AdminPharmacyController::class, 'update'])->name('Admin.Pharmacy.update');
    Route::delete('/pharmacy/delete/{user}', [AdminPharmacyController::class, 'destroy'])->name('Admin.Pharmacy.destroy');

    /* end point is not exists */
    Route::fallback(function () {
        return view("Admin.404_not_found_page");
    });

});

/* radiology dashboard */
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/radiology/dashboard', [RadiologyDashboardController::class, 'index'])->name('radiology.dashboard');
    Route::get('/radiology/appointments/{appointment}/complete', [RadiologyDashboardController::class, 'showCompleteForm'])->name('radiology.appointments.complete.form');
    Route::post('/radiology/appointments/complete', [RadiologyDashboardController::class, 'complete'])->name('radiology.appointments.complete');
});

/* lab dashboard */
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/lab/dashboard', [LabDashboardController::class, 'index'])->name('lab.dashboard');
    Route::get('/lab/appointments/{appointment}/complete', [LabDashboardController::class, 'showCompleteForm'])->name('lab.appointments.complete.form');
    Route::post('/lab/appointments/complete', [LabDashboardController::class, 'complete'])->name('lab.appointments.complete');
});

/* pharmacy dashboard */
Route::middleware(['auth', 'role:pharmacist'])->group(function () {
    Route::get('/pharmacy/dashboard', [PharmacyDashboardController::class, 'index'])->name('pharmacy.dashboard');
    Route::get('/pharmacy/prescriptions/{prescription}', [PharmacyDashboardController::class, 'show'])->name('pharmacy.prescriptions.show');
    Route::post('/pharmacy/prescriptions/{prescription}/update-status', [PharmacyDashboardController::class, 'updateStatus'])->name('pharmacy.prescriptions.updateStatus');
});

//test route 
/* Route::get("send-notification" , [FCMController::class , "send_notification"]); */



