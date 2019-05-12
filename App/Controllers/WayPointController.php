<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.05.2019
 * Time: 20:14
 */

/**
 * Class Controller
 */
class WayPointController extends ApplicationController
{
    private static $asyncRoutingAction = 'async';
    private static $routingAction = [
        'showmain'              => PATH_TO_VIEWS  . 'main'     . FILE_TPL_EXT,
        'showmap'               => PATH_TO_VIEWS  . 'map'      . FILE_TPL_EXT,
        'addwaypoint'           => PATH_TO_MODELS . 'WayPoint' . FILE_MODEL_EXT,
        'getwaypointbyaddress'  => PATH_TO_MODELS . 'WayPoint' . FILE_MODEL_EXT,
        'getallwaypoints'       => PATH_TO_MODELS . 'WayPoint' . FILE_MODEL_EXT,
        'deletewaypointdbbyid'  => PATH_TO_MODELS . 'WayPoint' . FILE_MODEL_EXT,
        'deleteallwaypointdb'   => PATH_TO_MODELS . 'WayPoint' . FILE_MODEL_EXT,
        'getpointstatistic'     => PATH_TO_MODELS . 'WayPoint' . FILE_MODEL_EXT,
    ];

    private static $status = [
        0 => 'failure',
        1 => 'ok'
    ];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (array_key_exists(self::$asyncRoutingAction, self::$args)) {
            self::routAsyncQuery();
        }

        self::routeStaticQuery();
    }

    /**
     *
     */
    public static function routAsyncQuery(): void
    {
        require_once FILE_CONFIG;
        switch (self::$action) {
            case 'addwaypoint':
                require_once self::$baseModel;
                require_once self::$routingAction['addwaypoint'];
                $point = new WayPoint();
                $result = $point::addWayPoint(self::$args);
                $status = self::$status[(int)$result];
                $point::calcWayPointStatistic();
                echo json_encode($status);
                exit;

            case  'getwaypointbyaddress':
                require_once self::$baseModel;
                require_once self::$routingAction['getwaypointbyaddress'];
                $point = new WayPoint();
                $result = $point::getWayPointByAddress(self::$args);
                echo json_encode($result);
                exit;

            case  'getallwaypoints':
                require_once self::$baseModel;
                require_once self::$routingAction['getallwaypoints'];
                $point = new WayPoint();
                $result = $point::getAllWayPoints();
                echo json_encode($result);
                exit;

            case  'deletewaypointdbbyid':
                require_once self::$baseModel;
                require_once self::$routingAction['deletewaypointdbbyid'];
                $point = new WayPoint();
                $result = $point::deleteWayPointDBById(self::$args);
                $point::calcWayPointStatistic();
                echo json_encode($result);
                exit;

            case  'deleteallwaypointdb':
                require_once self::$baseModel;
                require_once self::$routingAction['deleteallwaypointdb'];
                $point = new WayPoint();
                $result = $point::deleteAllWayPointDB();
                $point::calcWayPointStatistic();
                echo json_encode($result);
                exit;

            case  'getpointstatistic':
                require_once self::$baseModel;
                require_once self::$routingAction['getpointstatistic'];
                $point = new WayPoint();
                $result = $point::getWayPointStatistic();
                echo json_encode($result);
                exit;
        }
    }

    /**
     *
     */
    public static function routeStaticQuery(): void
    {
        switch (self::$action) {
            case 'showmain':
                $title = &self::$title;
                $subTpl = &self::$routingAction['showmap'];
                require_once self::$routingAction['showmain'];
        }
    }
}