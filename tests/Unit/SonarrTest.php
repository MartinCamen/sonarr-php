<?php

namespace MartinCamen\Sonarr\Tests\Unit;

use MartinCamen\ArrCore\Actions\SystemActions;
use MartinCamen\ArrCore\Actions\WantedActions;
use MartinCamen\ArrCore\Client\RestClientInterface;
use MartinCamen\Sonarr\Actions\CalendarActions;
use MartinCamen\Sonarr\Actions\CommandActions;
use MartinCamen\Sonarr\Actions\DownloadActions;
use MartinCamen\Sonarr\Actions\EpisodeActions;
use MartinCamen\Sonarr\Actions\EpisodeFileActions;
use MartinCamen\Sonarr\Actions\HistoryActions;
use MartinCamen\Sonarr\Actions\SeriesActions;
use MartinCamen\Sonarr\Client\SonarrApiClientInterface;
use MartinCamen\Sonarr\Sonarr;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SonarrTest extends TestCase
{
    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsSeriesActionsFromSeriesMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $seriesActions = new SeriesActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('series')
            ->willReturn($seriesActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->series();

        $this->assertInstanceOf(SeriesActions::class, $result);
        $this->assertSame($seriesActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsDownloadActionsFromDownloadsMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $downloadActions = new DownloadActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('downloads')
            ->willReturn($downloadActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->downloads();

        $this->assertInstanceOf(DownloadActions::class, $result);
        $this->assertSame($downloadActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsSystemActionsFromSystemMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $systemActions = new SystemActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('system')
            ->willReturn($systemActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->system();

        $this->assertInstanceOf(SystemActions::class, $result);
        $this->assertSame($systemActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsEpisodeActionsFromEpisodeMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $episodeActions = new EpisodeActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('episode')
            ->willReturn($episodeActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->episode();

        $this->assertInstanceOf(EpisodeActions::class, $result);
        $this->assertSame($episodeActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsEpisodeFileActionsFromEpisodeFileMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $episodeFileActions = new EpisodeFileActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('episodeFile')
            ->willReturn($episodeFileActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->episodeFile();

        $this->assertInstanceOf(EpisodeFileActions::class, $result);
        $this->assertSame($episodeFileActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsCalendarActionsFromCalendarMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $calendarActions = new CalendarActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('calendar')
            ->willReturn($calendarActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->calendar();

        $this->assertInstanceOf(CalendarActions::class, $result);
        $this->assertSame($calendarActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsHistoryActionsFromHistoryMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $historyActions = new HistoryActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('history')
            ->willReturn($historyActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->history();

        $this->assertInstanceOf(HistoryActions::class, $result);
        $this->assertSame($historyActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsWantedActionsFromWantedMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $wantedActions = new WantedActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('wanted')
            ->willReturn($wantedActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->wanted();

        $this->assertInstanceOf(WantedActions::class, $result);
        $this->assertSame($wantedActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsCommandActionsFromCommandMethod(): void
    {
        $restClient = $this->createMock(RestClientInterface::class);
        $commandActions = new CommandActions($restClient);

        $apiClient = $this->createMock(SonarrApiClientInterface::class);
        $apiClient->expects($this->once())
            ->method('command')
            ->willReturn($commandActions);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->command();

        $this->assertInstanceOf(CommandActions::class, $result);
        $this->assertSame($commandActions, $result);
    }

    #[Test]
    #[AllowMockObjectsWithoutExpectations]
    public function itReturnsApiClientFromApiMethod(): void
    {
        $apiClient = $this->createMock(SonarrApiClientInterface::class);

        $sonarr = new Sonarr($apiClient);
        $result = $sonarr->api();

        $this->assertInstanceOf(SonarrApiClientInterface::class, $result);
        $this->assertSame($apiClient, $result);
    }

    #[Test]
    public function itCreatesInstanceWithStaticCreate(): void
    {
        $sonarr = Sonarr::create(
            host: 'localhost',
            port: 8989,
            apiKey: 'test-api-key',
        );

        $this->assertInstanceOf(Sonarr::class, $sonarr);
        $this->assertInstanceOf(SonarrApiClientInterface::class, $sonarr->api());
    }
}
