# DCA

[![.github/workflows/ci.yml](https://github.com/1ma/dca/actions/workflows/ci.yml/badge.svg)](https://github.com/1ma/dca/actions)

## Example

Buy $5.00 worth of BTC at current market price at Bitstamp:

```bash
# first you need to fill in the credentials of the exchanges you want to use
$ cp -n settings.ini.dist settings.ini
$ nano settings.ini

# running the main script from project sources
$ composer install --no-dev
$ php bin/dca bitstamp:buy 500

# same command, running the portable executable (see below)
$ ./dca.phar b:b 500
```

Cron automation:

```
# buy $7.50 worth of BTC at market price every 2 days
0 0 */2 * * /usr/local/bin/dca.phar bitstamp:buy 750
```

## Testing

```bash
$ composer test
```

## Building the portable PHAR executable

```bash
$ composer compile
```

For that to work, a `settings.ini` file must be present at the root of the project.

## Reference

```bash
$ ./dca.phar
dca 0.0.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help               Display help for a command
  list               List commands
 bitstamp
  bitstamp:buy       Buy BTC at market price at Bitstamp.
  bitstamp:withdraw  Withdraw BTC from Bitstamp to the given address.
 kraken
  kraken:buy         Buy BTC at market price at Kraken.
  kraken:withdraw    Withdraw BTC from Kraken to the given address.
```

## TODO to v1.0

- [ ] Proper testing
- [ ] Proper documentation
- [x] Slack integration
- [x] Set up Continuous Integration
- [ ] Support more exchanges (e.g. Kraken, Bittrex, Bitfinex)
