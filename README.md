# RSS feed site generator

Symfony 5 application to generate a website for a given RSS feed, works best with podcast feeds.

## Installation

Clone the repo or download the source code. And install via composer in the root of the project directory:

```shell script
composer install
```

## Configuration

The global configuration file needs to be copied and renamed:

```shell script
cd config
cp rss.yaml rss.local.yaml
```

### Available configuration

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

TBA

## Acknowledgments

* twig pagination based on https://gist.github.com/SimonSimCity/4594748
