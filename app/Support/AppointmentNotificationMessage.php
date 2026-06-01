<?php

namespace App\Support;

use App\Models\Appointment;

class AppointmentNotificationMessage
{
    public static function newAppointment(Appointment $appointment): array
    {
        $patientName = $appointment->patient_display_name;

        return [
            'title' => 'تم حجز موعد جديد',
            'body' => 'تم حجز موعد جديد بواسطة '.$patientName.' بتاريخ '.self::appointmentDate($appointment).'.',
        ];
    }

    public static function completed(Appointment $appointment): array
    {
        return [
            'title' => 'تم إكمال الموعد',
            'body' => 'تم إكمال موعدك بتاريخ '.self::appointmentDate($appointment).'. نتمنى لك الصحة والعافية.',
        ];
    }

    public static function cancelledByDoctor(Appointment $appointment): array
    {
        return [
            'title' => 'تم إلغاء الموعد',
            'body' => 'تم إلغاء موعدك المقرر بتاريخ '.self::appointmentDate($appointment).' من قبل الطبيب.',
        ];
    }

    public static function cancelledByStaff(Appointment $appointment): array
    {
        return [
            'title' => 'تم إلغاء الموعد',
            'body' => 'تم إلغاء موعدك المقرر بتاريخ '.self::appointmentDate($appointment).' من قبل موظفي المركز.',
        ];
    }

    private static function appointmentDate(Appointment $appointment): string
    {
        return $appointment->start_at->format('Y-m-d H:i');
    }
}
