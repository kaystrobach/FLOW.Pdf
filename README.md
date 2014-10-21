FLOW.Pdf
========

This Package makes it possible to convert the rendered HTML directly to PDF on the fly using the mpdf library.

So it's quite easy to create PDFs on the fly.

Example useage in a FLOW Package called Some.Package
----------------------------------------------------

Taken from project/Packages/Application/Some.Package/Resources/Private/Layouts/Pdf.html
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
