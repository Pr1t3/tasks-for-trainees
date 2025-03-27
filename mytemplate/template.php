<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
?>

<div class="contact-form">
    <?php if ($arResult['FORM_HEADER'] ?? ''): ?>
        <div class="contact-form__head">
            <?php if ($arResult['FORM_TITLE'] ?? ''): ?>
                <div class="contact-form__head-title"><?= htmlspecialchars($arResult['FORM_TITLE']) ?></div>
            <?php endif; ?>
            
            <?php if ($arResult['FORM_DESCRIPTION'] ?? ''): ?>
                <div class="contact-form__head-text"><?= htmlspecialchars($arResult['FORM_DESCRIPTION']) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form class="contact-form__form" action="/" method="POST">
        <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>">
        <input type="hidden" name="web_form_submit" value="Y">
        <?=bitrix_sessid_post()?>

        <div class="contact-form__form-inputs">
            <?if(isset($arResult['arAnswers']['name'])):?>
                <?$question = $arResult['QUESTIONS']['name'];?>
                <div class="input contact-form__input">
                    <label class="input__label" for="medicine_name">
                        <div class="input__label-text"><?=$question['CAPTION']?><?=$question['REQUIRED'] === 'Y' ? ' *' : ''?></div>
                        <input class="input__input" type="text" id="medicine_name" name="form_text_<?=$question['STRUCTURE'][0]['ID']?>" value="" required="">
                        <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
                    </label>
                </div>
            <?endif;?>
            <?if(isset($arResult['arAnswers']['company'])):?>
                <?$question = $arResult['QUESTIONS']['company'];?>
                <div class="input contact-form__input">
                    <label class="input__label" for="medicine_company">
                        <div class="input__label-text"><?=$question['CAPTION']?><?=$question['REQUIRED'] === 'Y' ? ' *' : ''?></div>
                        <input class="input__input" type="text" id="medicine_company" name="form_text_<?=$question['STRUCTURE'][0]['ID']?>" value="" required="">
                        <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
                    </label>
                </div>
            <?endif;?>
            
            <?if(isset($arResult['arAnswers']['email'])):?>
                <?$question = $arResult['QUESTIONS']['email'];?>
                <div class="input contact-form__input">
                        <label class="input__label" for="medicine_email">
                        <div class="input__label-text"><?=$question['CAPTION']?><?=$question['REQUIRED'] === 'Y' ? ' *' : ''?></div>
                        <input class="input__input" type="email" id="medicine_email" name="form_email_<?=$question['STRUCTURE'][0]['ID']?>" value="" required="">
                        <div class="input__notification">Неверный формат почты</div>
                    </label>
                </div>
            <?endif;?>
            <?if(isset($arResult['arAnswers']['phone'])):?>
                <?$question = $arResult['QUESTIONS']['phone'];?>
                <div class="input contact-form__input">
                    <label class="input__label" for="medicine_phone">
                        <div class="input__label-text"><?=$question['CAPTION']?><?=$question['REQUIRED'] === 'Y' ? ' *' : ''?></div>
                        <input class="input__input" type="tel" id="medicine_phone"
                            data-inputmask="'mask': '+79999999999', 'clearIncomplete': 'true'" maxlength="12"
                            x-autocompletetype="phone-full" name="form_text_<?=$question['STRUCTURE'][0]['ID']?>" value="" required="">
                    </label>
                </div>
            <?endif;?>
        </div>
        <?if(isset($arResult['arAnswers']['message'])):?>
            <?$question = $arResult['QUESTIONS']['message'];?>
            <div class="contact-form__form-message">
                <div class="input"><label class="input__label" for="medicine_message">
                    <div class="input__label-text"><?=$question['CAPTION']?><?=$question['REQUIRED'] === 'Y' ? ' *' : ''?></div>
                    <textarea class="input__input" type="text" id="medicine_message" name="form_textarea_<?=$question['STRUCTURE'][0]['ID']?>" value=""></textarea>
                    <div class="input__notification"></div>
                </label></div>
            </div>
        <?endif;?>
        <div class="contact-form__bottom">
            <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы&nbsp;подтверждаете, что
            ознакомлены, полностью согласны и&nbsp;принимаете условия &laquo;Согласия на&nbsp;обработку персональных
            данных&raquo;.
            </div>
            <button class="form-button contact-form__bottom-button"
                    data-success="Отправлено"
                    data-error="Ошибка отправки">
                <div class="form-button__title">
                    <?= htmlspecialchars($arResult['arForm']['BUTTON'] ?? 'Отправить') ?>
                </div>
            </button>
        </div>
    </form>
</div>