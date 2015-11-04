<?php

/**
 * The base class for YandexMaps.
 */
class YandexMaps {
	/* @var modX $modx */
	public $modx;
	/* @var pdoTools $pdoTools */
	public $pdoTools;
	public $initialized = array();


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('yandexmaps_core_path', $config, $this->modx->getOption('core_path') . 'components/yandexmaps/');
		$assetsUrl = $this->modx->getOption('yandexmaps_assets_url', $config, $this->modx->getOption('assets_url') . 'components/yandexmaps/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/'
		), $config);

		$this->modx->addPackage('yandexmaps', $this->config['modelPath']);
		$this->modx->lexicon->load('yandexmaps:default');
	}
	
	
	/**
	 * Initializes component into different contexts.
	 *
	 * @param string $ctx The context to load. Defaults to web.
	 * @param array $scriptProperties
	 *
	 * @return boolean
	 */
	public function initialize($ctx = 'web', $scriptProperties = array()) {
		$this->config = array_merge($this->config, $scriptProperties);
		
		$this->config['ctx'] = $ctx;
		if (!empty($this->initialized[$ctx])) {
			return true;
		}
		switch ($ctx) {
			case 'mgr': break;
			default:
				if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
					/*$config = $this->makePlaceholders($this->config);
					$this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));*/
					
					$this->modx->regClientCSS($this->config['cssUrl'].'web/default.css');
					
					/*$this->modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
						<script type="text/javascript">
							if(typeof jQuery == "undefined") {
								document.write("<script src=\"http://yandex.st/jquery/2.1.1/jquery.min.js\" type=\"text/javascript\"><\/script>");
							}
							if(typeof ymaps == "undefined") {
								document.write("<script src=\"http://api-maps.yandex.ru/2.1/?lang=ru_RU&load=Map,Placemark,GeoObjectCollection,map.addon.balloon,geoObject.addon.balloon,package.controls\" type=\"text/javascript\"><\/script>");
							}
						</script>
						'));*/
				}
				
				$this->initialized[$ctx] = true;
				break;
		}
		return true;
	}
	
	
	/**
	 * Method for transform array to placeholders
	 *
	 * @var array $array With keys and values
	 * @var string $prefix Prefix for array keys
	 *
	 * @return array $array Two nested arrays with placeholders and values
	 */
	public function makePlaceholders(array $array = array(), $prefix = '') {
		if (!$this->pdoTools) {
			$this->loadPdoTools();
		}

		return $this->pdoTools->makePlaceholders($array, $prefix);
	}
	
	
	/**
	 * Loads an instance of pdoTools
	 *
	 * @return boolean
	 */
	public function loadPdoTools() {
		if (!is_object($this->pdoTools) || !($this->pdoTools instanceof pdoTools)) {
			/** @var pdoFetch $pdoFetch */
			$fqn = $this->modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
			if ($pdoClass = $this->modx->loadClass($fqn, '', false, true)) {
				$this->pdoTools = new $pdoClass($this->modx, $this->config);
			}
			elseif ($pdoClass = $this->modx->loadClass($fqn, MODX_CORE_PATH . 'components/pdotools/model/', false, true)) {
				$this->pdoTools = new $pdoClass($this->modx, $this->config);
			}
			else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not load pdoFetch from "MODX_CORE_PATH/components/pdotools/model/".');
			}
		}
		return !empty($this->pdoTools) && $this->pdoTools instanceof pdoTools;
	}

}