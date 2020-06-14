<?php
declare(strict_types=1);

namespace LeanpubApi\BookSummary;

use LeanpubApi\Common\Mapping;

final class BookSummary
{
    use Mapping;

    private string $title;

    private string $subtitle;

    private string $authorString;

    private string $titlePageUrl;

    private string $url;

    private string $pdfPublishedUrl;

    private string $epubPublishedUrl;

    private string $mobiPublishedUrl;
    private string $pdfPreviewUrl;
    private string $epubPreviewUrl;
    private string $mobiPreviewUrl;

    public function __construct(
        string $title,
        string $subtitle,
        string $authorString,
        string $titlePageUrl,
        string $url,
        string $pdfPublishedUrl,
        string $epubPublishedUrl,
        string $mobiPublishedUrl,
        string $pdfPreviewUrl,
        string $epubPreviewUrl,
        string $mobiPreviewUrl
    ) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->authorString = $authorString;
        $this->titlePageUrl = $titlePageUrl;
        $this->url = $url;
        $this->pdfPublishedUrl = $pdfPublishedUrl;
        $this->epubPublishedUrl = $epubPublishedUrl;
        $this->mobiPublishedUrl = $mobiPublishedUrl;
        $this->pdfPreviewUrl = $pdfPreviewUrl;
        $this->epubPreviewUrl = $epubPreviewUrl;
        $this->mobiPreviewUrl = $mobiPreviewUrl;
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromJsonDecodedData(array $data): self
    {
        return new self(
            self::asString($data, 'title'),
            self::asString($data, 'subtitle'),
            self::asString($data, 'author_string'),
            self::asString($data, 'title_page_url'),
            self::asString($data, 'url'),
            self::asString($data, 'pdf_published_url'),
            self::asString($data, 'epub_published_url'),
            self::asString($data, 'mobi_published_url'),
            self::asString($data, 'pdf_preview_url'),
            self::asString($data, 'epub_preview_url'),
            self::asString($data, 'mobi_preview_url')
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    public function subtitle(): string
    {
        return $this->subtitle;
    }

    public function author(): string
    {
        return $this->authorString;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function titlePageUrl(): string
    {
        return $this->titlePageUrl;
    }

    public function isAnyDownloadAvailable(): bool
    {
        if ($this->isPdfDownloadAvailable()) {
            return true;
        }

        if ($this->isEpubDownloadAvailable()) {
            return true;
        }

        if ($this->isMobiDownloadAvailable()) {
            return true;
        }

        return false;
    }

    public function pdfPublishedUrl(): string
    {
        return $this->pdfPublishedUrl;
    }

    public function isPdfDownloadAvailable(): bool
    {
        return $this->pdfPublishedUrl() !== '';
    }

    public function epubPublishedUrl(): string
    {
        return $this->epubPublishedUrl;
    }

    public function isEpubDownloadAvailable(): bool
    {
        return $this->epubPublishedUrl() !== '';
    }

    public function mobiPublishedUrl(): string
    {
        return $this->mobiPublishedUrl;
    }

    public function isMobiDownloadAvailable(): bool
    {
        return $this->mobiPublishedUrl() !== '';
    }

    public function pdfPreviewUrl(): string
    {
        return $this->pdfPreviewUrl;
    }

    public function epubPreviewUrl(): string
    {
        return $this->epubPreviewUrl;
    }

    public function mobiPreviewUrl(): string
    {
        return $this->mobiPreviewUrl;
    }
}
