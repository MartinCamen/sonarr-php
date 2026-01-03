<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Actions\CalendarActions as CoreCalendarActions;
use MartinCamen\Sonarr\Data\Options\CalendarOptions;
use MartinCamen\Sonarr\Data\Responses\Episode;
use MartinCamen\Sonarr\Data\Responses\EpisodeCollection;

/** @link https://sonarr.tv/docs/api/ */
final readonly class CalendarActions extends CoreCalendarActions
{
    /**
     * Get upcoming episodes within a date range.
     */
    public function all(?CalendarOptions $options = null): EpisodeCollection
    {
        return EpisodeCollection::fromArray($this->getAll($options));
    }

    /**
     * Get calendar event by ID.
     */
    public function find(int $id): Episode
    {
        return Episode::fromArray($this->getById($id));
    }
}
