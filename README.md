# SilverStripe Foxy

Foxy.io integration for SilverStripe websites.

## Requirements

* SilverStripe ^4.0

## Installation

```
composer require dynamic/silverstripe-foxy
```

## License

See [License](license.md)

## Example configuration

Add the following extensions and configuration options to `foxy.yml`:

```yaml

PageController:
  extensions:
    - Dynamic\Foxy\Extension\PurchasableExtension

Dynamic\Products\Page\Product:
  extensions:
    - Dynamic\Foxy\Extension\Purchasable

Dynamic\Foxy\Model\FoxyHelper:
  cart_url: ''      # from Foxy store settings
  secret: ''        # from Foxy store advanced settings
  custom_ssl: 0     # (optional) enable custom ssl setting from Foxy store advanced settings
  max_quantity: 10  # maximum number of the same product that can be added to the cart
  product_classes:
    - Dynamic\Products\Page\Product
  include_product_subclasses: 0   # (optional) include subclasses of product_classes in queries
  
```

## Templates

To include the AddToCartForm on your page/object, use `<% include AddToCartForm %>`

## Maintainers
 *  [Dynamic](http://www.dynamicagency.com) (<dev@dynamicagency.com>)
 
## Bugtracker
Bugs are tracked in the issues section of this repository. Before submitting an issue please read over 
existing issues to ensure yours is unique. 
 
If the issue does look like a new bug:
 
 - Create a new issue
 - Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots 
 and screencasts can help here.
 - Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version, 
 Operating System, any installed SilverStripe modules.
 
Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.
 
## Development and contribution
If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.
