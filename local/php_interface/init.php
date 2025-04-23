<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

if (Loader::includeModule('dev.site')) {
    EventManager::getInstance()->addEventHandlerCompatible(
        'iblock',
        'OnAfterIBlockElementAdd',
        ['Dev\\Site\\Handlers\\Iblock', 'OnAddIBlockElementAddAndUpdateHandler']
    );

    EventManager::getInstance()->addEventHandlerCompatible(
        'iblock',
        'OnAfterIBlockElementUpdate',
        ['Dev\\Site\\Handlers\\Iblock', 'OnAddIBlockElementAddAndUpdateHandler']
    );
}