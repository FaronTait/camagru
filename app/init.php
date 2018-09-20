<?php

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

session_start();

require_once ROOT_DIR . '/app/core/App.php';
require_once ROOT_DIR . '/app/controllers/Controller.php';
require_once ROOT_DIR . '/app/config/database.php';

/*
** Create sql db and tables that this app depends on if they dont exist yet
*/

try
{
  if (Controller::getDB() === NULL)
  {
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    Controller::setDB($db);
  }
  else
    $db = Controller::getDB();
  require_once (ROOT_DIR . '/app/config/setup.php');
}
catch (PDOException $e)
{
  echo 'Camagru Internal Server Error: ';
  exit();
}

/*
** Set the timezone for johannesburg
*/

date_default_timezone_set('Africa/Johannesburg');

/*
** Admin email for errors
*/

define('ADMIN_EMAIL', 'ftait@student.wethinkcode.co.za');

define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', dirname(__DIR__))));
define('SITE_HOST', 'http://' . $_SERVER['HTTP_HOST']);

/*
** Asset path for url (superimposable images location)
** Asset names
*/

define('ASSET_PATH', '/app/views/assets/');

/*
** Directory where all images will be saved
*/

define('UPLOAD_DIR', '/app/views/uploads/');

/*
** Create upload, asset and img folders if it doesnt exist
*/

if (!file_exists(ROOT_DIR . '/app/views/uploads')) {
  /*
  ** Images that are uploaded are saved here
  */
  if (!mkdir(ROOT_DIR . '/app/views/uploads', 0700)) {
    die('<b>Failed to create uploads folder, please manually do so before running app</b>');
  }
}

if (!file_exists(ROOT_DIR . '/public/imgs')) {
  /*
  ** BG images saved here
  */
  if (!mkdir(ROOT_DIR . '/public/imgs', 0700)) {
    die('<b>Failed to create imgs folder, please manually do so before running app</b>');
  }
}

if (!file_exists(ROOT_DIR . '/app/views/assets')) {
  /*
  ** Background images saved here
  */
  if (!mkdir(ROOT_DIR . '/app/views/assets', 0700)) {
    die('<b>Failed to create imgs folder, please manually do so before running app</b>');
  }
}