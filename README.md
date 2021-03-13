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

The only required parameter is _rss_feed_url_ containing the url of the RSS feed. To display "legal information" in the footer, _copyright_, _imprint_url_ and _imprint_link_name_ need to be provided.

```yaml
functional:
  # Url of podcast/blog rss feed
  rss_feed_url: ''
  # Possible values: https://twig.symfony.com/doc/2.x/filters/date.html
  date_format: 'Y-m-d'
  # Used to calculate the duration of a audio file
  bitrate_kbps: 192
  # How many items are displayed per page
  item_limit: 10
legal:
  # Displayed in footer
  copyright: ''
  # Rendered in footer, should include http:// or https://
  imprint_url: ''
  # Displayed in footer
  imprint_link_name: ''
# file name of custom header image, place in /public/image
logo: ''
metadata:
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
