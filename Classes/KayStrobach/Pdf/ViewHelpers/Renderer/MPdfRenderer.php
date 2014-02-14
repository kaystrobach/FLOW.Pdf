<?php

namespace KayStrobach\Pdf\ViewHelpers\Renderer;

class MPdfRenderer extends AbstractRenderer {

	/**
	 *
	 */
	protected function initLibrary() {
		if(!class_exists('mPDF', FALSE)) {
			require_once(FLOW_PATH_PACKAGES . 'Libraries/mpdf/mpdf/mpdf.php');
		}
	}

	/**
	 *
	 */
	protected function convert($html = '')
	{
		$mpdf=new \mPDF();
		#$mpdf->debug = TRUE;
		$mpdf->WriteHTML($html);
		$mpdf->Output($this->getOption('filename'), 'I');
	}
}