<?php
function getElementIdFromList($propertyCode, $propertyKey, $IBLOCK_ID) {
    static $propertyValuesCache = [];

    if (!isset($propertyValuesCache[$propertyCode])) {
        $rsEnum = CIBlockPropertyEnum::GetList([], ["CODE" => $propertyCode, "IBLOCK_ID" => $IBLOCK_ID]);
        while ($arEnum = $rsEnum->Fetch()) {
            $propertyValuesCache[$propertyCode][strtolower($arEnum["VALUE"])] = $arEnum["ID"];
        }
    }

    $propertyKey = strtolower($propertyKey);

    return $propertyValuesCache[$propertyCode][$propertyKey] ?? null;
}

function getChangedData($propertyValue, &$map) {
    if(isset($map[$propertyValue])) {
        return $map[$propertyValue];
    }
    return $propertyValue;
}

function parseCsv($data, $IBLOCK_ID, &$jobTypeMap, &$officeMap, &$locationMap) {
    $elementData = [
        "ACTIVITY" => getElementIdFromList("ACTIVITY", getChangedData($data[9], $jobTypeMap), $IBLOCK_ID),
        "FIELD" => getElementIdFromList("FIELD", $data[11], $IBLOCK_ID),
        "OFFICE" => getElementIdFromList("OFFICE", getChangedData($data[1], $officeMap), $IBLOCK_ID),
        "LOCATION" => getElementIdFromList("LOCATION", getChangedData(trim($data[2]), $locationMap), $IBLOCK_ID),
        "REQUIRE" => $data[4],
        "DUTY" => $data[5],
        "CONDITIONS" => $data[6],
        "EMAIL" => $data[12],
        "DATE" => date("d.m.Y"),
        "TYPE" => getElementIdFromList("TYPE", $data[8], $IBLOCK_ID),
        "SALARY_TYPE" => "",
        "SALARY_VALUE" => $data[7],
        "SCHEDULE" => getElementIdFromList("SCHEDULE", $data[10], $IBLOCK_ID)
    ];
    
    foreach ($elementData as $key => &$value) {
        $value = trim($value);
        $value = str_replace("\n", "", $value);
        
        if (stripos($value, "•") !== false) {
            $value = explode("•", $value);
            array_shift($value);
            $value = array_map("trim", $value);
        }
    }
    
    if (in_array($elementData["SALARY_VALUE"], ["-", ""])) {
        $elementData["SALARY_VALUE"] = "";
    } elseif ($elementData["SALARY_VALUE"] === "по договоренности") {
        $elementData["SALARY_VALUE"] = "";
        $elementData["SALARY_TYPE"] = "Договорная";
    } else {
        $arSalary = explode(" ", $elementData["SALARY_VALUE"]);
        
        if (in_array($arSalary[0], ["от", "до"])) {
            $elementData["SALARY_TYPE"] = strtoupper($arSalary[0]);
            array_shift($arSalary);
            $elementData["SALARY_VALUE"] = implode(" ", $arSalary);
        } else {
            $elementData["SALARY_TYPE"] = "=";
        }
    }
    
    $elementData["SALARY_TYPE"] = getElementIdFromList("SALARY_TYPE", $elementData["SALARY_TYPE"], $IBLOCK_ID);
    
    return $elementData;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule("iblock");

$officeMap = [
    "СВЕЗА Тюмень\n(Усть-Ишимский филиал )" => "СВЕЗА Тюмень (Усть-Ишимский филиал )",
    "СВЕЗА Ресурс" => "Свеза Ресурс"
];
$locationMap = [
    "Верхняя Синячиха" => "Верхняя Синячиха, Свердловская область",
    "Усть-Ишим" => "Усть-Ишим, Омская область",
    "Мантурово" => "Мантурово, Костромская область",
    "Новатор" => "Новатор, Вологодская область",
    "Уральский" => "Уральский, Пермский край",
    "Гамбург" => "Гамбург, Германия"
];
$jobTypeMap = [
    "Проектная/Временная работа" => "Временная занятость"  
];

$isHeaderRow = true;
$IBLOCK_ID = 6;
$element = new CIBlockElement;

if (($handle = fopen("vacancy.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if ($isHeaderRow) {
            $isHeaderRow = false;
            continue;
        }
        
        $elementData = parseCsv($data, $IBLOCK_ID, $jobTypeMap, $officeMap, $locationMap);
        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $elementData,
            "NAME" => $data[3],
            "ACTIVE" => end($data) ? "Y" : "N",
        ];
        
        if ($PRODUCT_ID = $element->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } 
        else {
            echo "Error: " . $element->LAST_ERROR . "<br>";
        }
    }
    fclose($handle);
}
?>

