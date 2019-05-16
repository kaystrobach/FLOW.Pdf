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

		try {
		    $tempDir = FLOW_PATH_TEMPORARY . '/KayStrobach.Pdf';
            if (!@mkdir($tempDir, 0777, true) && !is_dir($tempDir)) {
                throw new \InvalidArgumentException('Could not create TempDir ' . $tempDir);
            }

            $mpdf = new Mpdf(
                [
                    'mode' => '',
                    'format' => $this->getOption('papersize') . $orientation,
                    'tempDir' => $tempDir
                ]
            );

            $mpdf->debug = $this->getOption('debug');
            $mpdf->showImageErrors = true;

            $mpdf->setAutoTopMargin = TRUE;
            $mpdf->setAutoBottomMargin = TRUE;

            $this->systemLogger->log('Paperorientation: ' . $orientation);

            $mpdf->WriteHTML($html);
            return $mpdf->Output(
                '',
                Destination::STRING_RETURN
            );
        } catch (\Mpdf\MpdfException $e) {
		    $this->systemLogger->log($e->getMessage());
        }
        return null;
	}
}
