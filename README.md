# PHP IncludeList
Handles ensuring avoidance of duplicate inclusions, and handles chained dependencies.

```php

$JsAssets = new IncludeList;
$JsAssets->available_add('jquery', 'js/jquery.js');
$JsAssets->available_add('jquery-ui', 'js/jquery-ui.js', ['jquery']);
$JsAssets->paths(); #> []

$JsAssets->add('index.js', false, ['jquery-ui']);
$JsAssets->paths(); #> ["jquery", "jquery-ui", "index.js"]
```


