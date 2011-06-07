<?php
if(isset($_POST['encuestas']))
{
	include(esp_where('wizardduplicate'));
}

/**
 * vamos a ver que sale
 */
include(esp_where('wizardfunctions'));

$materias = getMaterias();



 $bg = '';

	/* load names and titles of all surveys available to
	 * _this_ user */
	if($_SESSION['acl']['superuser'] == 'Y') {
		$sql = "SELECT s.id, s.name, s.title, s.owner, s.realm
		FROM ".$GLOBALS['ESPCONFIG']['survey_table']." s WHERE s.status = 0 ORDER BY s.id DESC";
	} else {
		$realms = array_to_insql(
			array_intersect(
				$_SESSION['acl']['pall'],
				$_SESSION['acl']['pdesign']));
		$sql = "SELECT s.id, s.name, s.title, s.owner, s.realm
			FROM ".$GLOBALS['ESPCONFIG']['survey_table']." s WHERE s.status = 0 AND (s.owner = ".
			_addslashes($_SESSION['acl']['username']) ." || s.realm $realms) ORDER BY id DESC";
	}
	$result = execute_sql($sql);
?>
<script type="text/javascript">
<!--
function validateWizard()
{
	var materias = document.getElementById('materiasCursadas');
	var encuestas = document.getElementById('encuestas');

	var esEncuestaGeneral = document.getElementById('encuestaGeneral');
	var periodos = document.getElementsByName('periodoGeneral');
	//alert(periodos[0].checked);
	if(periodos[0].checked== false && periodos[1].checked==false && esEncuestaGeneral.checked == true)
	{
		alert('por favor seleccione un periodo');
  	  	return false;
	}	
			
	
	

	var materiasSeleccionadas = '';
	for (i=0; i<materias.options.length; i++) {
	    if (materias.options[i].selected) {
	    	materiasSeleccionadas = materiasSeleccionadas + materias.options[i].value + ',';
	    }
  	}
	//alert(materiasSeleccionadas);
  	if(materiasSeleccionadas == '' && !esEncuestaGeneral.checked)
  	{
  	  	alert('por favor seleccione una materia');
  	  	return false;
  	}

  	var objetoMateriasSeleccionadas = document.getElementById('materiasSeleccionadas');
  	objetoMateriasSeleccionadas.value = materiasSeleccionadas;

  	var encuestas = document.getElementById('encuestas');

  	var seleccionoEncuesta = false;
	for (i=0; i<encuestas.options.length; i++) {
	    if (encuestas.options[i].selected) {
	    	seleccionoEncuesta = true;
	    }
  	}
	//alert(seleccionoEncuesta);
  	if(!seleccionoEncuesta)
  	{
  	  	alert('por favor seleccione una encuesta');
  	  	return false;
  	}

	
	
  	
  	return true;
	
}

//-->
</script>
</div>
</form>
<div id="divWizardFormContainer">
	<h2>Wizard Survey Design</h2>
	<?php
		if(isset($resultadoDuplicacion))
		{
			echo '<h3>'.$resultadoDuplicacion.'</h3>';
		} 
	?>
	<?php
		if(isset($error))
		{
			echo '<h3>Pero estas encuestas no se pudieron crear porque ya existen</h3>';
			echo '<div class="error">';
			foreach ($error as $item)
			{
				echo '<span>'.$item.'</span>';	
			}
			echo '</div>';
			
		} 
	?>
	
	
	<form method="post" id="tabs" action="<?php echo $_SERVER['REQUEST_URI'];?>">
		<div id="divMateriasContainer" style="width: 450px;float: left; margin: 0 20px;">
			<span>Materia</span>
			<?php 
				if (!mssql_num_rows($materias)) {
				    echo 'No records found';exit;
				}
				else
				{
					echo '<select multiple="multiple" name="materiasCursadas" id="materiasCursadas" size="20">';
					$opt = '';
					
				    while ($row = mssql_fetch_assoc($materias)) {
				    	//var_dump($row);exit;
				    	if($opt != $row['nom_materia'])
				    	{
				    		if($opt != '')
				    		{
				    			echo '</optgroup>';
				    		}
				    		echo '<optgroup label="'.mb_convert_encoding($row['nom_materia'], 'UTF-8','ASCII').'">';
				    		$opt = $row['nom_materia'];
				    	}
				        echo '<option value="'.$row['periodo_curso'].'|'.$row['nro_materia'].'|' .$row['nro_curso_id']. '">'.
				        		mb_convert_encoding($row['turno_curso'],'UTF-8','ASCII') .'|' .$row['division_curso']. 
				        	'</option>';
				    }
				    echo '</select>';
				}
				mssql_free_result($materias);
			
			?>
			<input type="hidden" name="materiasSeleccionadas" id="materiasSeleccionadas" />	
		</div>
		<div id="divSurveyContainer" style="width: 400px;float: left;">
			<span>Encuesta</span>
			<?php 
				echo '<select name="encuestas" id="encuestas" size="20">';
				$opt = '';
				while(list($sid,$name,$title,$owner,$realm) = fetch_row($result)) {
       				$result->MoveNext();
			    	echo '<option value="'.$sid. '">'.
			        		$name . 
			        	'</option>';
			    }
			    echo '</select>';

			
			?>		
		</div>
		<div id="divSurveyGeneral">
			<fieldset id="flsLegajo">
				<label>
					Encuesta para todos los Alumnos
				</label>
				<input type="checkbox" name="encuestaGeneral" id="encuestaGeneral" value="1">
				<br />
				Periodo 1<input type="radio" name="periodoGeneral" value="01">
				Periodo 2<input type="radio" name="periodoGeneral" value="02">
				
			</fieldset>
		</div>
		
		
		
		
		
		
		<input type="submit" onclick="return validateWizard()" value="Crear Encuestas">
	</form>
	<br clear="all" />
	<br />
	<br />
	<br />
	
</div>
<?php echo("<a href=\"". $GLOBALS['ESPCONFIG']['ME'] ."?where=manage\">" . _('Go back to Management Interface') . "</a>\n"); ?>