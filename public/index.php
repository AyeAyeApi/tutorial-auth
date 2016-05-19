<?php
/**
 * index.php
 * @author    Daniel Mason <daniel@ayeayeapi.com>
 * @copyright (c) 2016 Daniel Mason <daniel@ayeayeapi.com>
 * @license   MIT
 * @see       https://github.com/AyeAyeApi/tutorial-auth
 */

require_once '../vendor/autoload.php';

use AyeAye\Auth\Api\Version1;
use AyeAye\Api\Api;

$initialController = new Version1();
$api = new Api($initialController);
$api->go()->respond();
