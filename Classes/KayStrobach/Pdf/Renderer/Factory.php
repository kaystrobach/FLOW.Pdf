<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 07.06.15
 * Time: 18:39
 */

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