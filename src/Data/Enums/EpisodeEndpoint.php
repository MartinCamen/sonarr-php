<?php

namespace MartinCamen\Sonarr\Data\Enums;

use MartinCamen\ArrCore\Contract\Endpoint;
use MartinCamen\ArrCore\Contract\ResolvesEndpointPath;

enum EpisodeEndpoint: string implements Endpoint
{
    use ResolvesEndpointPath;

    case All = 'episode';
    case ById = 'episode/{id}';

    public function defaultResponse(): mixed
    {
        return match ($this) {
            self::All  => [],
            self::ById => [],
        };
    }
}
