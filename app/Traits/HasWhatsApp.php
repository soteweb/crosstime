<?php

namespace App\Traits;

trait HasWhatsApp
{
    /**
     * Generate a WhatsApp notification URL for an athlete.
     *
     * @param \App\Models\Registration $registration
     * @return string
     */
    public function generateWhatsAppUrl($registration)
    {
        $phone = preg_replace('/[^0-9]/', '', $registration->athlete->phone);
        
        // Format for Paraguay (595) if it's a local 9-digit number
        if (strlen($phone) === 9 && $phone[0] === '0') {
            $phone = '595' . substr($phone, 1);
        } elseif (strlen($phone) === 9) {
            $phone = '595' . $phone;
        }

        $message = "🏆 ¡Hola *{$registration->athlete->name}*! Ya retiraste tu kit para el evento *{$registration->event->title}*. Tu número de dorsal asignado es el *#{$registration->bib_number}*. ¡Muchos éxitos en la carrera! 🏃‍♂️🏁";
        
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }
}
