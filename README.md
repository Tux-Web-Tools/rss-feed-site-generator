# RSS feed site generator

Symfony 5 application to generate a website for a given RSS feed, works best with podcast feeds.

## Installation

Clone the repo or download the source code. And install via composer in the root of the project directory:

Development
```shell script
composer install
```

Production
```shell script
composer install --no-dev --optimize-autoloader
composer dump-env prod
```
Clear cache if needed
```shell script
php bin/console cache:clear
```

## Configuration

The global configuration file needs to be copied and renamed:

```shell script
cd config
cp rss.yaml rss.local.yaml
```

### Available configuration items

See the [rss.yaml](/config/rss.yaml) file to view all available configurations options.

#### Required configuration

The only required parameters are _rss_feed_url_ containing the url of the RSS feed and _type_ to determine the type of site to be generated: Podcast _1_ is the default type. To render a more generic RSS feed, _type_ should be set to _0_.

#### Further configuration

The parameter _description.use_content_ is used to define which property is used to fetch the item's (episode/item) description.

To display a custom logo different from the one the RSS feed provides, an image file can be placed in _/public/image_, the file name including file extension needs to be added to _logo_. The header can be customized further by overwriting title, description, background and font color in rss.local.yaml. It is also possible to customize the favicon via _favicon_.

To display "legal information" in the footer, _copyright_, _imprint_url_ and _imprint_link_name_ need to be provided. 

If the content originates from another website, a _canonical_link_ can be added.

#### Child theme

The usage of a custom child theme can be enabled by setting _child_theme_ to _1_. Copy the original theme and rename it to _rss_feed_child_.

```shell script
cd templates
cp -r rss_feed rss_feed_child
```

The file extensions and inclusions need to be adjusted accordingly e.g.:

```html
{% extends 'rss_feed_child/feed_site.html.twig' %}
{{ include('rss_feed_child/_metatags.html.twig' }}
```

The main templates required by the application:

* Podcast feed:
    * podcast.html.twig
    * _episodes.html.twig
* Generic item feed:
    * generic.html.twig
    * _items.html.twig
* error.html.twig for both types

These templates must be present, their contents however can be adjusted and extended to customize the generated site's appearance.

To add custom CSS and JS just place those files inside the corresponding directory in public/css or public/js and copy and paste the _stylesheets_ and _javascripts_ twig blocks from base.html.twig in your child theme and add the custom files.

## Routing

The routing is defined in /config/routes.yaml. The default path for the application is 
{domain}/public/.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

* uses Skeleton responsive boilerplate https://github.com/dhg/Skeleton
* twig pagination based on https://gist.github.com/SimonSimCity/4594748
* uses htmx https://htmx.org/
* uses Green Audio Player https://github.com/greghub/green-audio-player
* uses SimplePie https://github.com/simplepie/simplepie
