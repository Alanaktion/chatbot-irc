#!/usr/bin/php
<?php
/**
 * Minimal IRC Chatbot
 * @author Alan Hardman <alan@phpizza.com>
 */

if (!defined('STDIN')) {
    die("The bot must be run from the command line.");
}

require_once __DIR__ . '/lib/client.php';
require __DIR__ . '/config.php';

$bot = new IrcClient($config['server'], $config['port']);
$bot->init($config);
$bot->run();
