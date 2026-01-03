<?php

namespace MartinCamen\Sonarr\Config;

use MartinCamen\ArrCore\Config\ArrServiceConfiguration;
use MartinCamen\ArrCore\Contract\ArrServiceConfigurationContract;

class SonarrConfiguration extends ArrServiceConfiguration implements ArrServiceConfigurationContract
{
    public int $port = 8989;
    public string $apiVersion = 'v3';
}
