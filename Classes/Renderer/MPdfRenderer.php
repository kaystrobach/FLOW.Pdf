<?php

namespace KayStrobach\Pdf\Renderer;

use Neos\Flow\Annotations as Flow;


class MPdfRenderer extends AbstractRenderer {

	/**
	 * @Flow\Inject
	 * @var \Neos\Flow\Utility\Environment
	 */
	protected $environment;

	/**
	 * @var \Neos\Flow\Log\SystemLoggerInterface
	 * @Flow\Inject
	 */
	protected $systemLogger;

	/**
	 *
	 */
	protected function initLibrary() {
		if(!class_exists('mPDF', FALSE)) {
			$autoloadPath = FLOW_PATH_PACKAGES . 'Libraries/mpdf/mpdf/src/Mpdf.php';
			define('_MPDF_TTFONTDATAPATH', $this->environment->getPathToTemporaryDirectory());
			define('_MPDF_TEMP_PATH', $this->environment->getPathToTemporaryDirectory());
			if(is_file($autoloadPath)) {
				require_once($autoloadPath);
			} else {
				throw new \Exception('please add mpdf/mpdf to your composer.json and install it');
			}
		}
	}

	/**
	 *
	 */
	protected function convert($html = '') {
		if($this->getOption('orientation') === 'landscape') {
			$orientation = '-L';
		} else {
			$orientation = '';
		}

		$mpdf = new \Mpdf\Mpdf(array('orientation' => $this->getOption('papersize') . $orientation));

		$mpdf->debug = $this->getOption('debug');

		$mpdf->setAutoTopMargin = TRUE;
		$mpdf->setAutoBottomMargin = TRUE;

		$this->systemLogger->log('Paperorientation: ' . $orientation);

		$mpdf->WriteHTML($html);
		$mpdf->Output($this->getOption('filename'), 'I');
	}
}
