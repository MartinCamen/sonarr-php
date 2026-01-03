<?php

namespace MartinCamen\Sonarr\Data\Enums;

use MartinCamen\ArrCore\Contract\Endpoint;
use MartinCamen\ArrCore\Contract\ResolvesEndpointPath;

enum SeriesEndpoint: string implements Endpoint
{
    use ResolvesEndpointPath;

    case All = 'series';
    case ById = 'series/{id}';
    case Lookup = 'series/lookup';
    case LookupTvdb = 'series/lookup/tvdb';

    public function defaultResponse(): mixed
    {
        return match ($this) {
            self::All, self::Lookup, self::LookupTvdb => [],
            self::ById => [],
        };
    }
}
