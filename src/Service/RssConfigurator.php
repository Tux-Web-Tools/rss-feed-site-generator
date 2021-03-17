<?php

namespace App\Service;

use App\Entity\RssConfig;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RssConfigurator
 *
 * @package App\Service
 */
class RssConfigurator
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * RssConfigurator constructor.
     *
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @return RssConfig|null
     */
    public function getRssConfiguration(): ?RssConfig
    {
        $rssConfigFile = $this->params->get('kernel.project_dir') . '/config/rss.local.yaml';
        if (file_exists($rssConfigFile)) {

            return RssConfig::createFromRequest(Yaml::parseFile($rssConfigFile));
        } else {
            return null;
        }
    }
}
