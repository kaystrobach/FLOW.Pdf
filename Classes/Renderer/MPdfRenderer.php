<?php

namespace KayStrobach\Pdf\Renderer;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Neos\Flow\Annotations as Flow;


class MPdfRenderer extends AbstractRenderer
{

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Utility\Environment
     */
    protected $environment;

    /**
     * @var \Neos\Flow\Log\PsrSystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * @Flow\InjectConfiguration
     * @var array
     */
    protected $settings;

    /**
     *
     */
    protected function initLibrary()
    {
        $this->systemLogger->debug('You are still using deprecated initLibrary call');
    }

    /**
     *
     */
    protected function convert($html = '')
    {
        $orientation = '';
        if ($this->getOption('orientation') === 'landscape') {
            $orientation = '-L';
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

            if ($this->settings['Renderers']['Mpdf']['WatermarkText'] !== '') {
                $mpdf->SetWatermarkText($this->settings['Renderers']['Mpdf']['WatermarkText']);
                $mpdf->showWatermarkText = true;
            }

            $mpdf->showWatermarkImage = true;

            $mpdf->debug = $this->getOption('debug');
            $mpdf->showImageErrors = true;

            $mpdf->setAutoTopMargin = true;
            $mpdf->setAutoBottomMargin = true;

            $this->systemLogger->debug(
                'Pdf settings: ',
                [
                    'watermark' => $this->settings['Renderers']['Mpdf']['WatermarkText'],
                    'tempDir' => $tempDir,
                    'debug' => $mpdf->debug,
                    'orientation' => $mpdf->CurOrientation
                ]
            );

            $mpdf->WriteHTML($html);
            return $mpdf->Output(
                '',
                Destination::STRING_RETURN
            );
        } catch (\Mpdf\MpdfException $e) {
            $this->systemLogger->emergency($e->getMessage());
        }
        return null;
    }
}
