# PHP IncludeList
For web asset inclusion management: Handles ensuring avoidance of duplicate inclusions, and handles chained dependencies.

```php

$JsAssets = new IncludeList;
$JsAssets->available_add('js/jquery.js', 'jquery');
$JsAssets->available_add('js/jquery-ui.js', 'jquery-ui', ['jquery']);
$JsAssets->paths(); #> []

$JsAssets->add('index.js', false, ['jquery-ui']);
$JsAssets->paths(); #> ["js/jquery.js",  "js/jquery-ui.js", "index.js"]
```


