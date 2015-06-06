<?php
namespace KayStrobach\Pdf\View;


use TYPO3\Fluid\View\TemplateView;
use TYPO3\Flow\Annotations as Flow;

class PdfTemplateView extends TemplateView{
	/**
	 * @var \KayStrobach\Pdf\ViewHelpers\Renderer\MPdfRenderer()
	 */
	protected $renderHelper;

	/**
	 * @param string $actionName
	 * @return string
	 */
	public function render($actionName = NULL) {
		/** @var \TYPO3\Flow\Http\Response $response */
		$response = $this->controllerContext->getResponse();

		$this->renderHelper->init($this->options);

		$response->setHeader('Content-Disposition', 'inline; filename="fname.pdf"');
		$response->setHeader('Content-Type', 'application/pdf; name="fileName.pdf"');
		return $this->renderHelper->render(parent::render());
	}
}