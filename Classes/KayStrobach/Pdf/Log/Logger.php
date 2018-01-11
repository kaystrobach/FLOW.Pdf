<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Pdf\Log;

use TYPO3\Flow\Annotations as Flow;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    /**
     * @var \TYPO3\Flow\Log\SystemLoggerInterface
     * @Flow\Inject
     */
    protected $systemLogger;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $this->systemLogger->log(
            'mPDF: ' . $message,
            $level,
            $context
        );
    }
}