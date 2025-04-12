<?php

namespace App\Enums;

enum PerubahanModalJenisEnum:string
{
    case INVESTASI_PEMILIK = 'investasi_pemilik';
    case LABA_DITAHAN = 'laba_ditahan';
    case PENARIKAN_MODAL = 'penarikan_modal';

    public function label(): string
    {
        return match ($this) {
            self::INVESTASI_PEMILIK => 'Investasi Pemilik',
            self::LABA_DITAHAN => 'Laba Ditahan',
            self::PENARIKAN_MODAL => 'Penarikan Modal',
        };
    }
}
