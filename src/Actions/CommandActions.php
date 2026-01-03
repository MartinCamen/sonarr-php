<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Actions\CommandActions as CoreCommandActions;
use MartinCamen\ArrCore\Data\Enums\CommandName;
use MartinCamen\ArrCore\Data\Responses\Command;

/** @link https://sonarr.tv/docs/api/ */
final readonly class CommandActions extends CoreCommandActions
{
    /**
     * Refresh all/specific series and rescan disk.
     *
     * @param array<int, int> $ids
     */
    public function refresh(array $ids = []): Command
    {
        $body = [];

        if ($ids !== []) {
            $body['seriesIds'] = $ids;
        }

        return $this->run(CommandName::RefreshSeries, $body);
    }

    /** Search for all missing episodes. */
    public function missing(): Command
    {
        return $this->run(CommandName::MissingEpisodeSearch);
    }

    /** Search for episodes below cutoff quality. */
    public function cutoffUnmet(): Command
    {
        return $this->run(CommandName::CutoffUnmetEpisodeSearch);
    }

    /**
     * Search for specific episodes.
     *
     * @param array<int, int> $episodeIds
     */
    public function searchEpisodes(array $episodeIds): Command
    {
        return $this->run(CommandName::EpisodeSearch, ['episodeIds' => $episodeIds]);
    }

    /** Search for all episodes in a season. */
    public function searchSeason(int $ids, int $seasonNumber): Command
    {
        return $this->run(CommandName::SeasonSearch, [
            'seriesId'     => $ids,
            'seasonNumber' => $seasonNumber,
        ]);
    }

    /** Search for all episodes in a series. */
    public function searchSeries(int $ids): Command
    {
        return $this->run(CommandName::SeriesSearch, ['seriesId' => $ids]);
    }

    /**
     * Rename series files.
     *
     * @param array<int, int> $ids
     */
    public function renameSeries(array $ids): Command
    {
        return $this->run(CommandName::RenameSeries, ['seriesIds' => $ids]);
    }

    /**
     * Rename specific episode files.
     *
     * @param array<int, int> $files
     */
    public function renameFiles(int $id, array $files): Command
    {
        return $this->run(CommandName::RenameFiles, [
            'seriesId' => $id,
            'files'    => $files,
        ]);
    }
}
