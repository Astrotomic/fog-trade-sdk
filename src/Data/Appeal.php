<?php

namespace Astrotomic\FogTradeSdk\Data;

use Astrotomic\FogTradeSdk\Enums\AppealState;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use SteamID;
use Symfony\Component\DomCrawler\Crawler;

final class Appeal extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $type,
        public readonly string $appellant,
        public readonly ?string $alts,
        public readonly string $description,
        public readonly string $evidence,
        public readonly int $views,
        public readonly bool $locked,
        public readonly int $state_id,
        public readonly ?int $assignee_id,
        public readonly int $creator_id,
        public readonly int $modificator_id,
        #[WithCast(DateTimeInterfaceCast::class, format: ['d.m.Y H:i:s', 'Y-m-d H:i:s'])]
        public readonly CarbonImmutable $created_at,
        #[WithCast(DateTimeInterfaceCast::class, format: ['d.m.Y H:i:s', 'Y-m-d H:i:s'])]
        public readonly CarbonImmutable $updated_at,
        public readonly string $state,
        public readonly string $assignee,
        public readonly string $last_reply,
    ) {
    }

    public function state(): AppealState
    {
        return AppealState::from($this->state_id);
    }

    public function appellant(): ?SteamID
    {
        return rescue(fn () => new SteamID(explode(' ', (new Crawler($this->appellant))->text(), 2)[0]));
    }

    public function alts(): array
    {
        $urls = Str::of($this->alts)
            ->explode(PHP_EOL)
            ->filter(fn (string $line) => str_contains(Str::lower($line), 'steamid64:'));

        return $urls
            ->map(function (string $url): string {
                return (string) Str::of($url)
                    ->after(':')
                    ->afterLast('/profiles/')
                    ->trim();
            })
            ->filter()
            ->map(fn (string $steamid) => rescue(fn () => new SteamID($steamid)))
            ->filter()
            ->all();
    }

    public function evidences(): array
    {
        preg_match_all('/https?\:\/\/[^\",\s]+/i', $this->evidence, $match);

        return collect($match)
            ->flatten()
            ->map(fn (string $url) => trim(str_replace("\xE2\x80\x8B", '', $url)))
            ->unique()
            ->filter(fn (string $url) => filter_var($url, FILTER_VALIDATE_URL))
            ->values()
            ->all();
    }
}
