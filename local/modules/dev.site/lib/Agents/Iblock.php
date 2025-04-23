<?php

namespace Dev\Site\Agents;


class Iblock
{
    public static function clearOldLogs()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        $logIBlockId = self::getLogIblockId();
        if (!$logIBlockId) {
            return '\\' . __CLASS__ . '::' . __FUNCTION__ . '();';
        }

        $iBlockElements = self::getLogElements();

        $count = 0;
        while ($arElem = $iBlockElements->Fetch()) {
            $arElements[$count] = $arElem;
            if ($count > 9) {
                \CIBlockElement::Delete($arElem['ID']);
            }
            $count++;
        }
        return '\\' . __CLASS__ . '::' . __FUNCTION__ . '();';
    }

    static function getLogElements() {
        $iBlockElements = \CIBlockElement::GetList(
            ['TIMESTAMP_X' => 'DESC'],
            [
                'IBLOCK_ID' => $logIBlockId,
            ],
            false,
            false,
            ['ID']
        );
        return $iBlockElements;
    }
    
    static function getLogIblockId() {
        $logIBlock = \CIBlock::GetList(
            [],
            [
                'CODE' => 'LOG',
            ],
            false,
            false,
            ['ID']
            )->Fetch();
        if ($logIBlock) {
            return $logIBlock['ID'];
        }
        return;
    }
}
