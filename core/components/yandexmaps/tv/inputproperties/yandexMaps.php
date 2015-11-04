<?php
/**
 * @package modx
 * @subpackage processors.element.tv.inputproperties
 */

$modx->lexicon->load('yandexmaps:tv');
$lang = $modx->lexicon->fetch('yandexmaps_',true);
$modx->smarty->assign('ymlex', $lang);

$corePath = $modx->getOption('table.core_path', null, $modx->getOption('core_path') . 'components/yandexmaps/');
return $modx->controller->fetchTemplate($corePath . 'tv/inputproperties/tpl/tv.yandexMaps.tpl');