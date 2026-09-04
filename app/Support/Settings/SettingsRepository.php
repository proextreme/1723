<?php

namespace App\Support\Settings;

use App\Models\Setting;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * Reads and writes site configuration. The whole `settings` table is small and
 * read on nearly every request, so it is cached as one entry and busted on write.
 */
class SettingsRepository
{
    private const CACHE_KEY = 'settings.all';

    /**
     * @var array<string, array{value: ?string, type: string}>|null
     */
    private ?array $memo = null;

    public function __construct(private readonly Cache $cache) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        return $this->cast($all[$key]['value'], $all[$key]['type']);
    }

    public function set(string $key, mixed $value, string $type = 'string'): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $this->serialize($value, $type), 'type' => $type],
        );

        $this->flush();
    }

    public function flush(): void
    {
        $this->memo = null;
        $this->cache->forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, array{value: ?string, type: string}>
     */
    private function all(): array
    {
        return $this->memo ??= $this->cache->rememberForever(self::CACHE_KEY, fn (): array => Setting::query()
            ->get(['key', 'value', 'type'])
            ->keyBy('key')
            ->map(fn (Setting $setting): array => [
                'value' => $setting->value,
                'type' => $setting->type,
            ])
            ->all());
    }

    private function cast(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOL),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    private function serialize(mixed $value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value,
        };
    }
}
