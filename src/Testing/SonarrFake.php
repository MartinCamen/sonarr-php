<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr\Testing;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Domain\Download\DownloadItemCollection;
use MartinCamen\ArrCore\Domain\Media\Series;
use MartinCamen\ArrCore\Domain\System\SystemSummary;
use MartinCamen\ArrCore\Testing\BaseFake;
use MartinCamen\ArrCore\Testing\Traits\FakesArrDownloadServices;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Client\SonarrApiClientInterface;
use MartinCamen\Sonarr\Data\Responses\QueuePage;
use MartinCamen\Sonarr\Data\Responses\SeriesCollection;
use MartinCamen\Sonarr\Mapper\SonarrToCoreMapper;
use MartinCamen\Sonarr\SonarrInterface;
use MartinCamen\Sonarr\Testing\Factories\SeriesFactory;

/**
 * Fake implementation for testing.
 *
 * Provides the same interface as Sonarr SDK but allows
 * custom responses and tracks method calls for assertions.
 *
 * @example
 * ```php
 * $fake = new SonarrFake([
 *     'series' => SeriesFactory::makeMany(5),
 * ]);
 *
 * $series = $fake->series();
 * $fake->assertCalled('series');
 * ```
 */
final class SonarrFake extends BaseFake implements SonarrInterface
{
    use FakesArrDownloadServices;

    private ?SonarrApiFake $apiFake = null;

    /**
     * Get all active downloads.
     */
    public function downloads(): DownloadItemCollection
    {
        $this->recordCall('downloads', []);

        $queueData = $this->formatsDownloads();

        return SonarrToCoreMapper::mapQueuePage(
            QueuePage::fromArray($queueData),
        );
    }

    /**
     * Get all series.
     *
     * @return array<int, Series>
     */
    public function series(): array
    {
        $this->recordCall('series', []);

        $series = isset($this->responses['series'])
            ? SeriesCollection::fromArray($this->responses['series'])
            : SeriesCollection::fromArray(SeriesFactory::makeMany(3));

        return SonarrToCoreMapper::mapSeriesCollection($series);
    }

    /**
     * Get a single series by ID.
     */
    public function seriesById(int $id): Series
    {
        $this->recordCall('seriesById', ['id' => $id]);

        if (isset($this->responses["seriesById/{$id}"])) {
            $series = \MartinCamen\Sonarr\Data\Responses\Series::fromArray($this->responses["seriesById/{$id}"]);
        } elseif (isset($this->responses['seriesById'])) {
            $series = \MartinCamen\Sonarr\Data\Responses\Series::fromArray($this->responses['seriesById']);
        } else {
            $series = \MartinCamen\Sonarr\Data\Responses\Series::fromArray(SeriesFactory::make($id));
        }

        return SonarrToCoreMapper::mapSeries($series);
    }

    /**
     * Get system status.
     */
    public function system(): SystemActions
    {
        $this->recordCall('system', []);

        return $this->api()->system();
    }

    /**
     * Get system summary.
     */
    public function systemSummary(): SystemSummary
    {
        $this->recordCall('systemSummary', []);

        $status = $this->getStatusForDownloadServiceSystemSummary();
        $health = $this->getHealthForDownloadServiceSystemSummary();

        return SonarrToCoreMapper::mapSystemSummary($status, $health->all());
    }

    /**
     * Access episode functionality.
     */
    public function episode(): EpisodeActions
    {
        $this->recordCall('episode', []);

        return $this->api()->episode();
    }

    /**
     * Access episode file functionality.
     */
    public function episodeFile(): EpisodeFileActions
    {
        $this->recordCall('episodeFile', []);

        return $this->api()->episodeFile();
    }

    /**
     * Access calendar functionality.
     */
    public function calendar(): CalendarActions
    {
        $this->recordCall('calendar', []);

        return $this->api()->calendar();
    }

    /**
     * Access history functionality.
     */
    public function history(): HistoryActions
    {
        $this->recordCall('history', []);

        return $this->api()->history();
    }

    /**
     * Access wanted functionality.
     */
    public function wanted(): WantedActions
    {
        $this->recordCall('wanted', []);

        return $this->api()->wanted();
    }

    /**
     * Access command functionality.
     */
    public function command(): CommandActions
    {
        $this->recordCall('command', []);

        return $this->api()->command();
    }

    /**
     * Get the underlying API client fake for advanced operations.
     */
    public function api(): SonarrApiClientInterface
    {
        return $this->apiFake ??= new SonarrApiFake();
    }
}
