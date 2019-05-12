<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.05.2019
 * Time: 20:13
 */
/**
 * Class ApplicationController
 */
class ApplicationController
{
    protected static $args = [];
    protected static $title = 'Demo';
    protected static $action;
    protected static $baseAction = 'showmain';
    protected static $baseModel = PATH_TO_MODELS . 'Core' . FILE_MODEL_EXT;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        self::initArgs();
        self::initAction();
    }

    /**
     *
     */
    private static function initArgs(): void
    {
        if (!empty( $_SERVER['QUERY_STRING'])) {
            $key = 0;
            $val = 1;
            $argsMix = explode('&', $_SERVER['QUERY_STRING']);
            foreach ($argsMix as $value) {
                $argMix = explode('=', $value);
                self::$args[$argMix[$key]] = $argMix[$val];
            }
        }
    }

    /**
     *
     */
    private static function initAction(): void
    {
        if (array_key_exists('action', self::$args)) {
            self::$action = self::$args['action'];
        } else {
            self::$action = self::$baseAction;
        }
    }
}
