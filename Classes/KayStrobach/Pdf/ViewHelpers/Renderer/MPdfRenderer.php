<?php

namespace KayStrobach\Pdf\ViewHelpers\Renderer;

class MPdfRenderer extends AbstractRenderer {

	/**
	 *
	 */
	protected function initLibrary() {
		if(!class_exists('mPDF', FALSE)) {
			$autoloadPath = FLOW_PATH_PACKAGES . 'Libraries/mpdf/mpdf/mpdf.php';
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
		$mpdf->WriteHTML($html);
		$mpdf->Output($this->getOption('filename'), 'I');
	}
}
