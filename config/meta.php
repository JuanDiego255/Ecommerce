<?php

return [
    'app_id' => env('META_APP_ID'),
    'app_secret' => env('META_APP_SECRET'),
    'redirect_uri' => env('META_REDIRECT_URI'),
    'redirect_uri_magnolia' => env('META_REDIRECT_URI_MAGNOLIA'),
    'redirect_uri_sc' => env('META_REDIRECT_URI_SC'),
    'graph_version' => env('META_GRAPH_VERSION', 'v19.0'),
];
