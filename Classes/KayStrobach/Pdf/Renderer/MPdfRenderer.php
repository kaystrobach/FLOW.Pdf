<?php

namespace KayStrobach\Pdf\Renderer;

use TYPO3\Flow\Annotations as Flow;


class MPdfRenderer extends AbstractRenderer {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Utility\Environment
	 */
	protected $environment;

	/**
	 *
	 */
	protected function initLibrary() {
		if(!class_exists('mPDF', FALSE)) {
			$autoloadPath = FLOW_PATH_PACKAGES . 'Libraries/mpdf/mpdf/mpdf.php';
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
		$mpdf=new \mPDF();
		#$mpdf->debug = TRUE;
		$mpdf->setAutoTopMargin = TRUE;
		$mpdf->setAutoBottomMargin = TRUE;
		$mpdf->WriteHTML($html);
		$mpdf->Output($this->getOption('filename'), 'I');
	}
}
