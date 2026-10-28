<?php

namespace App\Enums;

enum EstadoAsistencia: string
{
    case PRESENTE = 'presente';
    case AUSENTE = 'ausente';
    case JUSTIFICADA = 'justificada';
    case TARDE = 'tarde';
    case IGNORAR = 'ignorar'; // <-- NUEVO ESTADO AÑADIDO OFICIALMENTE

    // Utilidad para renderizar los componentes visuales
    public function label(): string
    {
        return match($this) {
            self::PRESENTE => 'Presente',
            self::AUSENTE => 'Ausente',
            self::JUSTIFICADA => 'Justificada',
            self::TARDE => 'Tarde',
            self::IGNORAR => 'Ignorado', // <-- LABEL ACTUALIZADO
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PRESENTE => 'success',
            self::AUSENTE => 'danger',
            self::JUSTIFICADA => 'warning', 
            self::TARDE => 'info',
            self::IGNORAR => 'primary', // <-- COLOR AZUL MANTENIDO
        };
    }
}