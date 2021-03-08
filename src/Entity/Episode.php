<?php

namespace App\Entity;


/**
 * Podcast episode
 */
class Episode
{

    /**
     *
     */
    private $title;

    /**
     *
     */
    private $link;

    /**
     *
     */
    private $pubDate;

    /**
     *
     */
    private $description;

    /**
     *
     */
    private $url;

    /**
     * Size in bytes
     *
     */
    private $length;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPubDate(): ?string
    {
        return $this->pubDate;
    }

    public function setPubDate(?string $pubDate): self
    {
        $this->pubDate = date("d.m.Y", strtotime($pubDate));

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(?string $length): self
    {
        $this->length = $length;

        return $this;
    }
}
