<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PreguntaSecreta extends Model{
    
protected $table = 'preguntas_secretas';
    protected $primaryKey = 'id_preguntas_secretas';
    public $timestamps = false; 
    protected $fillable = [
        'id_users',
        'pregunta1',
        'pregunta2',
        'respuesta1',
        'respuesta2',
    ];
    
    public function user(){
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public static function listaPreguntas1(){
        return [
            1 => '¿Cuál es tu cantante favorito?',
            2 => '¿Cuál es tu libro favorito?',
            3 => '¿Cuál sería tu trabajo ideal?',
            4 => '¿Cuál es tu película favorita?',
            5 => '¿Cuál es tu personaje favorito?',
        ];
    }

    public static function listaPreguntas2(){
        return [
            6 => '¿Cómo se llama tu mejor amigo?',
            7 => '¿Cómo se llama tu primera mascota?',
            8 => '¿Cómo se llama tu equipo favorito?',
            9 => '¿Cómo se llama la calle donde creciste?',
            10 => '¿Cómo se llama tu plato favorito?',
        ];
    }
}