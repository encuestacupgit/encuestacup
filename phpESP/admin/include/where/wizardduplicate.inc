<?php
//var_dump($_POST);exit;

$cursos = explode(',', $_POST['materiasSeleccionadas']);
$encuestaID = $_POST['encuestas'];

include(esp_where('wizardfunctions'));
if(isset($_POST['encuestaGeneral']))
{
	$resultadoDuplicacion = "las encuestas general fue creada con exito";
	$periodoID = date('Y').$_POST['periodoGeneral'];

	
	
	esp_require_once('/function/survey_aggregate');
	$idNewSurvey = survey_aggregate($encuestaID);
	if($idNewSurvey !==false)
	{
		$datosAModificar['name'] = $periodoID .'_ENCUESTA_DE_SATISFACCION_U';
		$datosAModificar['title'] = 'ENCUESTA DE SATISFACCION (No llenar si haces solo Especialización)';
		$datosAModificar['subtitle'] = '';
		$sql = 'update phpesp_survey set 
												name = "'. $datosAModificar['name'] .'",
												title = "'. $datosAModificar['title'] .'",
												subtitle = "'. $datosAModificar['subtitle'] .'"
						where id ='.$idNewSurvey.';';
		$res = execute_sql($sql);
		if($res == 0) //elimino encuesta
		{
			$error[] = 'Encuesta general no se pudo crear porque ya existe'; 
			$resultadoDuplicacion = '';
			survey_purge(array($idNewSurvey));
			//echo 'entre';exit;
		}
		$sqlDelete = 'delete from cup_survey 
						where periodo_curso = "'.$periodoID.'" 
						and nro_materia = 0
						and nro_curso_id = 0;';
		$res = execute_sql($sqlDelete);
		$sqlInsert = 'insert into cup_survey (periodo_curso, nro_materia, nro_curso_id,survey_id) 
									  values ("'.$periodoID.'",0,0,'.$idNewSurvey.');';
		//echo $sqlInsert;exit;
		$res = execute_sql($sqlInsert);
	}
	else 
	{
		var_dump($idNewSurvey);exit;
	}
	
}
else 
{
	foreach($cursos as $curso)
	{
		//var_dump($curso);exit;
		if($curso != '')
		{
			$tmpData = explode('|',$curso);
			$periodoID = $tmpData[0];
			$materiaID = 	$tmpData[1];
			$cursoID = 	$tmpData[2];
			
			$datosAModificar = getDatosEncuesta($periodoID,$materiaID,$cursoID);
			//var_dump($datosAModificar);exit;
			/**
			 * datos a cambiar de la encuesta
			 * 
			 * name = '<año>_<periodo>_<materia>_<turno>' los ' ' son reemplazados por '_';
			 * title = '<materia>'
			 * subtitle = '<profe>'
			 */
			
			esp_require_once('/function/survey_aggregate');
			$idNewSurvey = survey_aggregate($encuestaID);
			//var_dump($idNewSurvey);exit;
			if($idNewSurvey !==false)
			{
				
				
				
				$sql = 'update phpesp_survey set 
												name = "'. $datosAModificar['name'] .'",
												title = "'. $datosAModificar['title'] .'",
												subtitle = "'. $datosAModificar['subtitle'] .'"
						where id ='.$idNewSurvey.';';
				$res = execute_sql($sql);
				if($res == 0) //elimino encuesta
				{
					$error[] = 'Encuesta ['.$datosAModificar['name'].'] no se pudo crear porque ya existe'; 
					survey_purge(array($idNewSurvey));
					//echo 'entre';exit;
				}
				$sqlDelete = 'delete from cup_survey 
								where periodo_curso = "'.$periodoID.'" 
								and nro_materia = '.$materiaID.'
								and nro_curso_id = '.$cursoID .';';
				$res = execute_sql($sqlDelete);
				$sqlInsert = 'insert into cup_survey (periodo_curso, nro_materia, nro_curso_id,survey_id) 
											  values ("'.$periodoID.'",'.$materiaID.','.$cursoID.','.$idNewSurvey.');';
				$res = execute_sql($sqlInsert);
			}
			else
			{
				var_dump($idNewSurvey);exit;
			}
			
			
			
		}
		
		$resultadoDuplicacion = "las encuestas fueron creadas con exito";
		
		
	}
}


?>
