<?php

namespace MartinCamen\Sonarr\Tests\Unit\Data;

use MartinCamen\Sonarr\Data\Responses\Download;
use MartinCamen\Sonarr\Testing\Factories\DownloadFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DownloadTest extends TestCase
{
    #[Test]
    public function itCanBeCreatedFromArray(): void
    {
        $data = DownloadFactory::make(1);
        $download = Download::fromArray($data);

        $this->assertInstanceOf(Download::class, $download);
        $this->assertEquals(1, $download->id);
    }

    #[Test]
    public function itCanBeConvertedToArray(): void
    {
        $data = DownloadFactory::make(1);
        $download = Download::fromArray($data);
        $array = $download->toArray();

        $this->assertEquals(1, $array['id']);
    }

    #[Test]
    public function itCanCalculateProgress(): void
    {
        $download = Download::fromArray(DownloadFactory::make(1, [
            'size'     => 1000,
            'sizeleft' => 250,
        ]));

        $this->assertEquals(75.0, $download->getProgress());
    }

    #[Test]
    public function itHandlesZeroSizeForProgress(): void
    {
        $download = Download::fromArray(DownloadFactory::make(1, [
            'size'     => 0,
            'sizeleft' => 0,
        ]));

        $this->assertEquals(0.0, $download->getProgress());
    }

    #[Test]
    public function itCanCalculateSizeInGb(): void
    {
        $download = Download::fromArray(DownloadFactory::make(1, [
            'size' => 4500000000,
        ]));

        $this->assertEquals(4.19, $download->getSizeGb());
    }

    #[Test]
    public function itCanCheckIfCompleted(): void
    {
        $completed = Download::fromArray(DownloadFactory::makeCompleted(1));
        $downloading = Download::fromArray(DownloadFactory::make(2));

        $this->assertTrue($completed->isCompleted());
        $this->assertFalse($downloading->isCompleted());
    }

    #[Test]
    public function itCanCheckIfHasError(): void
    {
        $withError = Download::fromArray(DownloadFactory::makeWithError(1));
        $normal = Download::fromArray(DownloadFactory::make(2));

        $this->assertTrue($withError->hasError());
        $this->assertFalse($normal->hasError());
    }
}
