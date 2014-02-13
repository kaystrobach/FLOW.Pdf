<?php
namespace KayStrobach\Pdf\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "SBS.LaPo".              *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Exception\StopActionException;

class DomPdfViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper{

	/**
	 * @var int
	 */
	protected $errorReporting = 0;

	/**
	 * @todo add support for saving and linking the pdf
	 */
	function initializeArguments() {
		$this->registerArgument('debug',               'boolean', 'debug or not',                0, 0);
		$this->registerArgument('disable',             'boolean', 'disable PDF, output html',    0, 0);
		$this->registerArgument('filename',            'string',  'filename for download',       0, 'output.pdf');
		$this->registerArgument('papersize',           'string',  'set the papersize',           0, 'A4');
		$this->registerArgument('orientation',         'string',  'set the orientation',         0, 'portrait');
		$this->registerArgument('basepath',            'string',  'set the basepath',            0, '');
		$this->registerArgument('dpi',                 'integer', 'set the quality of the pdf',  0, '96');
		$this->registerArgument('enableHtml5Parser',   'boolean', 'html5parser or not',          0, 1);
		$this->registerArgument('enableCssFloat',      'boolean', 'css floating or not',         0, 1);
	}

	/**
	 *
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 * @return string the rendered string
	 */
	public function render() {
		ini_set('memory_limit', '512M');

		ob_end_clean();

		if(!$this->arguments['disable']) {
			$this->renderPdfFromHtml(
				$this->renderChildren()
			);
		} else {
			return $this->renderChildren();
		}

		//use instead of exit ;)
		throw new StopActionException();
	}

	protected  function disableErrorReporting() {
		$this->errorReporting = error_reporting();
		#error_reporting(E_ERROR | E_PARSE);
	}

	protected function enableErrorReporting() {
		error_reporting($this->errorReporting);
	}

	protected function renderPdfFromHtml($html, $forceDownload = 0) {
		$this->initializeDompdf();

		$domPdf = new \DOMPDF();
		$domPdf->set_options(
			array(
				'dpi'                 => $this->arguments['dpi'],
				'enable_html5_parser' => $this->arguments['enableHtml5Parser'],
				'enable_css_float'    => $this->arguments['enableCssFloat'],
				'enable_unicode'      => TRUE,
				// add some more options
			)
		);
		$domPdf->set_paper(
			$this->arguments['papersize'],
			$this->arguments['orientation']
		);
		$domPdf->set_base_path($this->arguments['basepath']);
		$domPdf->load_html($html);
		$domPdf->render();

		$domPdf->stream(
			$this->arguments['filename'],
			array(
				'Attachment' => $forceDownload
			)
		);

		$this->cleanupDompdf($domPdf);

	}

	protected function initializeDompdf() {
		if(class_exists('t3lib_extMgm')) {
			// the extbase way :D
			require_once(t3lib_extMgm::extPath('dompdf') . 'Resources/Private/Contrib/dompdf/dompdf_config.inc.php');
		} else {
			// the flow way
			// https://github.com/dompdf/dompdf/wiki/DOMPDF-and-Composer-Quick-start-guide
			define('DOMPDF_ENABLE_AUTOLOAD',    false);
			require_once(FLOW_PATH_PACKAGES . 'Libraries/dompdf/dompdf/dompdf_config.inc.php');
			require_once(FLOW_PATH_PACKAGES . 'Libraries/dompdf/dompdf/include/autoload.inc.php');
		}
		$this->disableErrorReporting();

	}
	protected function cleanupDompdf($domPdf) {
		$this->enableErrorReporting();
		unset($domPdf);
	}
}
