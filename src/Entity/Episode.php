<?php

namespace App\Entity;

use DateTime;
use Exception;

/**
 * Podcast episode
 */
class Episode
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * Link to the original page
     *
     * @var string
     */
    private $link = '';

    /**
     * @var DateTime
     */
    private $pubDate;

    /**
     * @var string
     */
    private $description = '';

    /**
     * Url of the audio file
     *
     * @var string
     */
    private $url = '';

    /**
     * Size in byte of audio file
     *
     * @var string
     */
    private $length = '';

    /**
     * @var int
     */
    private $duration = 0;

    /**
     * @var float
     */
    private $filesize = 0.0;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->item->get_title();
    }


    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLength(): string
    {
        return $this->length;
    }

    /**
     * @param string $length
     */
    public function setLength(string $length): void
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return DateTime
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * @param string|null $pubDate
     * @throws Exception
     */
    public function setPubDate(?string $pubDate): void
    {
        $this->pubDate = new DateTime(date("Y-m-d", strtotime($pubDate)));
    }

    /**
     * @return float
     */
    public function getFilesize(): float
    {
        return $this->filesize;
    }

    /**
     * @param int $filesize
     */
    public function setFilesize(int $filesize): void
    {
        $this->filesize = $filesize * 0.000001;
    }
}
