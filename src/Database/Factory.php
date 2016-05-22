<?php
/**
 * Factory.php
 * @author    Daniel Mason <daniel@ayeayeapi.com>
 * @copyright (c) 2016 Daniel Mason <daniel@ayeayeapi.com>
 * @license   MIT
 * @see       https://github.com/AyeAyeApi/tutorial-auth
 */

namespace AyeAye\Auth\Database;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

/**
 * Class Factory
 * @package AyeAye\Auth\Database
 */
class Factory
{

    /**
     * @var EntityManager
     */
    protected static $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public static function setEntityManager(EntityManager $entityManager)
    {
        static::$entityManager = $entityManager;
    }

    /**
     * @throws ORMException
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if (!static::$entityManager) {
            // Set up the Database Entities
            $paths = [ __DIR__ . "/Entity" ];
            $isDevMode = false;
            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

            // Create the Entity Manager
            $sqliteFile = __DIR__.'/../../db.sqlite';
            $dbParams = [
                'driver'   => 'pdo_sqlite',
                'user'     => 'root',
                'password' => '',
                'path'     => $sqliteFile,
            ];
            static::$entityManager = EntityManager::create($dbParams, $config);

            // If the database file doesn't exist, make it
            if(!file_exists($sqliteFile)) {
                touch($sqliteFile);
                $schemaTool = new SchemaTool(static::$entityManager);
                $classes = static::$entityManager->getMetadataFactory()->getAllMetadata();
                $schemaTool->createSchema($classes);
            }
        }
        return static::$entityManager;
    }
}
