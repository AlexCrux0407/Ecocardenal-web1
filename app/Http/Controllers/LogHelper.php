<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class LogHelper
{
    /**
     * Registra un mensaje de información en el log
     */
    public static function info($message)
    {
        Log::info($message);
    }
    
    /**
     * Registra un mensaje de depuración en el log
     */
    public static function debug($message)
    {
        Log::debug($message);
    }
    
    /**
     * Registra un mensaje de error en el log
     */
    public static function error($message)
    {
        Log::error($message);
    }
    
    /**
     * Registra un mensaje de advertencia en el log
     */
    public static function warning($message)
    {
        Log::warning($message);
    }
}