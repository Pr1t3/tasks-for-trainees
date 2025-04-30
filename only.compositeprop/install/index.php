<?php

use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class only_compositeprop extends CModule
{
    var $MODULE_ID  = 'only.compositeprop';

    function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . '/version.php';

        $this->MODULE_ID = 'only.compositeprop';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('IEX_CPROP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('IEX_CPROP_MODULE_DESC');

        $this->PARTNER_NAME = Loc::getMessage('IEX_CPROP_PARTNER_NAME');
        $this->PARTNER_URI = '';

        $this->FILE_PREFIX = 'compositeprop';
        $this->MODULE_FOLDER = str_replace('.', '_', $this->MODULE_ID);
        $this->FOLDER = 'bitrix';

        $this->INSTALL_PATH_FROM = '/' . $this->FOLDER . '/modules/' . $this->MODULE_ID;
    }

    function isVersionD7()
    {
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        if($this->isVersionD7())
        {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage('IEX_CPROP_INSTALL_ERROR_VERSION'));
        }
    }

    function DoUninstall()
    {
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();
    }


    function InstallDB()
    {
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

    function installFiles()
    {
        return true;
    }

    function uninstallFiles()
    {
        return true;
    }

    function getEvents()
    {
        return [
            ['FROM_MODULE' => 'iblock', 'EVENT' => 'OnIBlockPropertyBuildList', 'TO_METHOD' => 'GetUserTypeDescription'],
            ['FROM_MODULE' => 'main', 'EVENT' => 'OnUserTypeBuildList', 'TO_METHOD' => 'GetUserTypeDescription'],
        ];
    }

    function InstallEvents()
    {
        $classHandlerIblock = 'CompositePropertyIblock';
        $classHandlerUserField = 'CompositePropertyUserField';
        $eventManager = EventManager::getInstance();

        $arEvents = $this->getEvents();
        foreach($arEvents as $arEvent){
            if($arEvent['FROM_MODULE'] == 'iblock') {
                $classHandler = $classHandlerIblock;
            }
            elseif($arEvent['FROM_MODULE'] == 'main') {
                $classHandler = $classHandlerUserField;
            }
            else {
                continue;
            }
            $eventManager->registerEventHandler(
                $arEvent['FROM_MODULE'],
                $arEvent['EVENT'],
                $this->MODULE_ID,
                $classHandler,
                $arEvent['TO_METHOD']
            );
        }

        return true;
    }

    function UnInstallEvents()
    {
        $classHandlerIblock = 'CompositePropertyIblock';
        $classHandlerUserField = 'CompositePropertyUserField';
        $eventManager = EventManager::getInstance();

        $arEvents = $this->getEvents();
        foreach($arEvents as $arEvent){
            if($arEvent['FROM_MODULE'] == 'iblock') {
                $classHandler = $classHandlerIblock;
            }
            elseif($arEvent['FROM_MODULE'] == 'main') {
                $classHandler = $classHandlerUserField;
            }
            else {
                continue;
            }
            $eventManager->unregisterEventHandler(
                $arEvent['FROM_MODULE'],
                $arEvent['EVENT'],
                $this->MODULE_ID,
                $classHandler,
                $arEvent['TO_METHOD']
            );
        }

        return true;
    }
}