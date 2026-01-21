<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr\Client;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\DownloadActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Actions\SeriesActions;
use MartinCamen\Sonarr\Config\SonarrConfiguration;

/**
 * Low-level Sonarr API client.
 *
 * This class provides direct access to the Sonarr REST API using
 * Sonarr's native terminology (queue, series, episode, etc.).
 *
 * For most use cases, prefer using the high-level Sonarr class instead,
 * which provides a unified API with Core domain models.
 *
 * @internal This class is primarily for internal use. Use Sonarr class for public API.
 *
 * @link https://sonarr.tv/docs/api/
 */
class SonarrApiClient implements SonarrApiClientInterface
{
    protected RestClientInterface $client;
    protected ?SeriesActions $seriesActions = null;
    protected ?EpisodeActions $episodeActions = null;
    protected ?EpisodeFileActions $episodeFileActions = null;
    protected ?DownloadActions $downloadActions = null;
    protected ?HistoryActions $historyActions = null;
    protected ?CalendarActions $calendarActions = null;
    protected ?SystemActions $systemActions = null;
    protected ?CommandActions $commandActions = null;
    protected ?WantedActions $wantedActions = null;

    public function __construct(
        string $host = 'localhost',
        int $port = 8989,
        string $apiKey = '',
        bool $useHttps = false,
        int $timeout = 30,
        string $urlBase = '',
        string $apiVersion = 'v3',
        ?RestClientInterface $restClient = null,
    ) {
        $config = new SonarrConfiguration(
            host: $host,
            port: $port,
            apiKey: $apiKey,
            useHttps: $useHttps,
            timeout: $timeout,
            urlBase: $urlBase,
            apiVersion: $apiVersion,
        );

        $this->client = $restClient ?? new SonarrRestClient($config);
    }

    public static function make(SonarrConfiguration $config): self
    {
        return new self(
            host: $config->host,
            port: $config->port,
            apiKey: $config->apiKey,
            useHttps: $config->useHttps,
            timeout: $config->timeout,
            urlBase: $config->urlBase,
            apiVersion: $config->apiVersion,
        );
    }

    public function series(): SeriesActions
    {
        return $this->seriesActions ??= new SeriesActions($this->client);
    }

    public function episode(): EpisodeActions
    {
        return $this->episodeActions ??= new EpisodeActions($this->client);
    }

    public function episodeFile(): EpisodeFileActions
    {
        return $this->episodeFileActions ??= new EpisodeFileActions($this->client);
    }

    public function downloads(): DownloadActions
    {
        return $this->downloadActions ??= new DownloadActions($this->client);
    }

    public function history(): HistoryActions
    {
        return $this->historyActions ??= new HistoryActions($this->client);
    }

    public function calendar(): CalendarActions
    {
        return $this->calendarActions ??= new CalendarActions($this->client);
    }

    public function system(): SystemActions
    {
        return $this->systemActions ??= new SystemActions($this->client);
    }

    public function command(): CommandActions
    {
        return $this->commandActions ??= new CommandActions($this->client);
    }

    public function wanted(): WantedActions
    {
        return $this->wantedActions ??= new WantedActions($this->client);
    }

    public function getClient(): RestClientInterface
    {
        return $this->client;
    }
}
