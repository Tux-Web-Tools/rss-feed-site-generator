<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\PageConfig;
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
     * Bitrate in kbit/s
     */
    const BITRATE_KBPS = 192;

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
     * Use RSS property content as description
     */
    const DESCRIPTION_USE_CONTENT = 1;

    /**
     * @var array
     */
    private $episodes = [];

    /**
     * @var array
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
        $rssFeedUrl = ($this->rssConfig['config']['rss_feed_url']) ?: $feedUrl;

        if (!($feed = $this->fetchFeed($rssFeedUrl))) {
            return $this->render('rss_feed/error.html.twig', [
                'user' => $this->rssConfig
            ]);
        }

        $pageConfig = $this->getPageConfig($request);

        // Check the maximum item count
        $maxItems = ($pageConfig->getMaxItem() < $feed->get_item_quantity()) ? $pageConfig->getMaxItem() : $feed->get_item_quantity();

        // Pagination
        $maxPages = ceil($feed->get_item_quantity() / $pageConfig->getItemLimit());

        $result = null;
        if ($this->rssConfig['config']['type'] == self::PODCAST) {

            $this->episodes = $this->getEpisodes($feed, $pageConfig, $maxItems);

            // Check if HTMX request
            if (!$request->server->has('HTTP_HX_REQUEST')) {
                $podcastTemplate = 'rss_feed/podcast.html.twig';
            } else {
                $podcastTemplate = 'rss_feed/_episodes.html.twig';
            }
            $result = $this->render(
                $podcastTemplate,
                $this->getPodcastTemplateVariables(
                    $feed,
                    $request,
                    $pageConfig,
                    $maxPages
                ));
        } else {
            $result = $this->render('rss_feed/error.html.twig', [
                'user' => $this->rssConfig
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
     * Get page and item configuration
     *
     * @param Request $request
     * @return PageConfig
     */
    private function getPageConfig(Request $request): PageConfig
    {
        $pageConfig = new PageConfig();

        $pageConfig->setItemLimit(($this->rssConfig['config']['item_limit']) ?: self::ITEM_LIMIT);
        $getPage = ($request->get('page')) ? (int)$request->get('page') : 1;
        $pageConfig->setPage(($getPage > 0) ? $getPage : 1);
        $pageConfig->setStartItem($pageConfig->getPage() * $pageConfig->getItemLimit() - $pageConfig->getItemLimit());
        $pageConfig->setMaxItem($pageConfig->getStartItem() + $pageConfig->getItemLimit());

        return $pageConfig;
    }

    /**
     * Returns podcast episodes
     *
     * @param SimplePie $feed
     * @param PageConfig $pageConfig
     * @param $maxItems
     * @return array
     * @throws Exception
     */
    private function getEpisodes(SimplePie $feed, PageConfig $pageConfig, $maxItems): array
    {
        $bitrateKbps = ($this->rssConfig['config']['bitrate_kbps']) ?: self::BITRATE_KBPS;

        for ($i = $pageConfig->getStartItem(); $i < $maxItems; $i++) {

            $item = $feed->get_item($i);

            $episode = new Episode();
            $episode->setTitle($item->get_title());
            $episode->setLink($item->get_link());
            $episode->setPubDate($item->get_date());
            if (
                $item->get_content() &&
                $this->rssConfig['config']['description']['use_content'] == self::DESCRIPTION_USE_CONTENT
            ) {
                $episode->setDescription($item->get_content());
            } else {
                $episode->setDescription($item->get_description());
            }
            $episode->setUrl($item->get_enclosure()->link);
            $episode->setLength($item->get_enclosure()->length);
            if ($item->get_enclosure()->duration > 0) {
                $episode->setDuration($item->get_enclosure()->duration / 60);
            } else {
                $episode->setDuration($this->calculateMp3Duration(
                    $bitrateKbps,
                    $item->get_enclosure()->length)
                );
            }

            $episode->setFilesize($episode->getLength());
            $this->episodes[] = $episode;
        }

        return $this->episodes;
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

    /**
     * Returns an array of podcast template variables
     *
     * @param SimplePie $feed
     * @param Request $request
     * @param PageConfig $pageConfig
     * @param $maxPages
     * @return array
     */
    private function getPodcastTemplateVariables(
        SimplePie $feed,
        Request $request,
        PageConfig $pageConfig,
        $maxPages
    )
    {
        return [
            'feed' => $this->getFeedTemplateVariables($feed, $request),
            'episodes' => $this->episodes,
            'pagination' => [
                'page' => $pageConfig->getPage(),
                'maxPages' => $maxPages
            ],
            'user' => $this->rssConfig,
        ];
    }

    /**
     * Returns an array of feed template variables
     *
     * @param SimplePie $feed
     * @param Request $request
     * @return array
     */
    private function getFeedTemplateVariables(SimplePie $feed, Request $request): array
    {
        return [
            'title' => $feed->get_title(),
            'description' => $feed->get_description(),
            'image' => $feed->get_image_url(),
            'language' => $feed->get_language(),
            'url' => $request->getSchemeAndHttpHost() .
                $request->getPathInfo()
        ];
    }
}
