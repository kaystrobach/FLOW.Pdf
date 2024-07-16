<?php

namespace KayStrobach\Pdf\Renderer;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
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
     * @Flow\InjectConfiguration
     * @var array
     */
    protected $settings;

    /**
     *
     */
    protected function initLibrary()
    {
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

            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $optionsFromSettings = $this->settings['Renderers']['Mpdf']['options'];

            $config = array_merge_recursive(
                [
                    'fontDir' => $fontDirs,
                    'fontdata' => $fontData,
                ],
                $optionsFromSettings,
                [
                    'mode' => '',
                    'format' => $this->getOption('papersize') . $orientation,
                    'tempDir' => $tempDir,
                ]
            );

            $mpdf = new Mpdf($config);

            if ($this->settings['Renderers']['Mpdf']['WatermarkText'] !== '') {
                $mpdf->SetWatermarkText($this->settings['Renderers']['Mpdf']['WatermarkText']);
                $mpdf->showWatermarkText = true;
            }

            $mpdf->showWatermarkImage = true;

            $mpdf->debug = $this->getOption('debug');
            $mpdf->showImageErrors = true;

            $mpdf->setAutoTopMargin = true;
            $mpdf->setAutoBottomMargin = true;

            $mpdf->WriteHTML($html);
            return $mpdf->Output(
                '',
                Destination::STRING_RETURN
            );
        } catch (\Mpdf\MpdfException $e) {
            throw $e;
        }
        return null;
    }
}
