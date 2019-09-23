# Resmush service for offbeatWP

The Resmush service is a service package for [offbeatWP](https://github.com/offbeatwp).

## Installation
Install the package using [Composer](https://getcomposer.org/) (**First you need to go to the OffbeatWP theme folder**)

```
composer require offbeatwp/re-smush
```

Then you need to add the social importer as service. You can do this by adding the service in the `config/service.php` file.
```
OffbeatWP\ReSmush\Service::class,
```

## This one is in beta, you cannot use it yet
