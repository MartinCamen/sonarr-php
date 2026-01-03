<?php

namespace MartinCamen\Sonarr\Actions;

use DateTimeInterface;
use MartinCamen\ArrCore\Actions\HistoryActions as CoreHistoryActions;
use MartinCamen\ArrCore\Data\Enums\HistoryEndpoint;
use MartinCamen\ArrCore\Data\Options\PaginationOptions;
use MartinCamen\ArrCore\Data\Options\SortOptions;
use MartinCamen\Sonarr\Data\Enums\HistoryEventType;
use MartinCamen\Sonarr\Data\Options\HistoryOptions;
use MartinCamen\Sonarr\Data\Responses\HistoryPage;
use MartinCamen\Sonarr\Data\Responses\HistoryRecord;

/** @link https://sonarr.tv/docs/api/#v3/tag/history */
final readonly class HistoryActions extends CoreHistoryActions
{
    /** Get paginated history. */
    public function all(
        ?PaginationOptions $pagination = null,
        ?SortOptions $sort = null,
        ?HistoryOptions $filters = null,
    ): HistoryPage {
        $requestFilters = $filters?->toArray() ?? [];

        if (($eventType = $filters?->eventType) instanceof HistoryEventType) {
            $requestFilters['eventType'] = $eventType->numericValue();
        }

        return HistoryPage::fromArray(
            $this->getAll($pagination, $sort, $requestFilters),
        );
    }

    /**
     * Get history since a specific date.
     *
     * @return array<string, HistoryRecord>
     */
    public function since(
        DateTimeInterface $date,
        ?HistoryOptions $filters = null,
    ): array {
        return array_map(
            HistoryRecord::fromArray(...),
            $this->getAllSince($date, $filters),
        );
    }

    /**
     * Get history for a specific series.
     *
     * @return array<int, HistoryRecord>
     */
    public function find(
        int $id,
        ?HistoryOptions $filters = null,
    ): array {
        $params = array_merge(
            ['seriesId' => $id],
            $filters?->toArray() ?? [],
        );

        $result = $this->client->get(HistoryEndpoint::Series, $params);

        return array_map(
            HistoryRecord::fromArray(...),
            $result,
        );
    }
}
