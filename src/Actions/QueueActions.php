<?php

namespace MartinCamen\Sonarr\Actions;

use MartinCamen\ArrCore\Actions\QueueActions as CoreQueueActions;
use MartinCamen\ArrCore\Data\Enums\QueueEndpoint;
use MartinCamen\ArrCore\Data\Options\PaginationOptions;
use MartinCamen\ArrCore\Data\Options\SortOptions;
use MartinCamen\Sonarr\Data\Options\QueueOptions;
use MartinCamen\Sonarr\Data\Responses\QueuePage;
use MartinCamen\Sonarr\Data\Responses\QueueRecord;

/** @link https://sonarr.tv/docs/api/ */
final readonly class QueueActions extends CoreQueueActions
{
    /** Get paginated queue */
    public function all(
        ?PaginationOptions $pagination = null,
        ?SortOptions $sort = null,
        ?QueueOptions $filters = null,
    ): QueuePage {
        return QueuePage::fromArray($this->getAll($pagination, $sort, $filters));
    }

    /** Get queue item by ID */
    public function find(int $id): QueueRecord
    {
        return QueueRecord::fromArray(
            $this->client->get(QueueEndpoint::ById, ['id' => $id]),
        );
    }
}
