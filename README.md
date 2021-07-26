# suzuken/ion-php

WIP

## Requirements

* PHP 7.4 or later

## Examples

```php
<?php

use Ion\Ion;

$n = new Ion();
$data = '{ name: "Ion" }'
$value = $n->load($data);

var_dump($value);

$ionText = $n->dump($value);
var_dump($ionText);
```

## Development

* This implementation is based on https://github.com/amzn/ion-go
* Tests based on https://github.com/amzn/ion-tests