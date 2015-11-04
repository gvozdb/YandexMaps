<?php

/**
 * Class YandexMapsMainController
 */
abstract class YandexMapsMainController extends modExtraManagerController {
	/** @var YandexMaps $YandexMaps */
	public $YandexMaps;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('yandexmaps_core_path', null, $this->modx->getOption('core_path') . 'components/yandexmaps/');
		require_once $corePath . 'model/yandexmaps/yandexmaps.class.php';

		$this->YandexMaps = new YandexMaps($this->modx);
		$this->addCss($this->YandexMaps->config['cssUrl'] . 'mgr/default.css');
		//$this->addJavascript($this->YandexMaps->config['jsUrl'] . 'mgr/yandexmaps.js');
		$this->addHtml('
		<script type="text/javascript">
			YandexMaps.config = ' . $this->modx->toJSON($this->YandexMaps->config) . ';
			YandexMaps.config.connector_url = "' . $this->YandexMaps->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('yandexmaps:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends YandexMapsMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}