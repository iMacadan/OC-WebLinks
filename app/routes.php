<?php

// Home page
$app->get('/', "WebLinks\Controller\HomeController::indexAction")->bind('home');

// Submit a link form
$app->match('/link/submit', "WebLinks\Controller\HomeController::submitLinkAction")->bind('link_submit');

// Login form
$app->get('/login', "WebLinks\Controller\HomeController::loginAction")->bind('login');

// Admin home page
$app->get('/admin', "WebLinks\Controller\AdminController::indexAction")->bind('admin');

// Edit an existing article
$app->match('/admin/link/{id}/edit', "WebLinks\Controller\AdminController::editLinkAction")->bind('admin_link_edit');

// Remove an article
$app->get('/admin/link/{id}/delete', "WebLinks\Controller\AdminController::deleteLinkAction")->bind('admin_link_delete');

// Add a user
$app->match('/admin/user/add', "WebLinks\Controller\AdminController::addUserAction")->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/edit', "WebLinks\Controller\AdminController::editUserAction")->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/delete', "WebLinks\Controller\AdminController::deleteUserAction")->bind('admin_user_delete');

// API : get all links
$app->get('/api/links', "WebLinks\Controller\ApiController::getLinksAction")->bind('api_links');

// API : get a link
$app->get('/api/link/{id}', "WebLinks\Controller\ApiController::getLinkAction")->bind('api_link');
