<?php

namespace KayStrobach\Pdf\Renderer;

class Factory {
	/**
	 * @param string $rendererName
	 *
	 * @return DomPdfRenderer|AbstractRenderer
	 */
	public static function get($rendererName) {
		if(strtolower($rendererName) === 'mpdf') {
			$renderer = new \KayStrobach\Pdf\Renderer\MPdfRenderer();
		} else {
			$renderer = new \KayStrobach\Pdf\Renderer\DomPdfRenderer();
		}
		return $renderer;
	}
}