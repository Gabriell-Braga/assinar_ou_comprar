<?php

namespace App;

class Settings {
    static $settings = [];

    static function register($settings=[]) {
        self::$settings = array_merge(self::$settings, $settings);
    }

    static function get($key) {
        return isset(self::$settings[$key])? self::$settings[$key]['value']: null;
    }

    static function all() {
        return self::$settings;
    }
}