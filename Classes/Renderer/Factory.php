<?php

namespace KayStrobach\Pdf\Renderer;

class Factory
{
    /**
     * @param string $rendererName
     *
     * @return AbstractRenderer
     */
    public static function get($rendererName)
    {
        switch (strtolower($rendererName)) {
            case 'mpdf':
                return new \KayStrobach\Pdf\Renderer\MPdfRenderer();
            case 'dompdf':
                return new \KayStrobach\Pdf\Renderer\DomPdfRenderer();
            case 'wkhtmltopdf':
                return new \KayStrobach\Pdf\Renderer\WkHtmlToPdfRenderer();
            case 'chromium':
                return new \KayStrobach\Pdf\Renderer\ChromiumRenderer();
            default:
                if (class_exists($rendererName)) {
                    if (is_subclass_of($rendererName, AbstractRenderer::class)) {
                        return new $rendererName();
                    }
                }
                throw new \InvalidArgumentException('Renderer ' . $rendererName . ' is not supported');
        }

    }
}
