<?php

namespace KayStrobach\Pdf\Renderer;

class DomPdfRenderer extends AbstractRenderer {

	/**
	 *
	 */
	protected function initLibrary() {
		// the flow way
		// https://github.com/dompdf/dompdf/wiki/DOMPDF-and-Composer-Quick-start-guide
		define('DOMPDF_ENABLE_AUTOLOAD',    false);
		$autoloadDir = 'Libraries/dompdf/dompdf/';
		if(is_dir($autoloadDir)) {
			require_once(FLOW_PATH_PACKAGES . $autoloadDir . 'dompdf_config.inc.php');
			require_once(FLOW_PATH_PACKAGES . $autoloadDir . 'include/autoload.inc.php');
		} else {
			throw new \Exception('please add dompdf/dompdf to your composer.json and install it');
		}
	}

	/**
	 *
	 */
	protected function convert($html = '') {
		$domPdf = new \DOMPDF();
		$domPdf->set_options(
			array(
				'dpi'                 => $this->options['dpi'],
				'enable_html5_parser' => $this->options['enableHtml5Parser'],
				'enable_css_float'    => $this->options['enableCssFloat'],
				'enable_unicode'      => TRUE,
				// add some more options
			)
		);
		$domPdf->set_paper(
			$this->options['papersize'],
			$this->options['orientation']
		);
		$domPdf->set_base_path($this->options['basepath']);
		$domPdf->load_html($html);
		$domPdf->render();

		$domPdf->stream(
			$this->getOption('filename'),
			array(
				'Attachment' => $this->getOption('forceDownload', 0)
			)
		);

		$this->cleanupDompdf($domPdf);
	}
}
