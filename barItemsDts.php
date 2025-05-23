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
include(pathChart."/class/pImage.class.php");

$sSql = fn_EjecutarQuery("
select i.nomItem, coalesce(vr.revisados,0) calificaciones, vi.totalItems
from items i
inner join categorias c
on c.idCategoria = i.idCategoria
inner join grados g
on g.idGrado = i.idGrado
left join (
select count(*) revisados, im.idItem
from imagenesitems im
where im.flagRevisado = 1
group by im.idItem
) vr on vr.idItem = i.idItem
inner join (
select count(*) totalItems, im.idItem
from imagenesitems im
group by im.idItem
)vi on vi.idItem = i.idItem
where i.isActivo = 1
order by i.nomItem asc;
");
while ($sRow = fn_ExtraerQuery($sSql))
{
$calificaciones	= $sRow[strtolower('calificaciones')];
$totalItems		= $sRow[strtolower('totalItems')];
$porcentaje		= ($calificaciones / $totalItems) *100;
$values[]		= number_format($porcentaje,2);
$labels[]		= $sRow[strtolower('nomItem')].' ';
}
/* Create and populate the pData object */
$MyData = new pData();  
$MyData->addPoints($values,"Value");
$MyData->setAxisName(0,"Porcentaje");
#$MyData->setAxisUnit(0,"%");

$MyData->addPoints($labels,"Legenda");
$MyData->setAbscissa("Legenda");

/* Create the pChart object */
$myPicture = new pImage(500,500,$MyData);

/* Draw a gradient overlay */
$myPicture->drawGradientArea(0,0,500,0,DIRECTION_VERTICAL,array("StartR"=>238,"StartG"=>238,"StartB"=>238,"EndR"=>238,"EndG"=>238,"EndB"=>238,"Alpha"=>100));
$myPicture->drawGradientArea(0,0,500,400,DIRECTION_HORIZONTAL,array("StartR"=>238,"StartG"=>238,"StartB"=>238,"EndR"=>238,"EndG"=>238,"EndB"=>238,"Alpha"=>20));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,499,499,array("R"=>238,"G"=>238,"B"=>238));

/* Set the default font */ 
$myPicture->setFontProperties(array("FontName"=>pathChart."/fonts/verdana.ttf","FontSize"=>8,"R"=>80,"G"=>80,"B"=>80));

/* Define the chart area */ 
$myPicture->setGraphArea(100,70,450,450);

/* Draw the chart scale */ 
$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
$scaleSettings = array("XMargin"=>AUTO,"YMargin"=>10,"Floating"=>FALSE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"Pos"=>SCALE_POS_TOPBOTTOM,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries);
$myPicture->drawScale($scaleSettings); 

/* Turn on shadow computing */ 
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Create the per bar palette */
$Palette = array("0"=>array("R"=>188,"G"=>224,"B"=>46,"Alpha"=>100),
                 "1"=>array("R"=>224,"G"=>100,"B"=>46,"Alpha"=>100),
                 "2"=>array("R"=>224,"G"=>214,"B"=>46,"Alpha"=>100),
                 "3"=>array("R"=>46,"G"=>151,"B"=>224,"Alpha"=>100),
                 "4"=>array("R"=>176,"G"=>46,"B"=>224,"Alpha"=>100),
                 "5"=>array("R"=>224,"G"=>46,"B"=>117,"Alpha"=>100),
                 "6"=>array("R"=>92,"G"=>224,"B"=>46,"Alpha"=>100),
                 "7"=>array("R"=>224,"G"=>176,"B"=>46,"Alpha"=>100));

/* Draw the chart */ 
$myPicture->drawBarChart(array("DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>TRUE,"Rounded"=>TRUE,"Surrounding"=>30,"OverrideColors"=>$Palette));

/* Write the legend */ 
#$myPicture->drawLegend(450,215,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Write the chart title */  
#$myPicture->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>8)); 
#$myPicture->drawText(250,35,"Porcentaje Avance por Item",array("FontSize"=>12,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE)); 
 
/* Render the picture (choose the best way) */
$myPicture->Stroke();
?>