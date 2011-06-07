<?php
$host="localhost"; // Host name
$username="root"; // Mysql username
$password="root"; // Mysql password
$db_name="phpesp"; // Database name
$conn = mysql_connect("$host", "$username", "$password")or die("No se puede conectar");
mysql_select_db("$db_name")or die("Imposible Conectar a la base de datos");

$legajo=$_POST['userid'];
//--------------------------------------------CONSULTA SQL-----------------------
$sql_old="SELECT 
			m.nom_materia,
			i.nro_leg_alumno, 
			i.division_curso
			 
		FROM 
			inscripciones_cursar i, materias m 
		WHERE 
			i.cod_materia=m.cod_materia 
			AND i.cod_carrera=m.cod_carrera 
			AND i.cod_titulo=m.cod_titulo 
			AND i.cod_plan_estudio=m.cod_plan_estudio 
			AND i.nro_leg_alumno='$legajo'";

$sql = "
		SELECT 
				ma.nom_materia, 
				ic.nro_leg_alumno, 
				c.division_curso,
				c.turno_curso
		FROM inscripciones_cursar ic
		
		JOIN programas_academicos pa ON ic.nro_prog_acad = pa.nro_prog_acad
		JOIN materias ma ON ic.nro_materia = ma.nro_materia
		JOIN legajos_alumnos la ON ic.nro_leg_alumno = la.nro_leg_alumno
		JOIN identificacion_personas ip ON la.nro_persona = ip.nro_persona
		JOIN cursos c ON ic.nro_curso_id = c.nro_curso_id and ic.nro_materia = c.nro_materia and ic.periodo_curso = c.periodo_curso
		
		WHERE ic.periodo_curso = '201001' AND ic.nro_leg_alumno = " . $legajo ."
		ORDER BY ma.nom_materia"; 

$sql = "
		
SELECT 
				ma.nom_materia, 
				ic.nro_leg_alumno, 
				c.division_curso,
				c.turno_curso
				
		FROM inscripciones_cursar ic
		
		JOIN programas_academicos pa ON ic.nro_prog_acad = pa.nro_prog_acad
		JOIN materias ma ON ic.nro_materia = ma.nro_materia
		JOIN legajos_alumnos la ON ic.nro_leg_alumno = la.nro_leg_alumno
		JOIN identificacion_personas ip ON la.nro_persona = ip.nro_persona
		JOIN cursos c ON ic.nro_curso_id = c.nro_curso_id and ic.nro_materia = c.nro_materia and ic.periodo_curso = c.periodo_curso
		JOIN planes_estudios pe ON ic.nro_prog_acad = pe.nro_prog_acad AND ic.nro_materia = pe.nro_materia
		WHERE (ic.periodo_curso = '201002' or (ic.periodo_curso = '201001' AND pe.sem_materia is null)) AND ic.nro_leg_alumno = " . $legajo ." 
order by ma.nom_materia
		";


$result = mysql_query($sql);
//--------------------------------------------------------------------------------

?>






<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Colegio Universitario de Periodismo - Encuesta Academica <?php echo date('Y')?></title>
		<style type="text/css">
			<!--
			body {
				background-color: #AAAAAA;
			}
			-->
		</style>
	</head>
	
	<body>
		<?php
			if(!$result)
			{?>
				<h2>no se a encontrado el alumno</h2>
			
		<?php exit; }?>
		
			<form action='1embed.php' method='POST' target='_blank'>
			
				<table 
					align='CENTER' 
					width='50%' 
					height='5%' 
					bgcolor='#AACCBB' 
					border='5' 
					bordercolor='#93DB70'>
					<?php 
					while ($row = mysql_fetch_row($result))
					{?>
					<tr>
						<td>
							<input 
								type='radio' 
								name='radData' 
								value='<?php echo $row[0] . '|'. $row[2] . '|' . $row[3]   ;?>'
							/>
			  				<?php echo $row[0]; ?>
						</td>
						<td>
			  				
			  				<?php echo $row[2]; ?>
						</td>
						<td>
			  				<?php echo $row[3]; ?>
						</td>
					</tr>
	  			<?php
				}
				?>
				<tr>
						<td>
							<input 
								type="radio" 
								name="radData" 
								value="ENCUESTA DE SATISFACCION|U|U"
							/>
			  				ENCUESTA DE SATISFACCION (No llenar si haces solo Especializaci&oacute;n)
						</td>
						<td>
			  				TODOS
						</td>
						<td>
			  				TODOS
						</td>
					</tr>
			</table>
		<input 
			type='hidden' 
			value='<?php echo $legajo; ?>' 
			name='userid'
		/>
		<p 
			ALIGN='CENTER'>
			<input 
				type='submit' 
				value='Seleccionar' 
			/>
		</p>
	</form>
		
	</body>
</html>
