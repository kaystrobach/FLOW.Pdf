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
     * @var \Neos\Flow\Log\SystemLoggerInterface
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
        $this->systemLogger->log('You are still using deprecated initLibrary call', LOG_DEBUG);
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

            $this->systemLogger->log($this->settings['Renderers']['Mpdf']['WatermarkText']);

            if ($this->settings['Renderers']['Mpdf']['WatermarkText'] !== '') {
                $mpdf->SetWatermarkText($this->settings['Renderers']['Mpdf']['WatermarkText']);
                $mpdf->showWatermarkText = true;
            }

            $mpdf->showWatermarkImage = true;
            
            $mpdf->debug = $this->getOption('debug');
            $mpdf->showImageErrors = true;

            $mpdf->setAutoTopMargin = true;
            $mpdf->setAutoBottomMargin = true;

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
