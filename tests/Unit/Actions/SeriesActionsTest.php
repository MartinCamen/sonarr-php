<?php

namespace MartinCamen\Sonarr\Tests\Unit\Actions;

use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Actions\SeriesActions;
use MartinCamen\Sonarr\Data\Enums\SeriesEndpoint;
use MartinCamen\Sonarr\Data\Responses\Series;
use MartinCamen\Sonarr\Data\Responses\SeriesCollection;
use MartinCamen\Sonarr\Testing\Factories\SeriesFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SeriesActionsTest extends TestCase
{
    #[Test]
    public function itCanGetAllSeries(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(SeriesEndpoint::All, [])
            ->willReturn(SeriesFactory::makeMany(3));

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->all();

        $this->assertInstanceOf(SeriesCollection::class, $series);
        $this->assertCount(3, $series);
    }

    #[Test]
    public function itCanFilterSeriesByTvdbId(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(SeriesEndpoint::All, ['tvdbId' => 12345])
            ->willReturn([SeriesFactory::make(1, ['tvdbId' => 12345])]);

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->all(tvdbId: 12345);

        $this->assertInstanceOf(SeriesCollection::class, $series);
    }

    #[Test]
    public function itCanGetSeriesById(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(SeriesEndpoint::ById, ['id' => 123])
            ->willReturn(SeriesFactory::make(123));

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->find(123);

        $this->assertInstanceOf(Series::class, $series);
        $this->assertEquals(123, $series->id);
    }

    #[Test]
    public function itCanLookupSeries(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(SeriesEndpoint::Lookup, ['term' => 'Breaking Bad'])
            ->willReturn(SeriesFactory::makeMany(2));

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->search('Breaking Bad');

        $this->assertInstanceOf(SeriesCollection::class, $series);
    }

    #[Test]
    public function itCanLookupSeriesByTvdbId(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(SeriesEndpoint::LookupTvdb, ['tvdbId' => 81189])
            ->willReturn(SeriesFactory::make(1, ['tvdbId' => 81189]));

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->searchByTvdb(81189);

        $this->assertInstanceOf(Series::class, $series);
    }

    #[Test]
    public function itCanAddSeries(): void
    {
        $seriesData = SeriesFactory::make(1);

        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('post')
            ->with(SeriesEndpoint::All, $seriesData)
            ->willReturn($seriesData);

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->add($seriesData);

        $this->assertInstanceOf(Series::class, $series);
    }

    #[Test]
    public function itCanUpdateSeries(): void
    {
        $seriesData = ['monitored' => false];

        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('put')
            ->with(SeriesEndpoint::ById, array_merge(['id' => 123], $seriesData))
            ->willReturn(SeriesFactory::make(123, $seriesData));

        $seriesActions = new SeriesActions($client);
        $series = $seriesActions->update(123, $seriesData);

        $this->assertInstanceOf(Series::class, $series);
    }

    #[Test]
    public function itCanDeleteSeries(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('delete')
            ->with(SeriesEndpoint::ById, [
                'id'                     => 123,
                'deleteFiles'            => true,
                'addImportListExclusion' => false,
            ])
            ->willReturn(null);

        $seriesActions = new SeriesActions($client);
        $seriesActions->delete(123, deleteFiles: true);

        $this->addToAssertionCount(1);
    }
}
