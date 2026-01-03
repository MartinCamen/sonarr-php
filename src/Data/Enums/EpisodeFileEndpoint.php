<?php

namespace MartinCamen\Sonarr\Data\Enums;

use MartinCamen\ArrCore\Contract\Endpoint;
use MartinCamen\ArrCore\Contract\ResolvesEndpointPath;

enum EpisodeFileEndpoint: string implements Endpoint
{
    use ResolvesEndpointPath;

    case All = 'episodefile';
    case ById = 'episodefile/{id}';
    case Bulk = 'episodefile/bulk';

    /** @return array<int, mixed> */
    public function defaultResponse(): array
    {
        return match ($this) {
            self::All, self::ById, self::Bulk => [],
        };
    }
}
