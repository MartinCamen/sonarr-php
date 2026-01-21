<?php

declare(strict_types=1);

namespace MartinCamen\Sonarr\Mapper;

use MartinCamen\ArrCore\Domain\Download\DownloadItem;
use MartinCamen\ArrCore\Domain\Download\DownloadItemCollection;
use MartinCamen\ArrCore\Domain\Media\Series;
use MartinCamen\ArrCore\Domain\System\DownloadServiceSystemSummary;
use MartinCamen\ArrCore\Domain\System\HealthCheck;
use MartinCamen\ArrCore\Domain\System\SystemSummary;
use MartinCamen\ArrCore\Enum\Service;
use MartinCamen\ArrCore\Mapping\ServiceToCoreMapper;
use MartinCamen\ArrCore\Mapping\StatusNormalizer;
use MartinCamen\ArrCore\ValueObject\ArrFileSize;
use MartinCamen\ArrCore\ValueObject\ArrId;
use MartinCamen\ArrCore\ValueObject\Progress;
use MartinCamen\Sonarr\Data\Responses\Download;
use MartinCamen\Sonarr\Data\Responses\DownloadPage;
use MartinCamen\Sonarr\Data\Responses\Series as SonarrSeries;
use MartinCamen\Sonarr\Data\Responses\SeriesCollection;

/**
 * Maps Sonarr DTOs to php-arr-core domain models.
 *
 * This class provides pure, deterministic transformations from
 * Sonarr-specific data structures to canonical core models.
 */
final class SonarrToCoreMapper extends ServiceToCoreMapper
{
    /** Map Sonarr Series DTO to Core Series model */
    public static function mapSeries(SonarrSeries $dto): Series
    {
        $hasFiles = $dto->episodeFileCount > 0;

        return new Series(
            id: ArrId::fromInt($dto->id),
            title: $dto->title,
            year: $dto->year,
            status: StatusNormalizer::mediaFromSonarr($dto->status->value, $hasFiles),
            monitored: $dto->monitored,
            source: Service::Sonarr,
            sizeOnDisk: ArrFileSize::fromBytes($dto->sizeOnDisk),
            path: $dto->path,
            overview: $dto->overview,
            posterUrl: self::extractImage($dto->images, 'poster'),
            fanartUrl: self::extractImage($dto->images, 'fanart'),
            tvdbId: $dto->tvdbId,
            imdbId: $dto->imdbId,
            tvMazeId: $dto->tvMazeId,
            network: $dto->network,
            runtime: $dto->runtime,
            certification: $dto->certification,
            seasonCount: count($dto->seasons),
            episodeCount: $dto->episodeCount,
            episodeFileCount: $dto->episodeFileCount,
            seriesType: $dto->ended ? 'ended' : 'continuing',
        );
    }

    /**
     * Map Sonarr Series collection to array of Core Series.
     *
     * @return array<int, Series>
     */
    public static function mapSeriesCollection(SeriesCollection $collection): array
    {
        return array_map(
            self::mapSeries(...),
            $collection->all(),
        );
    }

    /** Map Sonarr Download to Core DownloadItem */
    public static function mapDownload(Download $dto): DownloadItem
    {
        $size = $dto->size;
        $sizeLeft = $dto->sizeLeft;
        $progress = $size > 0 ? $dto->getProgress() : 0;

        return new DownloadItem(
            id: ArrId::fromInt($dto->id),
            name: $dto->title ?? 'Unknown',
            size: ArrFileSize::fromBytes((int) $size),
            sizeRemaining: ArrFileSize::fromBytes((int) $sizeLeft),
            progress: Progress::fromPercentage($progress),
            status: StatusNormalizer::downloadFromSonarrQueue(
                $dto->status,
                $dto->trackedDownloadStatus,
            ),
            source: Service::Radarr,
            eta: $dto->timeLeft !== null ? self::parseTimeSpan($dto->timeLeft) : null,
            downloadClient: $dto->downloadClient,
            indexer: $dto->indexer,
            outputPath: $dto->outputPath,
            mediaId: $dto->seriesId !== null ? ArrId::fromInt($dto->seriesId) : null,
            mediaTitle: $dto->series['title'] ?? null,
            errorMessage: $dto->errorMessage,
        );
    }

    /** Map Sonarr DownloadPage to Core DownloadItemCollection */
    public static function mapDownloadPage(DownloadPage $dto): DownloadItemCollection
    {
        $items = array_map(
            self::mapDownload(...),
            $dto->all(),
        );

        return new DownloadItemCollection(...$items);
    }

    /**
     * Map Sonarr SystemSummary to Core SystemSummary.
     *
     * @param array<int, HealthCheck> $healthChecks
     */
    public static function mapSystemSummary(DownloadServiceSystemSummary $dto, array $healthChecks = []): SystemSummary
    {
        return self::mapToSystemSummary(Service::Sonarr, $dto, $healthChecks);
    }
}
