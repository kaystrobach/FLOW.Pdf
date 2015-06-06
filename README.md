FLOW.Pdf (For the TYPO3 Flow Framework)
=======================================

This Package makes it possible to convert the rendered HTML directly to PDF on the fly, using the mpdf or dompdf library.
To achieve the on the fly rendering easily it uses a fluid viewhelper.

You need one of these package installed via composer, the package itself checks if the library is missing and asks you to install it.

* dompdf/dompdf
* mpdf/mpdf


Controller Usage
----------------

The recent version of the package contains a new view.
This view can be included directly in the controller e.g. like this:

```
class FormulareController extends \TYPO3\Flow\Mvc\Controller\ActionController {
	/**
	 * @Flow\Inject
	 * @var \SBS\LaPo\Domain\Repository\StudentRepository
	 */
	protected $studentRepository;

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array(
		'pdf.html' => 'KayStrobach\Pdf\View\PdfTemplateView'
	);
```

This example uses the format pdf.html to make it easily possible to edit the template files with your favourite IDE.


Example usage in a FLOW Package called Some.Package
----------------------------------------------------

In a FLOW Layout you can wrap your generated HTML with the following ViewHelper and the output gets transformed into a PDF.

```
{namespace pdf=KayStrobach\Pdf\ViewHelpers}
<pdf:pdf enableHtml5Parser="1" disable="0" debug="1" dpi="120" renderer="mpdf">
	some html content
</pdf:pdf>
```

The parameter documentation can be obtained from the viewHelper directly see Classes/KayStrobach/Pdf/ViewHelpers/PdfViewHelper.php

A complete example may look like:
(Taken from project/Packages/Application/Some.Package/Resources/Private/Layouts/Pdf.html)
```
{namespace pdf=KayStrobach\Pdf\ViewHelpers}
<pdf:pdf enableHtml5Parser="1" disable="0" debug="1" dpi="120" renderer="mpdf">
	<!DOCTYPE html>
	<html  xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers">
		<head>
			<meta charset="utf-8">
			<title><f:render section="Title" /></title>
			<!-- Custom styles for this template -->
			<style type="text/css">
				table {
					border-collapse: collapse;
				}

				table.formblock td{
					border: 0.1mm solid black;
					vertical-align: top;
					padding: 1mm;
					width: 50%;
					height: 8mm;
				}
				table.formblock .halfcell {
					width: 25%;
				}
				table.formblockAbstand {
					margin-top: 5mm;
				}
				.label {
					font-size: 6pt;
				}
			</style>
			<f:base />
		</head>
		<body>
			<f:render section="Content" />
		</body>
	</html>
</pdf:pdf>
```
