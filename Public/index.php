<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 12.05.2019
 * Time: 21:48
 *
 * MVC pattern in use.
 * Created by Michael Sverdlov for Avangrid Homework.
 */

define('PATH_TO_MODELS',      $_SERVER['DOCUMENT_ROOT'] . '/App/Models/');
define('PATH_TO_VIEWS',       $_SERVER['DOCUMENT_ROOT'] . '/App/Views/');
define('PATH_TO_CONTROLLERS', $_SERVER['DOCUMENT_ROOT'] . '/App/Controllers/');

define('APPLICATION_CONTROLLER', PATH_TO_CONTROLLERS . 'ApplicationController.php');
define('DEFAULT_RUN_CONTROLLER', PATH_TO_CONTROLLERS . 'WayPointController.php');

define('FILE_TPL_EXT',   '.tpl.php');
define('FILE_MODEL_EXT', '.php');
define('FILE_CONFIG',    $_SERVER['DOCUMENT_ROOT'] . '/App/Config' . FILE_MODEL_EXT);

require_once APPLICATION_CONTROLLER;
require_once DEFAULT_RUN_CONTROLLER;

new WayPointController();