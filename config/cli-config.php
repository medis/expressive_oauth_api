<?php declare(strict_types = 1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;

// $container = require __DIR__ . '/container.php';
// return new HelperSet([
//     'em' => new EntityManagerHelper(
//         $container->get(EntityManagerInterface::class)
//     ),
// ]);



$container = require __DIR__ . '/container.php';

$em = $container->get(\Doctrine\ORM\EntityManager::class);

$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

// print_r($container->get('config')['doctrine']);die;
return new \Symfony\Component\Console\Helper\HelperSet([
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
]);