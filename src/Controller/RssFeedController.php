<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Feed;
use App\Entity\PageConfig;
use App\Entity\Item;
use App\Entity\RssConfig;
use App\Service\RssConfigurator;
use Exception;
use Psr\Log\LoggerInterface;
use SimplePie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class RssFeedController
 *
 * @package App\Controller
 */
class RssFeedController extends AbstractController
{
    /**
     * Limit of feed items
     */
    const ITEM_LIMIT = 10;

    /**
     * Generate podcast site
     */
    const PODCAST = 1;

    /**
     * Generate blog site
     */
    const BLOG = 2;

    /**
     * @var array
     */
    private $episodes = [];

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var RssConfig
     */
    private $rssConfig;

    /**
     * @var RssConfigurator
     */
    private $rssConfigurator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * RssFeedController constructor.
     *
     * @param RssConfigurator $rssConfigurator
     * @param KernelInterface $kernel
     * @param LoggerInterface $logger
     */
    public function __construct(
        RssConfigurator $rssConfigurator,
        KernelInterface $kernel,
        LoggerInterface $logger
    )
    {
        $this->rssConfigurator = $rssConfigurator;
        $this->rssConfig = $this->rssConfigurator->getRssConfiguration();
        $this->projectDir = $kernel->getProjectDir();
        $this->logger = $logger;
    }

    /**
     * Renders Feed site
     *
     * @param Request $request
     * @param $feedUrl
     * @return Response
     * @throws Exception
     */
    public function generateFeedSite(Request $request, $feedUrl = '')
    {
        $rssFeedUrl = ($this->rssConfig->getRssFeedUrl()) ?: $feedUrl;

        // Get required theme
        $theme = ($this->rssConfig->isChildTheme()) ? 'rss_feed_child/' : 'rss_feed/';

        if (!($feed = $this->fetchFeed($rssFeedUrl))) {
            return $this->render($theme . 'error.html.twig', [
                'rssConfig' => $this->rssConfig
            ]);
        }

        // Get page and item configuration
        $limit = ($this->rssConfig->getItemLimit()) ?: self::ITEM_LIMIT;
        $pageConfig = PageConfig::createFromRequest($request, $limit, $feed);

        // Get items and template names
        if ($this->rssConfig->isPodcastType()) {
            $this->episodes = $this->getEpisodes($feed, $pageConfig);
            $feedTemplate = 'podcast.html.twig';
            $itemTemplate = '_episodes.html.twig';
        } else {
            $this->items = $this->getItems($feed, $pageConfig);
            $feedTemplate = 'generic.html.twig';
            $itemTemplate = '_items.html.twig';
        }

        // Check if HTMX request
        if (!$request->server->has('HTTP_HX_REQUEST')) {
            $template = $theme . $feedTemplate;
        } else {
            $template = $theme . $itemTemplate;
        }

        // Render appropriate template
        $result = $this->render(
            $template,
            $this->getTemplateVariables(
                new Feed($feed),
                $pageConfig
            )
        );

        if (!$result) {
            $result = $this->render($theme . 'error.html.twig', [
                'rssConfig' => $this->rssConfig
            ]);
        }
        return $result;
    }

    /**
     * Fetches RSS feed as SimplePie object
     *
     * @param $feedUrl
     * @return SimplePie|null
     */
    private function fetchFeed($feedUrl): ?SimplePie
    {
        $feed = new SimplePie();
        $feed->set_feed_url($feedUrl);
        $feed->enable_order_by_date(false);
        if (!is_dir($this->projectDir . '/var/cache/rss')) {
            mkdir($this->projectDir . '/var/cache/rss');
        }
        $feed->set_cache_location($this->projectDir . '/var/cache/rss');

        $feed->init();

        if ($feed->error) {
            $this->logger->error(
                $feed->error()
            );
            return null;
        } else {
            return $feed;
        }
    }

    /**
     * Returns podcast episodes
     *
     * @param SimplePie $feed
     * @param PageConfig $pageConfig
     * @return array
     * @throws Exception
     */
    private function getEpisodes(SimplePie $feed, PageConfig $pageConfig): array
    {
        for ($i = $pageConfig->getStartItem(); $i < $pageConfig->getMaxItems(); $i++) {

            $item = $feed->get_item($i);

            $episode = new Episode($item, $this->rssConfig);

            $this->episodes[] = $episode;
        }
        return $this->episodes;
    }

    /**
     * Returns generic items
     *
     * @param SimplePie $feed
     * @param PageConfig $pageConfig
     * @return array
     */
    private function getItems(SimplePie $feed, PageConfig $pageConfig): array
    {
        for ($i = $pageConfig->getStartItem(); $i < $pageConfig->getMaxItems(); $i++) {

            $feedItem = $feed->get_item($i);

            $item = new Item($feedItem, $this->rssConfig);

            $this->items[] = $item;
        }
        return $this->items;
    }

    /**
     * Returns an array of template variables
     *
     * @param Feed $feed
     * @param PageConfig $pageConfig
     * @return array
     */
    private function getTemplateVariables(
        Feed $feed,
        PageConfig $pageConfig
    ): array
    {
        if ($this->rssConfig->isPodcastType()) {
            $name = 'episodes';
            $items = $this->episodes;
        } else {
            $name = 'items';
            $items = $this->items;
        }
        return [
            'feed' => $feed,
            $name => $items,
            'pageConfig' => $pageConfig,
            'rssConfig' => $this->rssConfig,
        ];
    }
}
