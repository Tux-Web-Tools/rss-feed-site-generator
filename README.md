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

### Available configuration

The only required parameters are _rss_feed_url_ containing the url of the RSS feed and _type_ to determine the site to be generated: currently only a podcast site (type _1_) is available.

The parameter _description.use_content_ is used to define which property is used to fetch the items (episode/post) description.

To display a custom logo different from the one the RSS feed provides, an image file can be placed in _/public/image_, the file name including file extension needs to be added to _logo_. The header can be customized further by overwriting title, description, background and font color in rss.local.yaml. 

To display "legal information" in the footer, _copyright_, _imprint_url_ and _imprint_link_name_ need to be provided. 

If the content is also displayed on another website, a _canonical_link_ can be added. 

It is possible to add a custom favicon via _favicon_.

```yaml
config:
  # Feed type: 1 == Podcast, 2 == Blog (TBA)
  type: 1
  # RSS Data field of description: 1 == content, 0 == description
  # The field description is usually shorter and does not contain HTML tags
  description:
    use_content: 1
  # Url of podcast/blog rss feed
  rss_feed_url: ''
  # Possible values: https://twig.symfony.com/doc/2.x/filters/date.html
  date_format: 'Y-m-d'
  # Used to calculate the duration of a audio file
  bitrate_kbps: 192
  # How many items are displayed per page
  item_limit: 10
content:
  # file name of custom header image, place in /public/image
  logo: ''
  # File name of custom favicon image, place in /public/image
  favicon: ''
  header:
    # Overwrites the feed title of the header
    title: ''
    # Overwrites the feed description of the header
    description: ''
    # Overwrites the background color of the header (Hex code e.g. #333)
    background_color: ''
  footer:
    # Displayed in footer
    copyright: ''
    # Rendered in footer, should include http:// or https://
    imprint_url: ''
    # Displayed in footer
    imprint_link_name: ''
  # Url to original website
  canonical_link: ''
  messages:
    # Displayed if feed could not be fetched
    feed_error: 'No valid rss feed was supplied.'
    # Displayed if no results were found
    no_results: 'No results found.'
```

### Routing

The routing is defined in /config/routes.yaml.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Acknowledgments

* uses Skeleton responsive boilerplate https://github.com/dhg/Skeleton
* twig pagination based on https://gist.github.com/SimonSimCity/4594748
* uses htmx https://htmx.org/
* uses Green Audio Player https://github.com/greghub/green-audio-player
* uses SimplePie https://github.com/simplepie/simplepie
