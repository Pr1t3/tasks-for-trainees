<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class NewsListComponentClass extends CBitrixComponent {
    public function onPrepareComponentParams($arParams) {
        if (empty($arParams['IBLOCK_TYPE']) && empty($arParams['IBLOCK_ID'])) {
            ShowError(GetMessage("IBLOCK_TYPE_OR_IBLOCK_ID_NOT_SET"));
            $this->includeComponentTemplate();
            die();
        }

        $filterIblock = [];
        if (isset($arParams['FILTER_IBLOCK']) && !empty($arParams['FILTER_IBLOCK'])) {
            foreach ($arParams['FILTER_IBLOCK'] as $key => $value) {
                $cleanKey = preg_replace('/^[<>=!]+/', '', $key);

                if (in_array($cleanKey, ['ACTIVE', 'NAME', 'SITE_ID', 'CODE'])) {
                    $filter[$key] = $value;
                } else {
                    ShowError(GetMessage("FILTER_IBLOCK_KEY_NOT_VALID"));
                }
            }
        }
        $filter = [];
        if (isset($arParams['FILTER']) && !empty($arParams['FILTER'])) {
            foreach ($arParams['FILTER'] as $key => $value) {
                $cleanKey = preg_replace('/^[<>=!]+/', '', $key);

                if (in_array($cleanKey, ['ID', 'ACTIVE', 'NAME', 'CODE', 'SECTION_ID', 'SECTION_CODE', 'SUBSECTION', ])) {
                    $filter[$key] = $value;
                } else {
                    ShowError(GetMessage("FILTER_KEY_NOT_VALID"));
                }
            }
        }
        $this->arParams['FILTER_IBLOCK'] = $filterIblock;
        $this->arParams['FILTER'] = $filter;
        $this->arParams['IBLOCK_TYPE'] = $arParams['IBLOCK_TYPE'];
        $this->arParams['IBLOCK_ID'] = $arParams['IBLOCK_ID'];

        return $arParams;
   }
    
    public function executeComponent() {
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return;
        } else {
            $this->arResult['ITEMS'] = $this->getItems();
        }
        $this->includeComponentTemplate();
    }

    private function getItems() {
        $iblocks = [];
        if (isset($this->arParams['IBLOCK_ID'])) {
            $iblockId = $this->arParams['IBLOCK_ID'];
            $iblocks[] = self::getIblockElements($iblockId);
        } else {
            $newFilter = $this->arParams['FILTER_IBLOCK'];
            $newFilter['TYPE'] = $this->arParams['IBLOCK_TYPE'];

            $arIblocks = \CIBlock::GetList(
                [],
                $newFilter,
            );
            while ($iblock = $arIblocks->fetch()) {
                $iblocks[] = self::getIblockElements($iblock['ID']);
            }
        }
        return $iblocks;
    }

    function getIblockElements($iblockId) {
        if (empty($iblockId)) {
            return [];
        }
        $newFilter = $this->arParams['FILTER'];
        $newFilter['IBLOCK_ID'] = $iblockId;
        $arElements = \CIBlockElement::GetList(
            [],
            $newFilter,
        );
        $iblockElements = [];
        while ($element = $arElements->fetch()) {
            if (!empty($element['PREVIEW_PICTURE']) && is_numeric($element['PREVIEW_PICTURE'])) {
                $element['PREVIEW_PICTURE'] = \CFile::getFileArray($element['PREVIEW_PICTURE']);
            }
            $iblockElements[] = $element;
        }
        return $iblockElements;
    }
}

?>