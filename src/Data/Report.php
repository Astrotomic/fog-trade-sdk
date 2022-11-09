<?php

namespace Astrotomic\FogTradeSdk\Data;

use Astrotomic\FogTradeSdk\Enums\ReportState;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use SteamID;
use Symfony\Component\DomCrawler\Crawler;

final class Report extends DataTransferObject
{
    public function __construct(
        public readonly int $id,
        public readonly string $type,
        public readonly string $victim, // ToDo
        public readonly string $accused, // ToDo
        public readonly string $description,
        public readonly string $evidence,
        public readonly int $views,
        public readonly bool $protected,
        public readonly bool $locked,
        public readonly int $state_id,
        public readonly ?int $assignee_id,
        public readonly int $creator_id,
        public readonly int $modificator_id,
        public readonly CarbonImmutable $created_at,
        public readonly CarbonImmutable $updated_at,
        public readonly string $state, // ToDo
        public readonly string $assignee, // ToDo
        public readonly string $last_reply, // ToDo
    ) {
    }

    public function state(): ReportState
    {
        return ReportState::from($this->state_id);
    }

    public function accused(): ?SteamID
    {
        return rescue(fn () => new SteamID(explode(' ', (new Crawler($this->accused))->text(), 2)[0]));
    }

    public function victim(): ?SteamID
    {
        $url = Str::of($this->victim)
            ->explode(PHP_EOL)
            ->first(fn (string $line) => str_contains(Str::lower($line), 'steamid64:'));

        $steamid = (string) Str::of($url)
            ->after(':')
            ->afterLast('/profiles/')
            ->trim();

        return rescue(fn () => new SteamID($steamid));
    }

    public function evidences(): array
    {
        preg_match_all('/https?\:\/\/[^\",\s]+/i', $this->evidence, $match);

        return collect($match)
            ->flatten()
            ->map(fn (string $url) => trim(str_replace("\xE2\x80\x8B", '', $url)))
            ->unique()
            ->values()
            ->all();
    }
}
