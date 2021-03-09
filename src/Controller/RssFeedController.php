<?php

namespace App\Controller;

use App\Entity\Episode;
use Exception;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RssFeedController
 *
 * @package App\Controller
 */
class RssFeedController extends AbstractController
{
    const BITRATE_KBPS = 192;

    const PAGINATION_ITEMS = 10;

    /**
     * @var array
     */
    public $episodes = [];

    /**
     * Renders Feed site
     *
     * @return Response
     */
    public function generateEpisodeSite($feedUrl)
    {
        if (!($feed = $this->getFeed($feedUrl))) {
            return $this->render('rss_feed/error.html.twig');
        }

        foreach ($feed->channel->item as $item) {
            $episode = new Episode();
            $episode->setTitle($item->title);
            $episode->setLink($item->link);
            $episode->setPubDate($item->pubDate);
            $episode->setDescription($item->description);
            $episode->setUrl($item->enclosure['url']);
            $episode->setLength($item->enclosure["length"]);
            $episode->setDuration($this->calculateMp3Durarion(
                self::BITRATE_KBPS,
                $item->enclosure["length"])
            );
            $this->episodes[] = $episode;
        }

        return $this->render('rss_feed/episodes.html.twig', [
            'podcast' => [
                'title' => $feed->channel->title,
                'description' => $feed->channel->description,
                'image' => $feed->channel->image->url,
                'language' => $feed->channel->language
            ],
            'episodes' => $this->episodes,
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
