<?php

namespace MartinCamen\Sonarr\Data\Enums;

enum SeriesType: string
{
    case Standard = 'standard';
    case Daily = 'daily';
    case Anime = 'anime';
}
