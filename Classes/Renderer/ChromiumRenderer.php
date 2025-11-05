<?php

namespace KayStrobach\Pdf\Renderer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Neos\Flow\Annotations as Flow;

class ChromiumRenderer extends AbstractRenderer
{
    /**
     * @Flow\Inject
     * @var LoggerInterface
     */
    protected $logger;

    protected function initLibrary()
    {
        // TODO: Implement initLibrary() method.
    }

    protected function convert($html = '')
    {
        $outputFile = tempnam(FLOW_PATH_DATA . 'Temporary', 'chromium-output');
        $inputFile = tempnam(FLOW_PATH_DATA . 'Temporary', 'chromium-input');
        $inputFileExt = $inputFile . '.html';

        file_put_contents($inputFileExt, $html);

        $orientation = 'Portrait';
        if ($this->getOption('orientation') === 'landscape') {
            $orientation = 'Landscape';
        }

        $command = \sprintf(
            implode(' ',
                [
                    'chromium',
                    '--headless',
                    '--disable-gpu',
                    '--no-sandbox',
                    '--print-to-pdf=%s',
                    '--print-to-pdf-no-header',
                    '--run-all-compositor-stages-before-draw',
                    '--virtual-time-budget=2000',
                    // PDF options via emulated print settings
                    '--allow-file-access-from-files',
                    '%s',
                ]
            ),
            escapeshellarg($outputFile),
            escapeshellarg('file://' . $inputFileExt),
        );
        $this->logger->debug('chromium command', ['command' => $command]);
        $process = Process::fromShellCommandline(
            $command,
            null,
            //['LANG' => 'C.UTF-8', 'LC_ALL' => 'C.UTF-8']
        );
        $process->start();
        $process->wait();

        $this->logger->debug(
            'chromium result',
            [
                'output' => $process->getOutput() . $process->getErrorOutput(),
                'exitCodeText' => $process->getExitCodeText(),
                'exitCode' => $process->getExitCode()
            ]
        );

        $buffer = file_get_contents($outputFile);
        unlink($outputFile);
        unlink($inputFile);
        unlink($inputFileExt);
        return $buffer;
    }
}
