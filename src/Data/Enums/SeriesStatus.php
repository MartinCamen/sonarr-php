<?php

namespace MartinCamen\Sonarr\Data\Enums;

enum SeriesStatus: string
{
    case Continuing = 'continuing';
    case Ended = 'ended';
    case Upcoming = 'upcoming';
    case Deleted = 'deleted';
    case Unknown = 'unknown';
}
