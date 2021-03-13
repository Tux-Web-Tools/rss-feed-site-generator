<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Service\RssConfigurator;
use Exception;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * RssFeedController constructor.
     *
     * @param RssConfigurator $rssConfigurator
     */
    public function __construct(RssConfigurator $rssConfigurator)
    {
        $this->rssConfigurator = $rssConfigurator;
        $this->rssConfig = $this->rssConfigurator->getRssConfiguration();
    }

    /**
     * Renders Feed site
     *
     * @param Request $request
     * @param $feedUrl
     * @return Response
     * @throws Exception
     */
    public function generateEpisodeSite(Request $request, $feedUrl = '')
    {
        $rssFeedUrl = ($this->rssConfig['functional']['rss_feed_url']) ?: $feedUrl;

        if (!($feed = $this->getFeed($rssFeedUrl))) {
            return $this->render('rss_feed/error.html.twig', [
                'message' => $this->rssConfig['messages']['feed_error']
            ]);
        }

        $itemLimit = ($this->rssConfig['functional']['item_limit']) ?: self::ITEM_LIMIT;
        $page = ($request->get('page')) ?: 1;
        $startItem = $page * $itemLimit - $itemLimit;
        $maxItem = $startItem + $itemLimit;
        $bitrateKbps = ($this->rssConfig['functional']['bitrate_kbps']) ?: self::BITRATE_KBPS;

        // Fetch rss items
        $items = [];
        foreach ($feed->channel->item as $item) {
            $items[] = $item;
        }

        // Check the maximum item count
        $maxItems = ($maxItem < count($items)) ? $maxItem : count($items);

        // Pagination
        $maxPages = ceil(count($items) / $itemLimit);

        for ($i = $startItem; $i < $maxItems; $i++) {
            $episode = new Episode();
            $episode->setTitle($items[$i]->title);
            $episode->setLink($items[$i]->link);
            $episode->setPubDate($items[$i]->pubDate);
            $episode->setDescription($items[$i]->description);
            $episode->setUrl($items[$i]->enclosure['url']);
            $episode->setLength($items[$i]->enclosure['length']);
            $episode->setDuration($this->calculateMp3Durarion(
                $bitrateKbps,
                $items[$i]->enclosure['length'])
            );
            $episode->setFilesize($episode->getLength());
            $this->episodes[] = $episode;
        }
        //var_dump($request->getSchemeAndHttpHost().$request->getPathInfo());

        return $this->render('rss_feed/episodes.html.twig', [
            'feed' => [
                'title' => $feed->channel->title,
                'description' => $feed->channel->description,
                'image' => $feed->channel->image->url,
                'language' => $feed->channel->language,
                'url' => $request->getSchemeAndHttpHost() .
                    $request->getPathInfo()
            ],
            'episodes' => $this->episodes,
            'pagination' => [
                'page' => $page,
                'maxPages' => $maxPages
            ],
            'user' => $this->rssConfig,
            'message' => $this->rssConfig['messages']['no_results']
        ]);
    }

    /**
     * Gets the rss feed as SimpleXML
     *
     * @param $feedUrl
     * @return SimpleXMLElement|Exception
     */
    private function getFeed($feedUrl)
    {
        try {
            return simplexml_load_file($feedUrl);;
        }catch (Exception $exception) {
            // Todo: Log error
            return null;
        }
    }

    /**
     * Calculates approximate duration of mp3 file
     *
     * @param $bitrate
     * @param $length
     * @return false|float
     */
    private function calculateMp3Durarion($bitrate, $length)
    {
        $seconds = ((int)$length * 0.008)/$bitrate;

        return floor($seconds/60);
    }
}
