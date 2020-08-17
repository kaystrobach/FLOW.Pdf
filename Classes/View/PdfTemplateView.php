<?php
namespace KayStrobach\Pdf\View;

use KayStrobach\Pdf\Renderer\Factory;
use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\View\StandaloneView;
use Neos\FluidAdaptor\View\TemplateView;
use Neos\Flow\Mvc\View\ViewInterface;

class PdfTemplateView extends StandaloneView implements ViewInterface
{
    /**
     * @var array
     */
    protected $supportedOptions = [
        'templateRootPathPattern' => [
            '@packageResourcesPath/Private/Templates',
            'Pattern to be resolved for "@templateRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"',
            'string'
        ],
        'partialRootPathPattern' => [
            '@packageResourcesPath/Private/Partials',
            'Pattern to be resolved for "@partialRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"',
            'string'
        ],
        'layoutRootPathPattern' => [
            '@packageResourcesPath/Private/Layouts',
            'Pattern to be resolved for "@layoutRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"',
            'string'
        ],
        'templateRootPaths' => [
            [],
            'Path(s) to the template root. If null, then $this->options["templateRootPathPattern"] will be used to determine the path',
            'array'
        ],
        'partialRootPaths' => [
            [],
            'Path(s) to the partial root. If null, then $this->options["partialRootPathPattern"] will be used to determine the path',
            'array'
        ],
        'layoutRootPaths' => [
            [],
            'Path(s) to the layout root. If null, then $this->options["layoutRootPathPattern"] will be used to determine the path',
            'array'
        ],
        'templatePathAndFilenamePattern' => [
            '@templateRoot/@subpackage/@controller/@action.@format',
            'File pattern for resolving the template file. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@action", "@format"',
            'string'
        ],
        'partialPathAndFilenamePattern' => [
            '@partialRoot/@subpackage/@partial.@format',
            'Directory pattern for global partials. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@partial", "@format"',
            'string'
        ],
        'layoutPathAndFilenamePattern' => [
            '@layoutRoot/@layout.@format',
            'File pattern for resolving the layout. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@layout", "@format"',
            'string'
        ],
        'templatePathAndFilename' => [
            null,
            'Path and filename of the template file. If set,  overrides the templatePathAndFilenamePattern',
            'string'
        ],
        'layoutPathAndFilename' => [
            null,
            'Path and filename of the layout file. If set, overrides the layoutPathAndFilenamePattern',
            'string'
        ],
        'debug' => [
            false,
            'debug or not',
            'boolean'
        ],
        'disable' => [
            false,
            'disable PDF, output html',
            'boolean'
        ],
        'filename' => [
            'pdf-@time.pdf',
            'filename for download',
            'string'
        ],
        'papersize' => [
            'A4',
            'set the papersize',
            'string'
        ],
        'orientation' => [
            'portrait',
            'set the orientation',
            'string'
        ],
        'basepath' => [
            '',
            'set the basepath',
            'string'
        ],
        'dpi' => [
            120,
            'set the quality of the pdf',
            'integer'
        ],
        'enableHtml5Parser' => [
            true,
            'html5parser or not',
            'boolean'
        ],
        'enableCssFloat' => [
            true,
            'css floating or not',
            'boolean'
        ],
        'renderer' => [
            'MPdf',
            'define the pdf renderer',
            'string'
        ],
    ];

    /**
     * @Flow\Inject
     * @var \KayStrobach\Pdf\Renderer\MPdfRenderer
     */
    protected $renderHelper;

    /**
     * @param string $actionName
     * @return string
     */
    public function render($actionName = null) {
        $this->assign('PAGENO', '{PAGENO}');
        $this->assign('nbpg', '{nbpg}');


        $renderer = Factory::get($this->options['renderer']);
        $renderer->init($this->options);

        $this->modifyHeader();
        return $renderer->render(parent::render());
    }

    protected function modifyHeader()
    {
        if (!method_exists($this->controllerContext, 'getResponse')) {
            return;
        }

        /** @var \Neos\Flow\Http\Response $response */

        $filename = $this->getOption('filename');

        $response = $this->controllerContext->getResponse();
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setHeader('Content-Type', 'application/pdf; name="fileName.pdf"');
    }
}
