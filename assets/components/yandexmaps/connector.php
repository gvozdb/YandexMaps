<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var YandexMaps $YandexMaps */
$YandexMaps = $modx->getService('yandexmaps', 'YandexMaps', $modx->getOption('yandexmaps_core_path', null, $modx->getOption('core_path') . 'components/yandexmaps/') . 'model/yandexmaps/');
$modx->lexicon->load('yandexmaps:default');

// handle request
$corePath = $modx->getOption('yandexmaps_core_path', null, $modx->getOption('core_path') . 'components/yandexmaps/');
$path = $modx->getOption('processorsPath', $YandexMaps->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));