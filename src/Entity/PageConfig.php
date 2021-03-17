<?php

namespace App\Entity;

use SimplePie;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PageConfig
 *
 * Configuration entity
 *
 * @package App\Entity
 */
class PageConfig
{
    /**
     * @var int
     */
    private $itemLimit = 0;

    /**
     * @var int
     */
    private $page = 0;

    /**
     * @var int
     */
    private $startItem = 0;

    /**
     * @var int
     */
    private $maxItems = 0;

    /**
     * @var int
     */
    private $maxPages = 0;

    /**
     * @param Request $request
     * @param int $limit
     * @param SimplePie $feed
     * @return PageConfig
     */
    public static function createFromRequest(Request $request, int $limit, SimplePie $feed): PageConfig
    {
        $pageConfig = new self();

        $pageConfig->setItemLimit($limit);
        $getPage = ($request->get('page')) ? (int)$request->get('page') : 1;
        $pageConfig->setPage(($getPage > 0) ? $getPage : 1);
        $pageConfig->setStartItem($pageConfig->getPage() * $pageConfig->getItemLimit() - $pageConfig->getItemLimit());
        $calcMaxItem = $pageConfig->getStartItem() + $pageConfig->getItemLimit();
        $pageConfig->setMaxItems($calcMaxItem, $feed);
        $pageConfig->setMaxPages($pageConfig->getItemLimit(), $feed);

        return $pageConfig;
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
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getStartItem(): int
    {
        return $this->startItem;
    }

    /**
     * @param int $startItem
     */
    public function setStartItem(int $startItem): void
    {
        $this->startItem = $startItem;
    }

    /**
     * @return int
     */
    public function getMaxItems(): int
    {
        return $this->maxItems;
    }

    /**
     * @param int $maxItem
     * @param SimplePie $feed
     */
    public function setMaxItems(int $maxItem, SimplePie $feed): void
    {
        $this->maxItems = min($maxItem, $feed->get_item_quantity());
    }

    /**
     * @return int
     */
    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    /**
     * @param int $itemLimit
     * @param SimplePie $feed
     */
    public function setMaxPages(int $itemLimit, SimplePie $feed): void
    {
        $this->maxPages = ceil($feed->get_item_quantity() / $itemLimit);
    }

}
