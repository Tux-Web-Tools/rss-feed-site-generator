<?php

namespace App\Entity;

use DateTime;
use Exception;
use SimplePie_Item;

/**
 * Podcast episode
 */
class Episode
{
    /**
     * Bitrate in kbit/s
     */
    const BITRATE_KBPS = 192;

    /**
     * @var SimplePie_Item
     */
    private $item;

    /**
     * @var RssConfig
     */
    private $rssConfig;

    /**
     * Episode constructor.
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
        return $this->item->get_description();
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->item->get_content();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->item->get_enclosure()->link;
    }

    /**
     * @return string
     */
    public function getLength(): string
    {
        return $this->item->get_enclosure()->length;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        $bitrateKbps = ($this->rssConfig->getBitrateKbps()) ?: self::BITRATE_KBPS;

        if ($this->item->get_enclosure()->duration) {
            $duration = $this->item->get_enclosure()->duration / 60;
        } else {
            $duration = $this->calculateMp3Duration(
                $bitrateKbps,
                $this->item->get_enclosure()->length
            );
        }
        return $duration;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getPubDate()
    {
        return new DateTime(date("Y-m-d", strtotime($this->item->get_date())));
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->item->get_item_tags(SIMPLEPIE_NAMESPACE_ITUNES, 'episode')[0]['data'];
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->item->get_item_tags(SIMPLEPIE_NAMESPACE_ITUNES, 'season')[0]['data'];
    }

    /**
     * @return float
     */
    public function getFilesize(): float
    {
        return $this->item->get_enclosure()->length * 0.000001;
    }

    /**
     * Calculates approximate duration of mp3 file
     *
     * @param $bitrate
     * @param $length
     * @return false|float
     */
    private function calculateMp3Duration($bitrate, $length)
    {
        $seconds = ((int)$length * 0.008)/$bitrate;

        return floor($seconds/60);
    }
}
