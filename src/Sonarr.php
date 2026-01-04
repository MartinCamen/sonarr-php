<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Domain\Download\DownloadItemCollection;
use MartinCamen\ArrCore\Domain\Media\Series;
use MartinCamen\ArrCore\Domain\System\SystemSummary;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Client\SonarrApiClient;
use MartinCamen\Sonarr\Client\SonarrApiClientInterface;
use MartinCamen\Sonarr\Config\SonarrConfiguration;
use MartinCamen\Sonarr\Mapper\SonarrToCoreMapper;

/**
 * Sonarr SDK client - the primary interface for interacting with Sonarr.
 *
 * This class provides a unified API with Core domain models, making it
 * easy to work with Sonarr data in a type-safe, cross-service compatible way.
 *
 * @example Basic usage:
 * ```php
 * $sonarr = Sonarr::create(
 *     host: 'localhost',
 *     port: 8989,
 *     apiKey: 'your-api-key',
 * );
 *
 * // Get all downloads (queue items)
 * $downloads = $sonarr->downloads();
 *
 * // Get all series
 * $series = $sonarr->series();
 *
 * // Get system status
 * $status = $sonarr->system()->status();
 * ```
 */
class Sonarr implements SonarrInterface
{
    public function __construct(private readonly SonarrApiClientInterface $apiClient) {}

    /**
     * Create a new Sonarr instance from connection parameters.
     */
    public static function create(
        string $host = 'localhost',
        int $port = 8989,
        string $apiKey = '',
        bool $useHttps = false,
        int $timeout = 30,
        string $urlBase = '',
        string $apiVersion = 'v3',
    ): self {
        return new self(
            new SonarrApiClient(
                host: $host,
                port: $port,
                apiKey: $apiKey,
                useHttps: $useHttps,
                timeout: $timeout,
                urlBase: $urlBase,
                apiVersion: $apiVersion,
            ),
        );
    }

    /**
     * Create a new Sonarr instance from a configuration object.
     */
    public static function make(SonarrConfiguration $config): self
    {
        return new self(SonarrApiClient::make($config));
    }

    /**
     * Get all active downloads (queue items).
     *
     * Returns a collection of download items mapped to Core domain models,
     * providing a unified interface across all *arr services.
     */
    public function downloads(): DownloadItemCollection
    {
        $queue = $this->apiClient->queue()->all();

        return SonarrToCoreMapper::mapQueuePage($queue);
    }

    /**
     * Get all series.
     *
     * @return array<int, Series>
     */
    public function series(): array
    {
        $series = $this->apiClient->series()->all();

        return SonarrToCoreMapper::mapSeriesCollection($series);
    }

    /**
     * Get a single series by ID.
     */
    public function seriesById(int $id): Series
    {
        $series = $this->apiClient->series()->find($id);

        return SonarrToCoreMapper::mapSeries($series);
    }

    /**
     * Access system functionality.
     *
     * Provides access to system information, health information, and more.
     */
    public function system(): SystemActions
    {
        return $this->apiClient->system();
    }

    /**
     * Get system status including health checks.
     */
    public function systemSummary(): SystemSummary
    {
        $status = $this->apiClient->system()->status();
        $health = $this->apiClient->system()->health();

        return SonarrToCoreMapper::mapSystemSummary($status, $health->all());
    }

    /**
     * Access episode functionality.
     *
     * Provides access to episode information and management.
     */
    public function episode(): EpisodeActions
    {
        return $this->apiClient->episode();
    }

    /**
     * Access episode file functionality.
     *
     * Provides access to episode file information and management.
     */
    public function episodeFile(): EpisodeFileActions
    {
        return $this->apiClient->episodeFile();
    }

    /**
     * Access calendar functionality.
     *
     * Provides access to upcoming episode releases and calendar events.
     */
    public function calendar(): CalendarActions
    {
        return $this->apiClient->calendar();
    }

    /**
     * Access history functionality.
     *
     * Provides access to download history, grabbed items, and import events.
     */
    public function history(): HistoryActions
    {
        return $this->apiClient->history();
    }

    /**
     * Access wanted functionality.
     *
     * Provides access to missing episodes and episodes below quality cutoff.
     */
    public function wanted(): WantedActions
    {
        return $this->apiClient->wanted();
    }

    /**
     * Access command functionality.
     *
     * Allows execution of Sonarr commands like series search, refresh, etc.
     */
    public function command(): CommandActions
    {
        return $this->apiClient->command();
    }

    /**
     * Get the underlying API client for advanced operations.
     *
     * Use this when you need direct access to Sonarr API functionality
     * that is not yet exposed through the high-level SDK methods.
     *
     * @example
     * ```php
     * // Access the raw queue API with full options
     * $queuePage = $sonarr->api()->queue()->all($pagination, $sort, $filters);
     *
     * // Add a series using the raw API
     * $sonarr->api()->series()->add($seriesData);
     * ```
     */
    public function api(): SonarrApiClientInterface
    {
        return $this->apiClient;
    }
}
