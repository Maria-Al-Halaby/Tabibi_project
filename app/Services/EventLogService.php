<?php

namespace App\Services;

use App\Models\EventLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EventLogService
{
    private const SENSITIVE_KEYS = [
        'authorization',
        'fcm_token',
        'otp',
        'password',
        'remember_token',
        'secret',
        'token',
    ];

    public static function record(Model $model, string $status, ?string $message = null, ?array $parameters = null): void
    {
        if ($model instanceof EventLog || ! in_array($status, ['add', 'delete'], true)) {
            return;
        }

        $actor = Auth::user();
        $tableName = $model->getTable();
        $modelId = $model->getKey();

        try {
            EventLog::withoutEvents(function () use ($actor, $model, $status, $message, $parameters, $tableName, $modelId): void {
                EventLog::create([
                    'user_id' => $actor?->getAuthIdentifier(),
                    'user_name' => self::actorName($actor),
                    'user_role' => self::actorRole($actor),
                    'table_name' => $tableName,
                    'model_type' => $model::class,
                    'model_id' => $modelId === null ? null : (string) $modelId,
                    'status' => $status,
                    'message' => $message ?: self::message($actor, $status, $tableName, $modelId),
                    'parameters' => $parameters ?: self::parameters($model, $status),
                    'ip_address' => app()->runningInConsole() ? null : request()->ip(),
                    'user_agent' => app()->runningInConsole() ? null : request()->userAgent(),
                ]);
            });
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private static function actorName(?Authenticatable $actor): ?string
    {
        if (! $actor) {
            return null;
        }

        $name = trim((string) ($actor->name ?? '').' '.(string) ($actor->last_name ?? ''));

        return $name !== '' ? $name : ($actor->email ?? null);
    }

    private static function actorRole(?Authenticatable $actor): ?string
    {
        if (! $actor || ! method_exists($actor, 'getRoleNames')) {
            return null;
        }

        $roles = $actor->getRoleNames();

        return $roles->isEmpty() ? null : $roles->implode(', ');
    }

    private static function message(?Authenticatable $actor, string $status, string $tableName, mixed $modelId): string
    {
        $action = $status === 'add' ? 'added' : 'deleted';
        $actorName = self::actorName($actor) ?: 'System';
        $actorRole = self::actorRole($actor);
        $roleText = $actorRole ? " ({$actorRole})" : '';
        $recordText = $modelId === null ? 'a record' : "record #{$modelId}";

        return "{$actorName}{$roleText} {$action} {$recordText} in {$tableName}.";
    }

    private static function parameters(Model $model, string $status): array
    {
        $attributes = $status === 'delete'
            ? $model->getOriginal()
            : $model->getAttributes();

        return self::sanitize($attributes);
    }

    private static function sanitize(array $parameters): array
    {
        $clean = [];

        foreach ($parameters as $key => $value) {
            $clean[$key] = self::isSensitiveKey((string) $key)
                ? '[FILTERED]'
                : (is_array($value) ? self::sanitize($value) : $value);
        }

        return $clean;
    }

    private static function isSensitiveKey(string $key): bool
    {
        $key = strtolower($key);

        foreach (self::SENSITIVE_KEYS as $sensitiveKey) {
            if (str_contains($key, $sensitiveKey)) {
                return true;
            }
        }

        return false;
    }
}
