<?php

define("TEST_ASSETS_DIR", __DIR__ . "/assets");
define("COOKBOOK_DIR", dirname(__DIR__) . "/vendor/ietf-jose/cookbook");
require dirname(__DIR__) . "/vendor/autoload.php";

if (empty(ini_get("date.timezone"))) {
	ini_set("date.timezone", "UTC");
}
