<?php

namespace KayStrobach\Pdf\Renderer;

abstract class AbstractRenderer
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var integer
     */
    protected $errorReporting;

    /**
     * @param array $options
     */
    public function init(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getOption($name, $default = null)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }
        return $default;
    }

    public function render(string $html = '')
    {
        $this->prepareEnvironment();
        $buffer = $this->convert($html);
        $this->cleanupEnvironment();
        return $buffer;
    }

    /**
     *
     */
    protected function prepareEnvironment(): void
    {
        ini_set('memory_limit', '512M');
        $this->disableErrorReporting();
        ob_end_clean();
        $this->initLibrary();
    }

    /**
     *
     */
    protected function disableErrorReporting(): void
    {
        $this->errorReporting = error_reporting();
        if (!$this->getOption('debug')) {
            error_reporting(0);
        }
    }

    /**
     *
     */
    protected function enableErrorReporting(): void
    {
        error_reporting($this->errorReporting);
    }

    /**
     *
     */
    protected function cleanupEnvironment(): void
    {
        $this->enableErrorReporting();
    }

    /**
     *
     */
    abstract protected function initLibrary();

    /**
     *
     */
    abstract protected function convert($html = '');
}
