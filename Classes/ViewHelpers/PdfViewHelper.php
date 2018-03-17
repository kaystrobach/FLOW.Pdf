<?php
namespace KayStrobach\Pdf\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "SBS.LaPo".              *
 *                                                                        *
 *                                                                        */

use KayStrobach\Pdf\Renderer\Factory;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Fluid\Core\ViewHelper\AbstractViewHelper;

class PdfViewHelper extends AbstractViewHelper{

	/**
	 * @var int
	 */
	protected $errorReporting = 0;

    /**
     * @throws \Neos\Fluid\Core\ViewHelper\Exception
     */
	public function initializeArguments() {
		$this->registerArgument('debug',               'boolean', 'debug or not',                0, 0);
		$this->registerArgument('disable',             'boolean', 'disable PDF, output html',    0, 0);
		$this->registerArgument('filename',            'string',  'filename for download',       0, 'pdf-' . time() . '.pdf');
		$this->registerArgument('papersize',           'string',  'set the papersize',           0, 'A4');
		$this->registerArgument('orientation',         'string',  'set the orientation',         0, 'portrait');
		$this->registerArgument('basepath',            'string',  'set the basepath',            0, '');
		$this->registerArgument('dpi',                 'integer', 'set the quality of the pdf',  0, '96');
		$this->registerArgument('enableHtml5Parser',   'boolean', 'html5parser or not',          0, 1);
		$this->registerArgument('enableCssFloat',      'boolean', 'css floating or not',         0, 1);
		$this->registerArgument('renderer',             'string', 'define the pdf renderer',     0, 'MPdf');
	}

	/**
	 *
	 * @throws \Neos\Flow\Mvc\Exception\StopActionException
	 * @return string the rendered string
	 */
	public function render() {
		if($this->arguments['disable']) {
            return $this->renderChildren();
        }

        $renderer = Factory::get($this->arguments['renderer']);

        $renderer->init($this->arguments);
        $renderer->render($this->renderChildren());

		//use instead of exit ;)
		throw new StopActionException();
	}
}
