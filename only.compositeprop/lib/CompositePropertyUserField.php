<?php
use \Bitrix\Main\Localization\Loc;

class CompositePropertyUserField extends CUserTypeEntity
{
    public static function GetUserTypeDescription()
    {
        return [
            'USER_TYPE_ID' => 'compositeprop',
            'CLASS_NAME'   => __CLASS__,
            'DESCRIPTION'  => Loc::getMessage('IEX_CPROP_DESC'),
            'BASE_TYPE'    => 'string',
        ];
    }

    public static function GetDBColumnType($arUserField)
    {
        return 'text';
    }

    public static function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        $values = json_decode($arHtmlControl['VALUE']['VALUE'], true);
        $arHtmlControl['VALUE'] = $arHtmlControl['NAME'];
        return CompositePropertyIblock::GetPropertyFieldHtml(
            [
                'USER_TYPE_SETTINGS' => $arUserField['SETTINGS'],
                'MULTIPLE' => 'N',
            ],
            ['VALUE' => $values],
            $arHtmlControl
        );
    }

    public static function PrepareSettings($arUserField)
    {
        return CompositePropertyIblock::PrepareUserSettings([
            'USER_TYPE_SETTINGS' => $arUserField['SETTINGS']
        ]);
    }

    public static function GetSettingsHTML($arUserField, $strHTMLControlName, &$arPropertyFields)
    {
        return CompositePropertyIblock::GetSettingsHTML(
            [
                'USER_TYPE_SETTINGS' => $arUserField['SETTINGS']
            ],
            $strHTMLControlName,
            $arPropertyFields
        );
    }

    public static function OnBeforeSave($arUserField, $value)
    {
        return CompositePropertyIblock::ConvertToDB(
            [
                'USER_TYPE_SETTINGS' => $arUserField['SETTINGS'],
            ],
            ['VALUE' => $value]
        )['VALUE'];
    }

    public static function OnAfterFetch($arUserField, $value)
    {
        return CompositePropertyIblock::ConvertFromDB(
            [],
            ['VALUE' => $value]
        )['VALUE'];
    }
}
?>