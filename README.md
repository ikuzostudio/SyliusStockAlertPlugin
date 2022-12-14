<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Stock Alert</h1>

<p align="center">Add Stock Alert & subscription into Sylius.</p>
<p align="center">/!\ Currently in alpha /!\</p>

## Quickstart


```
$ composer require ikuzostudio/stock-alert-plugin
```

Add plugin dependencies to your `config/bundles.php` file:

```php
return [
  // ...
  Ikuzo\SyliusStockAlertPlugin\IkuzoSyliusStockAlertPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
  ...
  - { resource: "@IkuzoSyliusStockAlertPlugin/Resources/config/app/config.yaml"}
```

Add routes in `config/routes.yaml`

```yaml
# config/routes.yaml

ikuzo_stock_alert_routes:
    resource: "@IkuzoSyliusStockAlertPlugin/Resources/config/routing.yaml"
```

Add the StockAlertChannelInterface to the Channel model and implement it with the StockAlertChannelChannelTrait
```php

use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelInterface;
use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelTrait;

class Channel extends BaseChannel implements StockAlertChannelInterface
{
    use StockAlertChannelTrait;
}
```
