# Sonarr PHP SDK

A PHP SDK for the Sonarr REST API v3.

> [!IMPORTANT]
> This project is still being developed and breaking changes might occur even between patch versions.
>
> The aim is to follow semantic versioning as soon as possible.

## Ecosystem

| Package                                                                 | Description                        |
|-------------------------------------------------------------------------|------------------------------------|
| [radarr-php](https://github.com/martincamen/radarr-php)                 | PHP SDK for Radarr                 |
| [sonarr-php](https://github.com/martincamen/sonarr-php)                 | PHP SDK for Sonarr                 |
| [jellyseerr-php](https://github.com/martincamen/jellyseerr-php)         | PHP SDK for Jellyseerr             |
| [laravel-radarr](https://github.com/martincamen/laravel-radarr)         | Laravel integration for Radarr     |
| [laravel-sonarr](https://github.com/martincamen/laravel-sonarr)         | Laravel integration for Sonarr     |
| [laravel-jellyseerr](https://github.com/martincamen/laravel-jellyseerr) | Laravel integration for Jellyseerr |


## Requirements

- PHP 8.3+

## Installation

```bash
composer require martincamen/sonarr-php
```

## Quick Start

```php
use MartinCamen\Sonarr\Sonarr;

$sonarr = Sonarr::create(
    host: 'localhost',
    port: 8989,
    apiKey: 'your-api-key',
    useHttps: false,
);

// Get all downloads (queue items)
$downloads = $sonarr->downloads()->all();

// Get all series
$series = $sonarr->series()->all();

// Get a specific series
$series = $sonarr->series()->find(1);

// Get system status
$status = $sonarr->system()->status();
```

### Laravel Integration

For Laravel integration, use the [laravel-sonarr](https://github.com/martincamen/laravel-sonarr) package which provides facades, service provider, and configuration management.

## Usage

### Downloads (Queue)

Get active downloads using the `downloads()` action:

```php
use MartinCamen\Sonarr\Sonarr;

$sonarr = Sonarr::create('localhost', 8989, 'your-api-key');

// Get all active downloads (paginated)
$downloadPage = $sonarr->downloads()->all();

foreach ($downloadPage as $item) {
    echo $item->title;
    echo $item->status->value;
    echo $item->sizeLeft;
}

// Get a specific download by ID
$download = $sonarr->downloads()->find(1);

// Get download status summary
$status = $sonarr->downloads()->status();
echo "Total: {$status->totalCount}";
echo "Unknown: {$status->unknownCount}";

// Delete a download
$sonarr->downloads()->delete(1);

// Bulk delete downloads
$sonarr->downloads()->bulkDelete([1, 2, 3]);
```

### Series

```php
use MartinCamen\Sonarr\Data\Responses\Series;
use MartinCamen\Sonarr\Data\Responses\SeriesCollection;
use MartinCamen\Sonarr\Sonarr;

// Get all series
/** @var SeriesCollection $series */
$series = $sonarr->series()->all();

foreach ($series as $show) {
    echo $show->title;
    echo $show->year;
    echo $show->status->value;
    echo $show->monitored ? 'Monitored' : 'Not monitored';
}

// Get a specific series by ID
/** @var Series $show */
$show = $sonarr->series()->find(1);

echo $show->title;
echo $show->overview;

// Search for series
$results = $sonarr->series()->search('Breaking Bad');

// Search by TVDB ID
$series = $sonarr->series()->searchByTvdb(81189);

// Add a new series
$series = $sonarr->series()->add([
    'title'            => 'Breaking Bad',
    'tvdbId'           => 81189,
    'qualityProfileId' => 1,
    'rootFolderPath'   => '/tv/',
]);

// Update a series
$series = $sonarr->series()->update(1, ['monitored' => false]);

// Delete a series
$sonarr->series()->delete(1);
```

### System

```php
use MartinCamen\ArrCore\Actions\SystemActions;

/** @var SystemActions $system */
$system = $sonarr->system();

// Get system status
$status = $system->status();
echo $status->version;
echo $status->osName;

// Get system health
$health = $system->health();
foreach ($health->warnings() as $warning) {
    echo $warning->type . ': ' . $warning->message;
}

// Get disk space
$diskSpace = $system->diskSpace();
foreach ($diskSpace as $disk) {
    echo $disk->path . ': ' . $disk->freeSpace;
}

// Get system tasks
$tasks = $system->tasks();
$task = $system->task(1);

// Get backups
$backups = $system->backups();
```

### Episodes

Access episode information and management:

```php
use MartinCamen\Sonarr\Data\Responses\Episode;
use MartinCamen\Sonarr\Data\Responses\EpisodeCollection;
use MartinCamen\Sonarr\Sonarr;

// Get all episodes
/** @var EpisodeCollection $episodes */
$episodes = $sonarr->episode()->all();

// Get all episodes for a series
$episodes = $sonarr->episode()->forSeries(1);

// Get episodes for a specific season
$episodes = $sonarr->episode()->forSeries(1, seasonNumber: 2);

// Get a specific episode by ID
/** @var Episode $episode */
$episode = $sonarr->episode()->find(1);

// Update an episode
$episode = $sonarr->episode()->update(1, ['monitored' => false]);
```

### Episode Files

Access episode file information:

```php
use MartinCamen\Sonarr\Data\Responses\EpisodeFile;
use MartinCamen\Sonarr\Data\Responses\EpisodeFileCollection;

// Get all episode files for a series
/** @var EpisodeFileCollection $files */
$files = $sonarr->episodeFile()->all(seriesId: 1);

// Get a specific episode file by ID
/** @var EpisodeFile $file */
$file = $sonarr->episodeFile()->find(1);

// Delete an episode file
$sonarr->episodeFile()->delete(1);

// Bulk delete episode files
$sonarr->episodeFile()->bulkDelete([1, 2, 3]);
```

### Calendar

Access upcoming episode releases:

```php
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Data\Options\CalendarOptions;
use MartinCamen\Sonarr\Data\Responses\EpisodeCollection;

// Get upcoming episodes (defaults to today to today + 2 days)

/** @var CalendarActions $calendar */
$calendar = $sonarr->calendar();

/** @var EpisodeCollection $episodes */
$episodes = $calendar->all();

// Get episodes within a specific date range
$options = CalendarOptions::make()->withDateRange(
    new DateTime('2024-01-01'),
    new DateTime('2024-01-31'),
);
$episodes = $calendar->all($options);

// Include unmonitored series
$options = CalendarOptions::make()
    ->withUnmonitored(unmonitored: true)
    ->withTags([1, 2]);
$episodes = $calendar->get($options);
```

### History

Access download history:

```php
use MartinCamen\ArrCore\Data\Options\PaginationOptions;
use MartinCamen\ArrCore\Data\Options\SortOptions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Data\Enums\HistoryEventType;
use MartinCamen\Sonarr\Data\Options\HistoryOptions;
use MartinCamen\Sonarr\Data\Responses\HistoryPage;

/** @var HistoryActions $history */
$history = $sonarr->history();

/** @var HistoryPage $historyPage */
// Get paginated history with defaults
$historyPage = $history->all();

// Get history with custom pagination and sorting
$pagination = new PaginationOptions(page: 1, pageSize: 50);
$sort = SortOptions::by('date')->descending();
$historyPage = $history->all($pagination, $sort);

// Filter by event type
$filters = HistoryOptions::make()
    ->withEventType(HistoryEventType::Grabbed)
    ->withIncludeSeries(true);
$historyPage = $history->all(null, null, $filters);
```

### Wanted (Missing & Cutoff)

Access missing episodes and quality cutoff:

```php
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Data\Options\WantedOptions;

/** @var WantedActions $wanted */
$wanted = $sonarr->wanted();

// Get paginated missing episodes
$missing = $wanted->missing();

// Filter to only monitored episodes
$filters = WantedOptions::make()->onlyMonitored();
$missing = $wanted->missing(null, null, $filters);

// Get ALL missing episodes (automatically handles pagination)
$allMissing = $wanted->allMissing();

// Get episodes below quality cutoff
$cutoff = $wanted->cutoff();
```

### Commands

Execute Sonarr commands:

```php
use MartinCamen\Sonarr\Actions\CommandActions;

/** @var CommandActions $commands */
$commands = $sonarr->command();

// Get all commands
$all = $commands->all();

// Trigger an RSS sync
$command = $commands->rssSync();

// Execute a series search
$command = $commands->searchSeries(id: 1);

// Execute a season specific series search
$command = $commands->searchSeason(seriesId: 1, seasonNumber: 4);
```

### Advanced: Raw API Access

For operations not yet exposed through the SDK, use the `api()` method to access the low-level API client:

```php
use MartinCamen\ArrCore\Data\Options\PaginationOptions;
use MartinCamen\ArrCore\Data\Options\SortOptions;
use MartinCamen\Sonarr\Data\Options\QueueOptions;
use MartinCamen\Sonarr\Sonarr;

// Add a new series
$seriesData = [
    'title'            => 'Breaking Bad',
    'qualityProfileId' => 1,
    'tvdbId'           => 81189,
    'rootFolderPath'   => '/tv/',
    'monitored'        => true,
    'addOptions'       => [
        'searchForMissingEpisodes' => true,
    ],
];

/** @var Sonarr $sonarr */
$sonarr->api()->series()->add($seriesData);

// Update a series
$sonarr->api()->series()->update(1, $seriesData);

// Delete a series
$sonarr->api()->series()->delete(1, deleteFiles: true);

// Search for series
$results = $sonarr->api()->series()->search('Breaking Bad');

// Get queue with full options
$pagination = new PaginationOptions(page: 1, pageSize: 100);
$sort = SortOptions::by('timeleft')->ascending();
$filters = QueueOptions::make()->withIncludeSeries(true);
$queue = $sonarr->api()->queue()->all($pagination, $sort, $filters);

// Delete from queue
$sonarr->api()->queue()->delete(
    id: 1,
    removeFromClient: true,
    blocklist: false,
);

// Get disk space
$diskSpace = $sonarr->api()->system()->diskSpace();
```

## Request Options

The SDK provides typed request option classes:

### Pagination Options

```php
use MartinCamen\ArrCore\Data\Options\PaginationOptions;

$options = PaginationOptions::make(pageSize: 12);
$options = new PaginationOptions(page: 2, pageSize: 50);
$options = PaginationOptions::make()->withPage(3)->withPageSize(100);
```

### Sort Options

```php
use MartinCamen\ArrCore\Data\Options\SortOptions;

$options = SortOptions::none();
$options = SortOptions::by('title')->ascending();
$options = SortOptions::by('airDateUtc')->descending();
```

## Error Handling

The SDK throws specific exceptions for different error types:

```php
use MartinCamen\ArrCore\Exceptions\AuthenticationException;
use MartinCamen\ArrCore\Exceptions\ConnectionException;
use MartinCamen\ArrCore\Exceptions\NotFoundException;
use MartinCamen\ArrCore\Exceptions\ValidationException;

try {
    $show = $sonarr->seriesById(999);
} catch (AuthenticationException $e) {
    // Invalid API key
} catch (NotFoundException $e) {
    // Series not found
} catch (ConnectionException $e) {
    // Could not connect to server
} catch (ValidationException $e) {
    // Validation error
    print_r($e->getErrors());
}
```

## Testing

The SDK provides testing utilities for easy mocking:

```php
use PHPUnit\Framework\Attributes\Test;
use MartinCamen\Sonarr\Testing\Factories\DownloadFactory;
use MartinCamen\Sonarr\Testing\Factories\SeriesFactory;
use MartinCamen\Sonarr\Testing\SonarrFake;

class MyTest extends TestCase
{
    #[Test]
    public function itDisplaysSeries(): void
    {
        $fake = new SonarrFake([
            'series' => SeriesFactory::makeMany(5),
        ]);

        $series = $fake->series()->all();

        $this->assertCount(5, $series);
        $fake->assertCalled('series');
        $fake->assertCalledTimes('series', 1);
    }

    #[Test]
    public function itDisplaysDownloads(): void
    {
        $fake = new SonarrFake([
            'downloads' => DownloadFactory::makeMany(3),
        ]);

        $downloads = $fake->downloads()->all();

        $this->assertCount(3, $downloads);
        $fake->assertCalled('downloads');
    }
}
```

### Using Factories

```php
use MartinCamen\Sonarr\Testing\Factories\DownloadFactory;
use MartinCamen\Sonarr\Testing\Factories\SeriesFactory;

// Create series data
$series = SeriesFactory::make(1);
$series = SeriesFactory::makeMany(5);
$series = SeriesFactory::make(1, [
    'title'  => 'Breaking Bad',
    'tvdbId' => 81189,
    'year'   => 2008,
]);

// Create download data
$downloads = DownloadFactory::makeMany(3);
$completed = DownloadFactory::makeCompleted(1);
$withError = DownloadFactory::makeWithError(2);
```

## Architecture

The SDK follows a layered architecture:

```
Sonarr (Public SDK)
  ↓
Action Classes (SeriesActions, DownloadActions, etc.)
  ↓
Endpoint Classes (SeriesEndpoint, QueueEndpoint, etc.)
  ↓
HTTP Client
```

- **`Sonarr`**: The public entry point returning action classes
- **Action Classes**: Type-safe methods for each domain (`series()->all()`, `downloads()->find(1)`)
- **Endpoint Classes**: Low-level API calls using Sonarr's native terminology
- **Response Types**: Typed DTOs from the SDK (`Series`, `DownloadPage`, etc.)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
