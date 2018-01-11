<?php

namespace KayStrobach\Pdf\Renderer;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Exception\StopActionException;


class MPdfRenderer extends AbstractRenderer {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Utility\Environment
	 */
	protected $environment;

	/**
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 * @Flow\Inject
	 */
	protected $systemLogger;

    /**
     * @var \KayStrobach\Pdf\Log\Logger
     * @Flow\Inject
     */
    protected $psrLogger;

	/**
	 *
	 */
	protected function initLibrary() {
	    #if (!defined('_MPDF_TTFONTDATAPATH')) {
        #    define('_MPDF_TTFONTDATAPATH', $this->environment->getPathToTemporaryDirectory());
        #}
        #if (!defined('_MPDF_TEMP_PATH')) {
        #    define('_MPDF_TEMP_PATH', $this->environment->getPathToTemporaryDirectory());
        #}
	}

    /**
     * @param string $html html code to render into an pdf
     * @throws \Mpdf\MpdfException
     * @throws \Exception
     * @return string
     */
	protected function convert($html = '') {
		if($this->getOption('orientation') === 'landscape') {
			$orientation = 'L';
		} else {
			$orientation = 'P';
		}

		$mpdfOptions = [
            'mode' => 'utf-8',
            'format' => $this->getOption('papersize'),
            'orientation' => $orientation,
            'setAutoTopMargin' => true,
            'setAutoBottomMargin' => true,
            'debug' => $this->getOption('debug'),
            'debugFonts' => $this->getOption('debug'),
            'showStats' => $this->getOption('debug'),
            'simpleTables' => true,
            'CSSselectMedia' => 'screen',
        ];

        $mpdf = new Mpdf($mpdfOptions);
        $mpdf->setLogger($this->psrLogger);
        $mpdf->SetTitle($this->getOption('filename'));
        $mpdf->SetCreator('KayStrobach.Pdf via https://github.com/kaystrobach/Flow.Pdf');
        $mpdf->SetSubject($this->getOption('filename'));
        $mpdf->SetKeywords($this->getOption('filename'));

		$this->systemLogger->log(
		    'mPDF Options',
            LOG_INFO,
            $mpdfOptions
        );

		$mpdf->WriteHTML($html);

		$this->systemLogger->log(
		    'mPDF Content',
            LOG_DEBUG,
            $html
        );
        //$this->getOption('filename'), 'I'
		return $mpdf->Output(FLOW_PATH_DATA . 'output.pdf', Destination::FILE);

        $this->systemLogger->log(
            'mPDF done',
            LOG_DEBUG
        );

        throw new StopActionException();
	}
}
