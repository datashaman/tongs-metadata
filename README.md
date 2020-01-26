# tongs metadata plugin

File metadata plugin for [Tongs](https://github.com/datashaman/tongs) static site generator.

## setup

If you add the following config to your _tongs.json_ file:

    {
      "plugins": {
        "metadata": {
          "authors": "data/authors.json"
        }
      }
    }

... then this plugin will load the contents of _src/data/authors.json_ into the global metadata as `authors`.

The data files are removed from the build pipeline. The plugin recognizes and parses file with extensions `json`, `yaml` and `yml`.

## source

This plugin is heavily based on [metalsmith-metadata](https://github.com/segmentio/metalsmith-metadata).
