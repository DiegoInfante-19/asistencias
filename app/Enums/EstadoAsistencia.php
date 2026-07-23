<?php

namespace App\Enums;

enum EstadoAsistencia: string
{
    case PRESENTE = 'presente';
    case AUSENTE = 'ausente';
    case JUSTIFICADA = 'justificada';
    case TARDE = 'tarde';

    // Utilidad para renderizar los componentes visuales (Badges Bootstrap)
    public function label(): string
    {
        return match($this) {
            self::PRESENTE => 'Presente',
            self::AUSENTE => 'Ausente',
            self::JUSTIFICADA => 'Justificada',
            self::TARDE => 'Tarde',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PRESENTE => 'success',
            self::AUSENTE => 'danger',
            self::JUSTIFICADA => 'warning text-dark',
            self::TARDE => 'info text-dark',
        };
    }
}