<?php

namespace KayStrobach\Pdf\Renderer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Neos\Flow\Annotations as Flow;

class WkHtmlToPdfRenderer extends AbstractRenderer
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
        $outputFile = tempnam(FLOW_PATH_DATA . 'Temporary', 'wkhtml2pdf-output');

        $orientation = 'Portrait';
        if ($this->getOption('orientation') === 'landscape') {
            $orientation = 'Landscape';
        }

        $command = \sprintf(
            implode(' ',
            [
                'wkhtmltopdf',
                '--enable-local-file-access',
                '--allow %s',
                '--load-error-handling ignore',
                '--load-media-error-handling ignore',
                '--javascript-delay 2000',
                '--encoding utf-8',
                '--page-size ' . $this->getOption('pageSize', 'A4'),
                '--orientation ' . $orientation,
                '--margin-top 15mm --margin-right 10mm --margin-bottom 15mm --margin-left 10mm',
                '-', // read from stdin
                '%s',
            ]
            ),
            escapeshellarg(FLOW_PATH_DATA),
            escapeshellarg($outputFile),
        );
        $this->logger->debug('WkHtml2Pdf command', ['command' => $command]);
        $process = Process::fromShellCommandline(
            $command

        );
        $process->setInput($html);
        $process->start();
        $process->wait();

        $this->logger->debug(
            'WkHtml2Pdf result',
            [
                'output' => $process->getOutput() . $process->getErrorOutput(),
                'exitCodeText' => $process->getExitCodeText(),
                'exitCode' => $process->getExitCode()
            ]
        );

        $buffer = file_get_contents($outputFile);
        unlink($outputFile);
        return $buffer;
    }
}
