<?php

namespace KayStrobach\Pdf\Renderer;

abstract class AbstractRenderer {
	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * @var integer
	 */
	protected $errorReporting = NULL;

	/**
	 * @param array $options
	 */
	public function init($options) {
		$this->options = $options;
	}

	/**
	 * @param $name
	 * @param null $default
	 * @return null
	 */
	public function getOption($name, $default = NULL) {
		if(array_key_exists($name, $this->options)) {
			return $this->options[$name];
		} else {
			return $default;
		}
	}

	/**
	 * @param string $html
	 */
	public function render($html = '') {
		$this->prepareEnvironment();
		$buffer = $this->convert($html);
		$this->cleanupEnvironment();
		return $buffer;
	}

	/**
	 *
	 */
	protected  function prepareEnvironment() {
		ini_set('memory_limit', '512M');
		$this->disableErrorReporting();
		ob_end_clean();
		$this->initLibrary();
	}

	/**
	 *
	 */
	protected  function disableErrorReporting() {
		$this->errorReporting = error_reporting();
		error_reporting(0);
	}

	/**
	 *
	 */
	protected function enableErrorReporting() {
		error_reporting($this->errorReporting);
	}

	/**
	 *
	 */
	protected  function cleanupEnvironment() {
		$this->enableErrorReporting();
	}

	/**
	 *
	 */
	abstract protected function initLibrary();

	/**
	 *
	 */
	abstract protected function convert($html = '') ;
}