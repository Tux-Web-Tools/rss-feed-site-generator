<?php

namespace App\Entity;


use SimplePie;

class Feed
{
    /**
     * @var SimplePie
     */
    private $feed;

    /**
     * Feed constructor.
     *
     * @param SimplePie $feed
     */
    public function __construct(SimplePie $feed)
    {
        $this->feed = $feed;
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->feed->get_title();
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return html_entity_decode($this->feed->get_description());
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->feed->get_image_url();
    }

    /**
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->feed->get_language();
    }
}
