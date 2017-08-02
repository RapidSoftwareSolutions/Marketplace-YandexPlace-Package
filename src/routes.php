<?php
$routes = [
    'metadata',
    'searchByOrganization'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}
