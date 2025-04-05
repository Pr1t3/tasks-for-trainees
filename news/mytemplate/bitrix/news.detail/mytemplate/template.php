<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}

$APPLICATION->SetTitle($arResult["NAME"]);
?>

<div class="news-detail">
    <div class="article-card">
        <?if((!isset($arParams["DISPLAY_NAME"]) || $arParams["DISPLAY_NAME"]!="N") && $arResult["NAME"]):?>
            <div class="article-card__title"><?=$arResult["NAME"]?></div>
        <?endif;?>
        <?if((!isset($arParams["DISPLAY_DATE"]) || $arParams["DISPLAY_DATE"]!="N") && $arResult["DISPLAY_ACTIVE_FROM"]):?>
            <div class="article-card__date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
        <?endif;?>
        <div class="article-card__content">
            <?if((!isset($arParams["DISPLAY_PICTURE"]) || $arParams["DISPLAY_PICTURE"]!="N") && is_array($arResult["DETAIL_PICTURE"])):?>
                <div class="article-card__image sticky">
                    <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="" data-object-fit="cover"/>
                </div>
            <?endif;?>
            <div class="article-card__text">
                <div class="block-content" data-anim="anim-3">
                    <?if($arResult["DETAIL_TEXT"] <> ''):?>
                        <p><?=$arResult["DETAIL_TEXT"]?></p>
                    <?endif;?>
                <a class="article-card__button" href="/news/">Назад к новостям</a>
                </div>
            </div>
        </div>
    </div>
</div>