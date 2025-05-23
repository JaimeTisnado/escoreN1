<?php
$approot = dirname($_SERVER['SCRIPT_NAME']);
$BFolder = explode('/', $approot);
define('BASE_FOLDER',$BFolder[1]);
define('DS', DIRECTORY_SEPARATOR);
//Defines.
define('FBASE', ( BASE_FOLDER == 'panel' ? NULL : BASE_FOLDER ) );
define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT'].'/'.FBASE);
define('JPATH_BASE_PANEL', JPATH_BASE.'/panel');

//--->Configuracion General
require_once(JPATH_BASE.DS.'mod.config/config.php');
#$isMostrarAlert = true;
include_once(JPATH_BASE.DS."mod.includes/sessions.php"); 
?>
<?php
define('pathChart', JPATH_BASE.DS."pChart2.1.4");
/* pChart library inclusions */
include(pathChart."/class/pData.class.php");
include(pathChart."/class/pDraw.class.php");
include(pathChart."/class/pPie.class.php");
include(pathChart."/class/pImage.class.php");

$sArray =  fndb_getDatosReporte1();
#print_r($sArray);
/* Create and populate the pData object */
$MyData = new pData();  
$MyData->addPoints($sArray,"Value");  
/* Define the absissa serie */
$MyData->addPoints(array("No Calificadas","Calificadas"),"Legenda");
$MyData->setAbscissa("Legenda");
 
/* Create the pChart object */
$myPicture = new pImage(400,200,$MyData);
 
/* Draw a gradient overlay */
$myPicture->drawGradientArea(0,0,400,0,DIRECTION_VERTICAL,array("StartR"=>238,"StartG"=>238,"StartB"=>238,"EndR"=>238,"EndG"=>238,"EndB"=>238,"Alpha"=>100));
$myPicture->drawGradientArea(0,0,400,400,DIRECTION_HORIZONTAL,array("StartR"=>238,"StartG"=>238,"StartB"=>238,"EndR"=>238,"EndG"=>238,"EndB"=>238,"Alpha"=>20));
 
/* Add a border to the picture */
$myPicture->drawRectangle(0,0,399,199,array("R"=>238,"G"=>238,"B"=>238));
 
/* Set the default font properties */
$myPicture->setFontProperties(array("FontName"=>pathChart."/fonts/verdana.ttf","FontSize"=>8,"R"=>80,"G"=>80,"B"=>80));
 
/* Create the pPie object */
$PieChart = new pPie($myPicture,$MyData);
 
/* Define the slice color */
$PieChart->setSliceColor(0,array("R"=>97,"G"=>77,"B"=>63));
$PieChart->setSliceColor(1,array("R"=>143,"G"=>197,"B"=>0));

 
/* Enable shadow computing */
$myPicture->setShadow(FALSE,array("X"=>0,"Y"=>0,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 
/* Draw a splitted pie chart */
$PieChart->draw3DPie(150,125,array("DataGapAngle"=>0,"DataGapRadius"=>0,"Border"=>TRUE,"WriteValues"=>TRUE));

/* Write the legend */ 
$PieChart->drawPieLegend(250,65,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));

/* Write the picture title */
#$myPicture->drawText(200,30,"Porcentaje Avance Global",array("FontSize"=>10,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE,"R"=>80,"G"=>80,"B"=>80));

/* Render the picture (choose the best way) */
$myPicture->Stroke();

?>