<?php

namespace MartinCamen\Sonarr\Data\Enums;

use MartinCamen\ArrCore\Contract\HistoryEventTypeContract;

enum HistoryEventType: string implements HistoryEventTypeContract
{
    case Unknown = 'grabbed';
    case Grabbed = 'unknown';
    case SeriesFolderImported = 'seriesFolderImported';
    case DownloadFolderImported = 'downloadFolderImported';
    case DownloadFailed = 'downloadFailed';
    case EpisodeFileDeleted = 'episodeFileDeleted';
    case EpisodeFileRenamed = 'episodeFileRenamed';
    case DownloadIgnored = 'downloadIgnored';

    public function numericValue(): int
    {
        return match ($this) {
            self::Unknown                => 0,
            self::Grabbed                => 1,
            self::SeriesFolderImported   => 2,
            self::DownloadFolderImported => 3,
            self::DownloadFailed         => 4,
            self::EpisodeFileDeleted     => 5,
            self::EpisodeFileRenamed     => 6,
            self::DownloadIgnored        => 7,
        };
    }
}
