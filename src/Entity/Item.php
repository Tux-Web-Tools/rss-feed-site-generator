<?php

namespace App\Entity;

use DateTime;
use Exception;
use SimplePie_Item;

/**
 * Generic item
 */
class Item
{
    /**
     * @var SimplePie_Item
     */
    private $item;

    /**
     * @var RssConfig
     */
    private $rssConfig;

    /**
     * Item constructor.
     *
     * @param SimplePie_Item $item
     * @param RssConfig $rssConfig
     */
    public function __construct(SimplePie_Item $item, RssConfig $rssConfig)
    {
        $this->item = $item;
        $this->rssConfig = $rssConfig;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return html_entity_decode($this->item->get_title());
    }


    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->item->get_link();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if (
            $this->item->get_content() &&
            $this->rssConfig->isUseContent()
        ) {
            $description = $this->item->get_content();
        } else {
            $description = $this->item->get_description();
        }

        return $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->item->get_enclosure()->link;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getPubDate()
    {
        return new DateTime(date("Y-m-d", strtotime($this->item->get_date())));
    }
}
