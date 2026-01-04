# Sonarr PHP SDK

A PHP SDK for the Sonarr REST API v3.

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
$downloads = $sonarr->downloads();

// Get all series
$series = $sonarr->series();

// Get system status
$status = $sonarr->system()->status();
// Get system summary
$systemSummary = $sonarr->systemSummary();
```

### Laravel Integration

For Laravel integration, use the [laravel-sonarr](https://github.com/martincamen/laravel-sonarr) package which provides facades, service provider, and configuration management.

## Usage

### Downloads (Queue)

Get active downloads using the unified `downloads()` method, which returns Core domain models compatible with other *arr services:

```php
use MartinCamen\Sonarr\Sonarr;

$sonarr = Sonarr::create('localhost', 8989, 'your-api-key');

// Get all active downloads
$downloads = $sonarr->downloads();

foreach ($downloads as $item) {
    echo $item->name;
    echo $item->progress->percentage() . '%';
    echo $item->status->value;
    echo $item->size->formatted();
}

// Filter downloads by status
$active = $downloads->active();
$completed = $downloads->completed();
$failed = $downloads->failed();

// Get total size and progress
echo $downloads->totalSize()->formatted();
echo $downloads->totalProgress()->percentage() . '%';
```

### Series

```php
// Get all series
$series = $sonarr->series();

foreach ($series as $show) {
    echo $show->title;
    echo $show->year;
    echo $show->status->value;
    echo $show->monitored ? 'Monitored' : 'Not monitored';
}

// Get a specific series by ID
$show = $sonarr->seriesById(1);
echo $show->title;
echo $show->overview;
```

### System Status

```php
echo $radarr->system()->status()->version;

foreach ($radarr->system()->health()->warnings() as $warning) {
    echo $warning->type . ': ' . $warning->message;
}
```

### System Summary

```php
$summary = $sonarr->systemSummary();

echo $summary->version;
echo $summary->isHealthy ? 'Healthy' : 'Issues detected';

foreach ($summary->healthIssues as $issue) {
    echo $issue->type . ': ' . $issue->message;
}
```

### Episodes

Access episode information and management:

```php
use MartinCamen\Sonarr\Data\Options\EpisodeOptions;

// Get all episodes for a series
$episodes = $sonarr->episode()->forSeries(1);

// Get episodes for a specific season
$episodes = $sonarr->episode()->forSeries(1, seasonNumber: 2);

// Get all episodes with custom filters
$options = EpisodeOptions::make()->withIncludeImages(includeImages: true);
$episodes = $sonarr->episode()->all($options);

// Get a specific episode by ID
$episode = $sonarr->episode()->get(1);
```

### Episode Files

Access episode file information:

```php
// Get all episode files for a series
$files = $sonarr->episodeFile()->all(seriesId: 1);

// Get a specific episode file by ID
$file = $sonarr->episodeFile()->get(1);

// Delete an episode file
$sonarr->episodeFile()->delete(1);
```

### Calendar

Access upcoming episode releases:

```php
use MartinCamen\Sonarr\Data\Options\CalendarOptions;

// Get upcoming episodes (defaults to today to today + 2 days)
$calendar = $sonarr->calendar()->get();

// Get episodes within a specific date range
$options = CalendarOptions::make()
    ->withDateRange(
        new DateTime('2024-01-01'),
        new DateTime('2024-01-31'),
    );
$episodes = $sonarr->calendar()->get($options);

// Include unmonitored series
$options = CalendarOptions::make()
    ->withUnmonitored(unmonitored: true)
    ->withTags([1, 2]);
$episodes = $sonarr->calendar()->get($options);
```

### History

Access download history:

```php
use MartinCamen\Sonarr\Data\Enums\HistoryEventType;
use MartinCamen\Sonarr\Data\Options\HistoryOptions;
use MartinCamen\ArrCore\Data\Options\PaginationOptions;
use MartinCamen\ArrCore\Data\Options\SortOptions;

// Get paginated history with defaults
$history = $sonarr->history()->all();

// Get history with custom pagination and sorting
$pagination = new PaginationOptions(page: 1, pageSize: 50);
$sort = SortOptions::by('date')->descending();
$history = $sonarr->history()->all($pagination, $sort);

// Filter by event type
$filters = HistoryOptions::make()
    ->withEventType(HistoryEventType::Grabbed)
    ->withIncludeSeries(true);
$history = $sonarr->history()->all(null, null, $filters);

// Get history for a specific series
$records = $sonarr->history()->forSeries(1);

// Get history for a specific episode
$records = $sonarr->history()->forEpisode(1);
```

### Wanted (Missing & Cutoff)

Access missing episodes and quality cutoff:

```php
use MartinCamen\ArrCore\Data\Options\WantedOptions;

// Get paginated missing episodes
$missing = $sonarr->wanted()->missing();

// Filter to only monitored episodes
$filters = WantedOptions::make()->onlyMonitored();
$missing = $sonarr->wanted()->missing(null, null, $filters);

// Get ALL missing episodes (automatically handles pagination)
$allMissing = $sonarr->wanted()->allMissing();

// Get episodes below quality cutoff
$cutoff = $sonarr->wanted()->cutoff();
```

### Commands

Execute Sonarr commands:

```php
// Get all commands
$commands = $sonarr->command()->all();

// Trigger an RSS sync
$command = $sonarr->command()->rssSync();

// Execute a series search
$command = $sonarr->command()->searchSeries(1);

// Search for missing episodes
$command = $sonarr->command()->searchMissing();
```

### Advanced: Raw API Access

For operations not yet exposed through the SDK, use the `api()` method to access the low-level API client:

```php
use MartinCamen\Sonarr\Data\Options\QueueOptions;

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
$sonarr->api()->series()->add($seriesData);

// Update a series
$sonarr->api()->series()->update(1, $seriesData);

// Delete a series
$sonarr->api()->series()->delete(1, deleteFiles: true);

// Search for series
$results = $sonarr->api()->series()->lookup('Breaking Bad');

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

        $series = $fake->series();

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

        $downloads = $fake->downloads();

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
SonarrApiClient (Internal API Client)
  ↓
HTTP Client
```

- **`Sonarr`**: The public interface with unified terminology (`downloads()`, `series()`) returning Core domain models
- **`SonarrApiClient`**: Internal client using Sonarr's native API terminology (`queue()`, `series()`)
- **Core Domain Models**: Shared types from `php-arr-core` for cross-service compatibility

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
