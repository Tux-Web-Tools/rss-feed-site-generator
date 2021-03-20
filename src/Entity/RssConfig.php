<?php

namespace App\Entity;

/**
 * Class RssConfig
 *
 * Holds rss.local.yaml config and content parameters
 *
 * @package App\Entity
 */
class RssConfig
{
    /**
     * RSS feed type
     * Podcast == 1, generic == 0
     *
     * @var bool
     */
    private $type = true;

    /**
     * RSS Data field of item description
     * 1 == content, 0 == description
     *
     * @var bool
     */
    private $useContent = false;

    /**
     * @var string
     */
    private $rssFeedUrl = '';

    /**
     * Format for Twig date filter
     * Possible values: https://twig.symfony.com/doc/2.x/filters/date.html
     *
     * @var string
     */
    private $dateFormat = '';

    /**
     * Used to calculate the duration of a audio file
     *
     * @var int
     */
    private $bitrateKbps = 0;

    /**
     * How many items are displayed per page
     *
     * @var int
     */
    private $itemLimit = 0;

    /**
     * File name of custom header image, place in /public/image
     *
     * @var string
     */
    private $logo = '';

    /**
     * Displayed in footer as (c)
     *
     * @var string
     */
    private $copyright = '';

    /**
     * Rendered in footer, must include http:// or https://
     *
     * @var string
     */
    private $imprintUrl = '';

    /**
     * Displayed in footer
     *
     * @var string
     */
    private $imprintLinkName = '';

    /**
     * Url to original website
     *
     * @var string
     */
    private $canonicalLink = '';

    /**
     * Displayed if feed could not be fetched
     *
     * @var string
     */
    private $feedError = '';

    /**
     * Displayed if no items were found
     *
     * @var string
     */
    private $noResults = '';

    /**
     * @var string
     */
    private $favicon = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $backgroundColor = '';

    /**
     * @var string
     */
    private $fontColor = '';

    /**
     * @var bool
     */
    private $child_theme = false;

    /**
     * @param array $rssConfigArray
     * @return RssConfig
     */
    public static function createFromRequest(array $rssConfigArray): RssConfig
    {
        $rssConfig = new self();

        $config = $rssConfigArray['config'];
        $content = $rssConfigArray['content'];

        $rssConfig->setType($config['type']);
        $rssConfig->setChildTheme($config['child_theme']);
        $rssConfig->setUseContent($config['description']['use_content']);
        $rssConfig->setRssFeedUrl($config['rss_feed_url']);
        $rssConfig->setDateFormat($config['date_format']);
        $rssConfig->setBitrateKbps($config['bitrate_kbps']);
        $rssConfig->setItemLimit($config['item_limit']);
        $rssConfig->setLogo($content['logo']);
        $rssConfig->setFavicon($content['favicon']);
        $rssConfig->setTitle($content['header']['title']);
        $rssConfig->setDescription($content['header']['description']);
        $rssConfig->setBackgroundColor($content['header']['background_color']);
        $rssConfig->setFontColor($content['header']['font_color']);
        $rssConfig->setCopyright($content['footer']['copyright']);
        $rssConfig->setImprintUrl($content['footer']['imprint_url']);
        $rssConfig->setImprintLinkName($content['footer']['imprint_link_name']);
        $rssConfig->setCanonicalLink($content['canonical_link']);
        $rssConfig->setFeedError($content['messages']['feed_error']);
        $rssConfig->setNoResults($content['messages']['no_results']);
        return $rssConfig;
    }

    /**
     * @return bool
     */
    public function isPodcastType(): bool
    {
        return $this->type;
    }

    /**
     * @param bool $type
     */
    public function setType(bool $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isUseContent(): bool
    {
        return $this->useContent;
    }

    /**
     * @param bool $useContent
     */
    public function setUseContent(bool $useContent): void
    {
        $this->useContent = $useContent;
    }

    /**
     * @return string
     */
    public function getRssFeedUrl(): string
    {
        return $this->rssFeedUrl;
    }

    /**
     * @param string $rssFeedUrl
     */
    public function setRssFeedUrl(string $rssFeedUrl): void
    {
        $this->rssFeedUrl = $rssFeedUrl;
    }

    /**
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @return int
     */
    public function getBitrateKbps(): int
    {
        return $this->bitrateKbps;
    }

    /**
     * @param int $bitrateKbps
     */
    public function setBitrateKbps(int $bitrateKbps): void
    {
        $this->bitrateKbps = $bitrateKbps;
    }

    /**
     * @return int
     */
    public function getItemLimit(): int
    {
        return $this->itemLimit;
    }

    /**
     * @param int $itemLimit
     */
    public function setItemLimit(int $itemLimit): void
    {
        $this->itemLimit = $itemLimit;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getCopyright(): string
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     */
    public function setCopyright(string $copyright): void
    {
        $this->copyright = $copyright;
    }

    /**
     * @return string
     */
    public function getImprintUrl(): string
    {
        return $this->imprintUrl;
    }

    /**
     * @param string $imprintUrl
     */
    public function setImprintUrl(string $imprintUrl): void
    {
        $this->imprintUrl = $imprintUrl;
    }

    /**
     * @return string
     */
    public function getImprintLinkName(): string
    {
        return $this->imprintLinkName;
    }

    /**
     * @param string $imprintLinkName
     */
    public function setImprintLinkName(string $imprintLinkName): void
    {
        $this->imprintLinkName = $imprintLinkName;
    }

    /**
     * @return string
     */
    public function getCanonicalLink(): string
    {
        return $this->canonicalLink;
    }

    /**
     * @param string $canonicalLink
     */
    public function setCanonicalLink(string $canonicalLink): void
    {
        $this->canonicalLink = $canonicalLink;
    }

    /**
     * @return string
     */
    public function getFeedError(): string
    {
        return $this->feedError;
    }

    /**
     * @param string $feedError
     */
    public function setFeedError(string $feedError): void
    {
        $this->feedError = $feedError;
    }

    /**
     * @return string
     */
    public function getNoResults(): string
    {
        return $this->noResults;
    }

    /**
     * @param string $noResults
     */
    public function setNoResults(string $noResults): void
    {
        $this->noResults = $noResults;
    }

    /**
     * @return string
     */
    public function getFavicon(): string
    {
        return $this->favicon;
    }

    /**
     * @param string $favicon
     */
    public function setFavicon(string $favicon): void
    {
        $this->favicon = $favicon;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return string
     */
    public function getFontColor(): string
    {
        return $this->fontColor;
    }

    /**
     * @param string $fontColor
     */
    public function setFontColor(string $fontColor): void
    {
        $this->fontColor = $fontColor;
    }

    /**
     * @return bool
     */
    public function isChildTheme(): bool
    {
        return $this->child_theme;
    }

    /**
     * @param bool $child_theme
     */
    public function setChildTheme(bool $child_theme): void
    {
        $this->child_theme = $child_theme;
    }
}

