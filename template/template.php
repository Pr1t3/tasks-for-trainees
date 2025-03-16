<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<div class="news-list">
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <div class="news-item">
			<div class="main-info">
				<h2 class="name">
					<a href=<?= $arItem["DETAIL_PAGE_URL"] ?>><?= $arItem["NAME"] ?></a>
				</h2>
				<p class="preview-text"><?= $arItem["PREVIEW_TEXT"] ?></p>
				<p class="date"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></p>
			</div>
			<?if($arItem["PREVIEW_PICTURE"]):?>
			<div class="picture-div">
				<img
					class="preview-picture"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					/>
			</div>
			<?endif?>
        </div>
		<?if(next($arResult["ITEMS"])):?>
			<div class="divider-line"></div>
		<?endif?>
	<?php endforeach; ?>
</div>