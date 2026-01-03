<?php

namespace MartinCamen\Sonarr\Tests\Unit\Data;

use MartinCamen\Sonarr\Data\Responses\Series;
use MartinCamen\Sonarr\Testing\Factories\SeriesFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SeriesTest extends TestCase
{
    #[Test]
    public function itCanBeCreatedFromArray(): void
    {
        $data = SeriesFactory::make(1);
        $series = Series::fromArray($data);

        $this->assertInstanceOf(Series::class, $series);
        $this->assertEquals(1, $series->id);
        $this->assertEquals('Test Series 1', $series->title);
    }

    #[Test]
    public function itCanBeConvertedToArray(): void
    {
        $data = SeriesFactory::make(1);
        $series = Series::fromArray($data);
        $array = $series->toArray();

        $this->assertEquals(1, $array['id']);
        $this->assertEquals('Test Series 1', $array['title']);
    }

    #[Test]
    public function itCanCheckIfEnded(): void
    {
        $endedSeries = Series::fromArray(SeriesFactory::makeEnded(1));
        $continuingSeries = Series::fromArray(SeriesFactory::make(1));

        $this->assertTrue($endedSeries->isEnded());
        $this->assertFalse($continuingSeries->isEnded());
    }

    #[Test]
    public function itCanCheckIfContinuing(): void
    {
        $continuingSeries = Series::fromArray(SeriesFactory::make(1));
        $endedSeries = Series::fromArray(SeriesFactory::makeEnded(1));

        $this->assertTrue($continuingSeries->isContinuing());
        $this->assertFalse($endedSeries->isContinuing());
    }

    #[Test]
    public function itCanCheckIfHasEpisodes(): void
    {
        $seriesWithEpisodes = Series::fromArray(SeriesFactory::makeWithEpisodes(1));
        $seriesWithoutEpisodes = Series::fromArray(SeriesFactory::make(1, [
            'statistics' => [
                'episodeCount'      => 10,
                'episodeFileCount'  => 0,
                'totalEpisodeCount' => 10,
                'sizeOnDisk'        => 0,
            ],
        ]));

        $this->assertTrue($seriesWithEpisodes->hasEpisodes());
        $this->assertFalse($seriesWithoutEpisodes->hasEpisodes());
    }

    #[Test]
    public function itCanCheckIfMonitored(): void
    {
        $monitoredSeries = Series::fromArray(SeriesFactory::make(1));
        $unmonitoredSeries = Series::fromArray(SeriesFactory::makeUnmonitored(1));

        $this->assertTrue($monitoredSeries->isMonitored());
        $this->assertFalse($unmonitoredSeries->isMonitored());
    }

    #[Test]
    public function itCanCalculateSizeOnDiskInGb(): void
    {
        $series = Series::fromArray(SeriesFactory::make(1, [
            'statistics' => [
                'episodeCount'      => 10,
                'episodeFileCount'  => 10,
                'totalEpisodeCount' => 10,
                'sizeOnDisk'        => 10737418240, // 10 GB in bytes
            ],
        ]));

        $this->assertEquals(10.0, $series->getSizeOnDiskGb());
    }

    #[Test]
    public function itCanCheckSeriesType(): void
    {
        $standardSeries = Series::fromArray(SeriesFactory::make(1));
        $animeSeries = Series::fromArray(SeriesFactory::make(1, ['seriesType' => 'anime']));
        $dailySeries = Series::fromArray(SeriesFactory::make(1, ['seriesType' => 'daily']));

        $this->assertFalse($standardSeries->isAnime());
        $this->assertTrue($animeSeries->isAnime());
        $this->assertTrue($dailySeries->isDaily());
    }
}
