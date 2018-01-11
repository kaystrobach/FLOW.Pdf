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
     * @throws \InvalidArgumentException
     *
     * @return AbstractRenderer
     */
	public static function get($rendererName) {
		if(strtolower($rendererName) !== 'mpdf') {
            throw new \InvalidArgumentException(
                $rendererName . 'not supported anymore.',
                230948094368
            );
		}
		return new MPdfRenderer();
	}
}