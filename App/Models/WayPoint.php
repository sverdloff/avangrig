<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 12.05.2019
 * Time: 20:12
 */

/**
 * Class WayPoint
 */
class WayPoint extends Core
{
    protected static $tables = [
        'points'         => ' `waypoint`.`points` ',
        'pointstatistic' => ' `waypoint`.`pointstatistic` '
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $longitudeFrom
     * @param $latitudeFrom
     * @param $longitudeTo
     * @param $latitudeTo
     * @param int $earthRadius
     * @return float|int
     */
    public static function getDistance(
        $longitudeFrom, $latitudeFrom, $longitudeTo, $latitudeTo, $earthRadius = 6371)
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }

    /**
     * @param $args
     * @return bool
     */
    public static function addWayPoint($args): bool
    {
        $sql = 'INSERT INTO ' . self::$tables['points'] .
            'SET    `Lat` = "'    . (float)$args['lat'] . '", ' .
            '`Lng` = "'    . (float)$args['lng'] . '", ' .
            '`Address` = ' . self::getDB()->quote(urldecode ($args['adr'])). ', '.
            '`PlaceId` = ' . self::getDB()->quote(urldecode ($args['placeid'])). ' '.
            'ON DUPLICATE KEY '.
            'UPDATE `IsDeleted` = 0';

        $queryBuilder = self::getDB()->query($sql);

        return (bool)$queryBuilder;
    }

    /**
     * @param $args
     * @return array
     */
    public static function getWayPointByAddress($args): array
    {
        $sql = 'SELECT * FROM ' . self::$tables['points'] . ' USE INDEX(`del`) '.
            'WHERE ' . '`Address` = '. self::getDB()->quote(urldecode ($args['adr'])) . ' '.
            'AND `IsDeleted` = 1 ' .
            ' LIMIT 1';

        $queryBuilder = self::getDB()->query($sql);
        $res = $queryBuilder->fetchAll(PDO::FETCH_ASSOC);

        return (array)$res;
    }

    /**
     * @param $args
     * @return array
     */
    public static function getAllWayPoints(): array
    {
        $sql = 'SELECT * FROM ' . self::$tables['points'] . ' USE INDEX(`del`) '.
            'WHERE `IsDeleted` = 0 '.
            'ORDER BY `Lng` ';

        $queryBuilder = self::getDB()->query($sql);
        $res = $queryBuilder->fetchAll(PDO::FETCH_ASSOC);

        return (array)$res;
    }

    /**
     * @param $args
     * @return bool
     */
    public static function deleteWayPointDBById($args): bool
    {
        $sql = 'UPDATE ' . self::$tables['points'] .
            'SET `IsDeleted` = 1 '.
            'WHERE ' . '`Id` = '. (int)($args['id']) . ' '.
            'LIMIT 1';

        $queryBuilder = self::getDB()->query($sql);

        return (bool)$queryBuilder;
    }

    /**
     * @return bool
     */
    public static function deleteAllWayPointDB(): bool
    {
        $sql = 'UPDATE ' . self::$tables['points'] .
            'SET `IsDeleted` = 1 ';

        $queryBuilder = self::getDB()->query($sql);

        return (bool)$queryBuilder;
    }

    /**
     * @return array
     */
    public static function getWayPointStatistic(): array
    {
        $sql = 'SELECT * FROM ' . self::$tables['pointstatistic'];

        $queryBuilder = self::getDB()->query($sql);
        $res = $queryBuilder->fetchAll(PDO::FETCH_ASSOC);

        return (array)$res;
    }

    /**
     *
     */
    public static function calcWayPointStatistic(): void
    {
        $sql = 'UPDATE '. self::$tables['pointstatistic']. ' '.
            'SET `Counter` = (' .
            'SELECT COUNT(`Id`) FROM ' . self::$tables['points']. ' '.
            'WHERE `IsDeleted` = 0 )';

        self::getDB()->query($sql);
    }

    public static function getOptimalWay ()
    {
        $points = self::getAllWayPoints();
        $optimalWay = [];
        $obj = new ArrayObject($points);
        $copyPoints = $obj->getArrayCopy();

        $optimalWay [$copyPoints[0]['Address']] = 0;
        while (count($copyPoints) > 1) {
            $minDistance = INF;
            $nearestPointId = null;
            for ($j = 1; $j < count($copyPoints); $j++) {
                $distanceCurrentToNext = self::getDistance(
                    $copyPoints[0]['Lng'], $copyPoints[0]['Lat'],
                    $copyPoints[$j]['Lng'], $copyPoints[$j]['Lat']);
                if ((float)$distanceCurrentToNext <= (float)$minDistance) {
                    $minDistance = $distanceCurrentToNext;
                    $nearestPointId = $j;
                }
            }
            $optimalWay[$copyPoints[$nearestPointId]['Address']] = $minDistance;
            array_shift($copyPoints);
            array_unshift($copyPoints, $copyPoints[$nearestPointId - 1]);
            array_splice($copyPoints, $nearestPointId, 1);
        }

        $way = 0;
        foreach ($optimalWay as $key => $value){
            $way += $value;
        }

        return $way;
    }
}