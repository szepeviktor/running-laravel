# Fresh production installation

- Document everything (including service providers) in `hosting.yml`
- Default Apache virtualhost + PHP FPM pool + SSL certificate
- PHP extensions and directives (declared also in `php-env-check.php`)
- Apache config: `Include public/.htaccess`
- `.env` variables
- Database seeding and/or import
- Media import
- [CD](/webserver/Continuous-integration-Continuous-delivery.md) testing
- Per application CLI configuration `php -c /home/user/website/php-cli.ini`
- Set up queues
- Cron jobs (sitemap, queue checks)
- Outbound email: Laravel SwiftMailer or `mail()` and local queuing MTA
- Log reporting (`bin/laravel-report.sh`)
- Periodic file check (`bin/tripwire-fake.sh`)
- Monitor front page and ping API endpoint with Monit
- Register to webmaster tools
- Think of other environments (development/staging/beta/demo)

## Localisation

Adding all languages to your server's locale archive may not be desirable.

`_` is an alias for `gettext`.

### Installation

```bash
# ls /usr/share/i18n/locales/
for LOCALE in en_US hu_HU; do
    echo "${LOCALE} ..."
    localedef --replace -f UTF-8 -i "$LOCALE" "/home/USER/website/locales/${LOCALE}.utf-8"
done
```

### Testing

`utf-8` with small letters.

```bash
LOCPATH="/home/USER/website/locales" php -r 'var_dump(setlocale(LC_ALL, "hu_HU.utf-8"));'
```

### Configuration

Set `LOCPATH` (libc variable) in your PHP-FPM pool configuration.

```ini
env[LOCPATH] = "/home/USER/website/locales"
```
