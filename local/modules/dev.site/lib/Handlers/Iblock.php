<?php

namespace Dev\Site\Handlers;


class Iblock
{
    public static function OnAddIBlockElementAddAndUpdateHandler(&$arFields)
    {
        \Bitrix\Main\Loader::includeModule("iblock");

        $logIBlockId = self::getLogIblockId();

        $iBlockId = $arFields['IBLOCK_ID'];
        $iBlockCode = $arFields['IBLOCK_CODE'];
        $iBlockName = self::getIBlockName($iBlockId);
        $elementId = $arFields['ID'];
        $elementCode = $arFields['CODE'];
        $elementName = $arFields['NAME'];
        $elementIBlockSectionId = $arFields['IBLOCK_SECTION'][0];

        if ($iBlockId == $logIBlockId) {
            return;
        }
        
        $sectionId = self::getSectionId($logIBlockId, $iBlockName, $iBlockCode);     

        if (!isset($elementIBlockSectionId) || empty($elementIBlockSectionId)) {
            $previewText = $iBlockName . '->' . $elementName;
        } else {
            $sectionsString = self::getSections($elementIBlockSectionId);
            $previewText = $iBlockName . '->' . $sectionsString . '->' . $elementName;
        }


        $element = new \CIBlockElement;
        $arFieldsNewElement = [
            'ACTIVE' => 'Y',
            'ACTIVE_FROM' => ConvertTimeStamp(time(), 'FULL'),
            'IBLOCK_ID' => $logIBlockId,
            'NAME' => $elementId,
            'CODE' => $elementCode,
            'IBLOCK_SECTION_ID' => $sectionId,
            'PREVIEW_TEXT' => $previewText,
        ];

        $existingElement = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $logIBlockId,
                'NAME' => $elementId,
            ],
            false,
            false,
            ['ID']
        )->Fetch();

        if ($existingElement) {
            $element->Update($existingElement['ID'], $arFieldsNewElement);
        } else {
            $element->Add($arFieldsNewElement);
        }
    }

    static function getSectionId($logIBlockId, $iBlockName, $iBlockCode) {
        $section = \CIBlockSection::GetList(
            [],
            [
                'IBLOCK_ID' => $logIBlockId,
                'NAME' => $iBlockName,
            ],
            false,
            false,
            ['ID']
        )->Fetch();

        if ($section) {
            $sectionId = $section['ID'];
        } else {
            $section = new \CIBlockSection;
            $arFieldsNewSection = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $logIBlockId,
                'NAME' => $iBlockName,
                'CODE' => $iBlockCode,
            ];
            $sectionId = $section->Add($arFieldsNewSection);
            if (!$sectionId) {
                return;
            }
        }
        return $sectionId;
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

    static function getIBlockName($iBlockId) {
        $iblock = \CIBlock::GetByID($iBlockId)->GetNext();

        if(!$iblock) {
            return;
        }
        return $iblock['NAME'];
    }

    static function getSections($iBlockSectionId) {
        $section = \CIBlockSection::GetById($iBlockSectionId)->Fetch();

        if (!$section) {
            return '';
        }

        $iBlockSectionName = $section['NAME'];
        $iBlockSectionParentId = $section['IBLOCK_SECTION_ID'];

        if (!isset($iBlockSectionParentId) || empty($iBlockSectionParentId)) {
            return $iBlockSectionName;
        }

        return self::getSections($iBlockSectionParentId) . '->' . $iBlockSectionName;
    }
}
