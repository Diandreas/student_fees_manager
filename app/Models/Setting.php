<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Récupérer un paramètre par sa clé.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            return $setting->value;
        }
        
        return $default;
    }

    /**
     * Définir ou mettre à jour un paramètre.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function set($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
} 