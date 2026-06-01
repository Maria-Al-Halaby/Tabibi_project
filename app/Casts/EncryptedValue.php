<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EncryptedValue implements CastsAttributes
{
    public function __construct(private readonly string $type = 'string') {}

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->castFromStorage($this->decryptIfPossible($value));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return Crypt::encryptString($this->prepareForStorage($value));
    }

    private function decryptIfPossible(mixed $value): string
    {
        $value = (string) $value;

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException) {
            return $value;
        }
    }

    private function castFromStorage(string $value): mixed
    {
        return match ($this->type) {
            'array', 'json' => $this->decodeArray($value),
            'integer', 'int' => (int) $value,
            'float', 'double' => (float) $value,
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            default => $value,
        };
    }

    private function prepareForStorage(mixed $value): string
    {
        if (in_array($this->type, ['array', 'json'], true)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        }

        if (in_array($this->type, ['boolean', 'bool'], true)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }

    private function decodeArray(string $value): array
    {
        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($decoded)
            ? $decoded
            : [];
    }
}
