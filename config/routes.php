<?php

\Cake\Routing\Router::scope('/lookup_lists', ['plugin' => 'LookupLists'], function (\Cake\Routing\RouteBuilder $routes) {
    $routes->connect('/', ['action' => 'index', 'controller' => 'LookupLists']);
    $routes->connect('/:action/*', ['controller' => 'LookupLists']);
    $routes->connect('/items/:action/*', ['controller' => 'LookupListItems']);
});
