<?php
namespace App\Support;

// Indian mobile numbers are stored as 10 bare digits (users.phone, customers.phone).
// People type them every which way (+91 98300 00001, 098300-00001), so normalise before lookup.
class Phone
{
    public static function normalize(?string $raw): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);

        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return substr($digits, 2);
        }
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return substr($digits, 1);
        }

        return $digits;
    }
}
