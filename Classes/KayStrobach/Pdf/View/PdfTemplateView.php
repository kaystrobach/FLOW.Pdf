<?php
namespace KayStrobach\Pdf\View;

use KayStrobach\Pdf\Renderer\Factory;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\View\TemplateView;

class PdfTemplateView extends TemplateView {
	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'templateRootPathPattern' => array('@packageResourcesPath/Private/Templates', 'Pattern to be resolved for "@templateRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"', 'string'),
		'partialRootPathPattern' => array('@packageResourcesPath/Private/Partials', 'Pattern to be resolved for "@partialRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"', 'string'),
		'layoutRootPathPattern' => array('@packageResourcesPath/Private/Layouts', 'Pattern to be resolved for "@layoutRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"', 'string'),

		'templateRootPaths' => array(NULL, 'Path(s) to the template root. If NULL, then $this->options["templateRootPathPattern"] will be used to determine the path', 'array'),
		'partialRootPaths' => array(NULL, 'Path(s) to the partial root. If NULL, then $this->options["partialRootPathPattern"] will be used to determine the path', 'array'),
		'layoutRootPaths' => array(NULL, 'Path(s) to the layout root. If NULL, then $this->options["layoutRootPathPattern"] will be used to determine the path', 'array'),

		'templatePathAndFilenamePattern' => array('@templateRoot/@subpackage/@controller/@action.@format', 'File pattern for resolving the template file. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@action", "@format"', 'string'),
		'partialPathAndFilenamePattern' => array('@partialRoot/@subpackage/@partial.@format', 'Directory pattern for global partials. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@partial", "@format"', 'string'),
		'layoutPathAndFilenamePattern' => array('@layoutRoot/@layout.@format', 'File pattern for resolving the layout. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@layout", "@format"', 'string'),

		'templatePathAndFilename' => array(NULL, 'Path and filename of the template file. If set,  overrides the templatePathAndFilenamePattern', 'string'),
		'layoutPathAndFilename' => array(NULL, 'Path and filename of the layout file. If set, overrides the layoutPathAndFilenamePattern', 'string'),

		'debug'             => array(FALSE,           'debug or not',                'boolean'),
		'disable'           => array(FALSE,           'disable PDF, output html',    'boolean'),
		'filename'          => array('pdf-@time.pdf', 'filename for download',       'string'),
		'papersize'         => array('A4',            'set the papersize',           'string'),
		'orientation'       => array('portrait',      'set the orientation',         'string'),
		'basepath'          => array('',              'set the basepath',            'string'),
		'dpi'               => array(120,              'set the quality of the pdf',  'integer'),
		'enableHtml5Parser' => array(TRUE,            'html5parser or not',          'boolean'),
		'enableCssFloat'    => array(TRUE,            'css floating or not',         'boolean'),
		'renderer'          => array('MPdf',          'define the pdf renderer',     'string'),

);

	/**
	 * @Flow\Inject
	 * @var \KayStrobach\Pdf\Renderer\MPdfRenderer
	 */
	protected $renderHelper;

	/**
	 * @param string $actionName
	 * @return string
	 */
	public function render($actionName = NULL) {
		$this->assign('PAGENO', '{PAGENO}');
		$this->assign('nbpg', '{nbpg}');

		/** @var \TYPO3\Flow\Http\Response $response */
		$response = $this->controllerContext->getResponse();

		$renderer = Factory::get($this->options['renderer']);
		$renderer->init($this->options);

		$response->setHeader('Content-Disposition', 'inline; filename="fname.pdf"');
		$response->setHeader('Content-Type', 'application/pdf; name="fileName.pdf"');

		return $renderer->render(parent::render($actionName));
	}
}