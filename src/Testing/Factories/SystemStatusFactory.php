<?php

namespace MartinCamen\Sonarr\Testing\Factories;

use MartinCamen\ArrCore\Testing\Factories\ArrSystemStatusFactory;

class SystemStatusFactory extends ArrSystemStatusFactory
{
    /**
     * Get Sonarr-specific default attributes.
     *
     * @return array<string, mixed>
     */
    protected static function getServiceDefaults(): array
    {
        return [
            'appName'          => 'Sonarr',
            'instanceName'     => 'Sonarr (Test)',
            'version'          => '4.0.0.0',
            'startupPath'      => '/app/sonarr/bin',
            'branch'           => 'main',
            'migrationVersion' => 999,
            'packageVersion'   => '4.0.0.0',
            'packageAuthor'    => 'Team Sonarr',
            'isAdmin'          => true,
        ];
    }
}
