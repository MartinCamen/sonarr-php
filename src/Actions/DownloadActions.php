<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Actions\DownloadActions as CoreDownloadActions;
use MartinCamen\ArrCore\Data\Enums\QueueEndpoint;
use MartinCamen\ArrCore\Data\Options\PaginationOptions;
use MartinCamen\ArrCore\Data\Options\SortOptions;
use MartinCamen\Sonarr\Data\Options\DownloadOptions;
use MartinCamen\Sonarr\Data\Responses\Download;
use MartinCamen\Sonarr\Data\Responses\DownloadPage;

/** @link https://sonarr.tv/docs/api/ */
final readonly class DownloadActions extends CoreDownloadActions
{
    /** Get paginated downloads */
    public function all(
        ?PaginationOptions $pagination = null,
        ?SortOptions $sort = null,
        ?DownloadOptions $filters = null,
    ): DownloadPage {
        return DownloadPage::fromArray($this->getAll($pagination, $sort, $filters));
    }

    /** Get download by ID */
    public function find(int $id): Download
    {
        return Download::fromArray(
            $this->client->get(QueueEndpoint::ById, ['id' => $id]),
        );
    }
}
