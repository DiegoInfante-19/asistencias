<?php
namespace App\Enums;
enum NivelAcademico: string{
    
    case TSU = 'TSU';
    case INGENIERIA = 'Ingeniería';

    // Utilidad para imprimir etiquetas formales en las vistas Blade o respuestas JSON
    public function label(): string{
        return match($this) {
            self::TSU => 'Técnico Superior Universitario',
            self::INGENIERIA => 'Ingeniería',
        };
    }

    // Utilidad para renderizar Badges de Bootstrap dinámicamente
    public function color(): string{
        return match($this) {
            self::TSU => 'primary',
            self::INGENIERIA => 'success',
        };
    }
}