<?php

namespace MartinCamen\Sonarr\Tests\Unit\Actions;

use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Data\Enums\EpisodeEndpoint;
use MartinCamen\Sonarr\Data\Options\EpisodeOptions;
use MartinCamen\Sonarr\Data\Responses\Episode;
use MartinCamen\Sonarr\Data\Responses\EpisodeCollection;
use MartinCamen\Sonarr\Testing\Factories\EpisodeFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EpisodeActionsTest extends TestCase
{
    #[Test]
    public function itCanGetAllEpisodes(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(EpisodeEndpoint::All, [])
            ->willReturn(EpisodeFactory::makeMany(5));

        $episodeActions = new EpisodeActions($client);
        $episodes = $episodeActions->all();

        $this->assertInstanceOf(EpisodeCollection::class, $episodes);
        $this->assertCount(5, $episodes);
    }

    #[Test]
    public function itCanGetEpisodesWithFilters(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(EpisodeEndpoint::All, [
                'seriesId'     => 123,
                'seasonNumber' => 2,
            ])
            ->willReturn(EpisodeFactory::makeMany(10, seriesId: 123, seasonNumber: 2));

        $options = new EpisodeOptions(seriesId: 123, seasonNumber: 2);
        $episodeActions = new EpisodeActions($client);
        $episodes = $episodeActions->all($options);

        $this->assertInstanceOf(EpisodeCollection::class, $episodes);
    }

    #[Test]
    public function itCanGetEpisodeById(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(EpisodeEndpoint::ById, ['id' => 456])
            ->willReturn(EpisodeFactory::make(456));

        $episodeActions = new EpisodeActions($client);
        $episode = $episodeActions->find(456);

        $this->assertInstanceOf(Episode::class, $episode);
        $this->assertEquals(456, $episode->id);
    }

    #[Test]
    public function itCanGetEpisodesForSeries(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(EpisodeEndpoint::All, [
                'seriesId' => 123,
            ])
            ->willReturn(EpisodeFactory::makeMany(20, seriesId: 123));

        $episodeActions = new EpisodeActions($client);
        $episodes = $episodeActions->forSeries(123);

        $this->assertInstanceOf(EpisodeCollection::class, $episodes);
    }

    #[Test]
    public function itCanGetEpisodesForSpecificSeason(): void
    {
        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('get')
            ->with(EpisodeEndpoint::All, [
                'seriesId'     => 123,
                'seasonNumber' => 2,
            ])
            ->willReturn(EpisodeFactory::makeMany(12, seriesId: 123, seasonNumber: 2));

        $episodeActions = new EpisodeActions($client);
        $episodes = $episodeActions->forSeries(123, seasonNumber: 2);

        $this->assertInstanceOf(EpisodeCollection::class, $episodes);
    }

    #[Test]
    public function itCanUpdateEpisode(): void
    {
        $episodeData = ['monitored' => false];

        $client = $this->createMock(RestClientInterface::class);
        $client->expects($this->once())
            ->method('put')
            ->with(EpisodeEndpoint::ById, array_merge(['id' => 456], $episodeData))
            ->willReturn(EpisodeFactory::make(456, overrides: $episodeData));

        $episodeActions = new EpisodeActions($client);
        $episode = $episodeActions->update(456, $episodeData);

        $this->assertInstanceOf(Episode::class, $episode);
    }
}
