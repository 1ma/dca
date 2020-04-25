<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$phar = new Phar($root . '/dca.phar', 0, $root . '/dca.phar');
$phar = $phar->convertToExecutable(Phar::PHAR, Phar::NONE);

$phar->startBuffering();

$phar->buildFromIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator('src')), $root);
$phar->buildFromIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator('vendor')), $root);

$phar->addFile($root . '/bin/console.php', 'bin/console.php');
$phar->addFile($root . '/LICENSE', 'LICENSE');
$phar->addFile($root . '/settings.ini', 'settings.ini');

$phar->setDefaultStub('bin/console.php');

$phar->stopBuffering();
