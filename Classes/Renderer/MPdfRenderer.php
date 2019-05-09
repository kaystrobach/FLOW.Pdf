<?php

namespace KayStrobach\Pdf\Renderer;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
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
		$this->systemLogger->log('You are still using deprecated initLibrary call', LOG_DEBUG);
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

		$mpdf = new Mpdf(
		    [
		        '',
                $this->getOption('papersize') . $orientation
            ]
        );

		$mpdf->debug = $this->getOption('debug');
        $mpdf->PDFA = true;

		$mpdf->setAutoTopMargin = TRUE;
		$mpdf->setAutoBottomMargin = TRUE;

		$this->systemLogger->log('Paperorientation: ' . $orientation);

		$mpdf->WriteHTML($html);
		return $mpdf->Output(
		    '',
            Destination::STRING_RETURN
        );
	}
}
