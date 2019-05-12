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
}