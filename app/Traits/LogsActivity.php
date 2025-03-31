<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        $user = Auth::user();
        $changes = $action === 'updated' ? $model->getChanges() : null;
        $original = $action === 'updated' ? $model->getOriginal() : null;

        ActivityLog::create([
            'user_type' => $user ? get_class($user) : null,
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => self::getActivityDescription($action, $model),
            'old_values' => $original,
            'new_values' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected static function getActivityDescription($action, $model)
    {
        $modelName = class_basename($model);
        
        switch ($action) {
            case 'created':
                return "Un nouveau {$modelName} a été créé";
            case 'updated':
                return "Le {$modelName} a été modifié";
            case 'deleted':
                return "Le {$modelName} a été supprimé";
            default:
                return "Une action a été effectuée sur le {$modelName}";
        }
    }
} 