<?php 
//echo 'mierda';exit;
include('mssqlconection.php');

function getMaterias()
{
	$sql="
			SELECT
			        c.nro_materia,
					ma.nom_materia,
					c.periodo_curso,
					c.nro_curso_id,        
					c.division_curso,
			        c.turno_curso
					
			        
			FROM inscripciones_cursar ic
			
			JOIN programas_academicos pa ON ic.nro_prog_acad = pa.nro_prog_acad
			JOIN materias ma ON ic.nro_materia = ma.nro_materia
			JOIN cursos c ON ic.nro_curso_id = c.nro_curso_id
			            and ic.nro_materia = c.nro_materia
			            and ic.periodo_curso = c.periodo_curso
			        
			where ic.periodo_curso = '" .date('Y'). "01' or ic.periodo_curso = '".date('Y')."201102'
			
			group by ma.nom_materia, c.division_curso, c.turno_curso, c.nro_materia,ma.nom_materia,c.periodo_curso,c.nro_curso_id
			order by ma.nom_materia ASC";
			
	return mssql_query($sql);
}


function getDatosEncuesta($periodoID,$materiaID,$cursoID)
{
	$sql="select ch.nro_leg_docente, 
	(ip.apellido + ' ' + ip.nombres) as subtitle, 
	ma.nom_materia as title,
	(LEFT(ch.periodo_curso,4) + '_' + right(ch.periodo_curso,1) + '_' +REPLACE(ma.nom_materia,' ','_') + '_' + cu.division_curso ) as name
from curso_horario ch
join legajos_docentes ld on ld.nro_leg_docente = ch.nro_leg_docente
join identificacion_personas ip on ip.nro_persona = ld.nro_persona
join materias ma on ma.nro_materia = ch.nro_materia
join cursos cu on cu.periodo_curso = ch.periodo_curso and cu.nro_materia = ch.nro_materia and cu.nro_curso_id = ch.nro_curso_id
where ch.periodo_curso = '". $periodoID."' and
		ch.nro_materia = ".$materiaID." and
		ch.nro_curso_id = ".$cursoID."
group by ch.nro_leg_docente, ip.apellido, ip.nombres, ma.nom_materia, ch.periodo_curso,cu.division_curso";
	
	$result = mssql_query($sql);
	$resultado = array();
	while ($row = mssql_fetch_assoc($result)) {
		if(count($resultado) > 0)
		{
			$resultado[0]['subtitle'] .= ','. $row['subtitle'];
		}
		else
		{
			$resultado[] = $row;
		}
		
	}
	return $resultado[0];
	
}

function getMateriasAlumno($legajo)
{
	$sql = "		
			SELECT 
							c.periodo_curso,
							c.nro_materia, 
							c.nro_curso_id,
							
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
					WHERE (ic.periodo_curso = '".date('Y')."02' or (ic.periodo_curso = '".date('Y')."01' AND pe.sem_materia is null)) AND ic.nro_leg_alumno = " . $legajo ." 
					order by ma.nom_materia
		";
			
	return mssql_query($sql);
}


?>