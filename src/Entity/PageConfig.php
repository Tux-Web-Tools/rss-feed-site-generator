<?php

namespace App\Entity;

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
    private $maxItem = 0;

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
    public function getMaxItem(): int
    {
        return $this->maxItem;
    }

    /**
     * @param int $maxItem
     */
    public function setMaxItem(int $maxItem): void
    {
        $this->maxItem = $maxItem;
    }

}
