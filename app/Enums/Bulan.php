<?php

namespace App\Enums;

enum Bulan: string
{
    case Januari = 'Januari';
    case Februari = 'Februari';
    case Maret = 'Maret';
    case April = 'April';
    case Mei = 'Mei';
    case Juni = 'Juni';
    case Juli = 'Juli';
    case Agustus = 'Agustus';
    case September = 'September';
    case Oktober = 'Oktober';
    case November = 'November';
    case Desember = 'Desember';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
