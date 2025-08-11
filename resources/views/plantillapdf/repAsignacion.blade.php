

<html>
<head>
	<style>
	@page {
		margin: 0cm 0cm;
	}
	body{
		font-family: 'arial', sans-serif;
		margin-top: 5cm;
		margin-left: 1cm;
		margin-right: 1cm;
		margin-bottom: 1cm;
		width: 24.5cm;
	}
	hr{
		margin: 0;
		border-top: 1px solid rgb(0, 0, 0);
	}
	#customers {
		border-collapse: collapse;
		font-size: 7pt;
	}
	#customers td, #customers th {
		padding: 5px;
		border: 1px solid #ddd;
	}
	#customers thead { display: table-header-group; }
	.eslogan{
		font-size: xx-small;
		font-style: italic;
	}
	#customers th {
		text-align: center;
		background-color: #ddd;
		color: black;
		border: 1px solid #000;
	}
	#customers tfoot th {
		text-align: right;
		background-color: #ddd;
		color: black;
		border: 1px solid #000;
	}
	#customers thead { display: table-header-group; }
	.eslogan{
		font-size: xx-small;
		font-style: italic;
	}
	#cabecera tr td{
		margin:0;
		padding:0;
		font-size: 6pt;
	}
	header {
		position: fixed;
		top: 1cm;
		left: 1cm;
		right: 1cm;
		height: 3cm;
		}
	footer {
		position: relative;
		left: 1cm;
		right: 0cm;
	}
	.page-break {
	    page-break-after: always;
	}
</style>
</head>
<body>
	<header>
	<div style="width: 26cm; height: 3.7cm;">
		<table>
			<tr>
				<td>
					<div style="width: 6cm; height: 3.7cm; text-align: center;">
						<img src="{{ asset('img/sistema.png') }}" alt="" style="width: auto; height: 2cm; ">
								<p style="margin: 0.1cm; text-align: center; font-size: x-small; padding: 0;" ><b>POLICIA BOLIVIANA</b></p>
								<p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;">COMANDO GENERAL</p>
								<p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;"><b>DIRECCIÓN NACIONAL DE SALUD</b></p>
								<p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;"><b>Y BIENESTAR SOCIAL</b></p>
								<p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;">LA PAZ - BOLIVIA</p>
					</div>
				</td>
				<td>
					<div style="width: 13.5cm; height: 2cm;">
					<p style="margin-top: 0.5cm; text-align: center;" ><b>ACTA DE ASIGNACIÓN DE BIENES</b></p>
					<p style="font-size: xx-small; margin: 0; text-align: center;">AL: {{$fechaTitulo}}</p>
				</div>
				</td>
				<td>
					<div style="width: 4cm; height: 1cm; ">
						<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>Página:</b></p>
						<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>Fecha de Impresión:</b></p>
						
					</div>
				</td>
				<td>
					<div style="width: 2cm; height: 1cm;">
						<p style="font-size: xx-small; margin: 0.1cm;"><br></p>
						<p style="font-size: xx-small; margin: 0.1cm;">{{$fechaDerecha}}</p>
						
					</div>
				</td>
			</tr>
		</table>
	</div>
	<hr>
</header>
	<main>
		<table id="cabecera">
		<tr style=" height: .5cm;">
			<td >
				<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>ENTIDAD:</b></p>
			</td>
			<td style=" height: .5cm; width:9cm;">
				<p style="font-size: xx-small; margin: 0.1cm;">DIRECCIÓN NACIONAL DE SALUD Y BIENESTAR SOCIAL</p>
			</td>
			<td style=" height: .5cm; width:9cm;">
			<p style="font-size: xx-small;  margin: 0.1cm;"><b>UNIDAD:</b> {{$unidad.' - '.$desuni->descrip}}</p>
			</td>
		</tr>
		<tr style=" height: .5cm;">
			<td >
			<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>RESPONSABLE:</b></p>
			</td>
			<td >
			<p style="font-size: xx-small; margin: 0.1cm;">{{$responsable->nomresp}}</p>
			</td>
			<td><div style="width: 3cm;  height: .5cm; text-align: right; font-size: xx-small; margin: 0.1cm;">C.I.</div></td>
			<td><div style="width: 2cm;  height: .5cm; font-size: xx-small; margin: 0.1cm; ">{{$responsable->ci}}</div> </td>
			<td><div style="width: 6cm;  height: .5cm; font-size: xx-small; margin: 0.1cm; ">{{$responsable->sigla}}</div></td>
		</tr>
		<tr style=" height: .5cm;" >
			<td>
			<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>CARGO:</b></p>
			</td>
			<td>
			
			<p style="font-size: xx-small; margin: 0.1cm;">{{$responsable->cargo}}</p>
			</td>
		</tr>
		<tr style=" height: .5cm;">
			<td>
			<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>OFICINA:</b></p>
			</td>
			<td>
			<p style="font-size: xx-small; margin: 0.1cm;">{{$responsable->codofic.' - '.$responsable->nomofic}}</p>
			</td>
		</tr>
	</table>
	<table id="customers">
		<thead>
			<tr style="border: 1px solid;">
				<th style="width: 3.3cm;">CÓDIGO</th>
				<th style="width: 5.5cm;">AUXILIAR</th>
				<th style="width: 13cm;">DESCRIPCIÓN DE ACTIVO</th>
				<th style="width: 2.7cm;">ESTADO</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($datos as $dato)
				<tr>
				<td>{{$dato->codigo}}</td>
				<td>{{$dato->codaux.' - '.$dato->nomaux}}</td>
				<td>{{$dato->descripcion}}</td>
				<td>{{$dato->nomestado}}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4"><b>Cantidad: </b>{{$total}}</p></th>
			</tr>
		</tfoot>
		
	</table>
			<p style="font-size: x-small;"><b>Cantidad: </b>{{$total}}</p>
			<p class="eslogan">El servidor público queda prohibido de usar o permitir el uso de los bienes para beneficio particular o privado, prestar o transferir el bien a otro empleado público, enajenar el bien por cuenta propia,
				dañar o alterar sus características físicas o técnicas, poner en riesgo el bien, ingresar o sacar bienes particulares sin autorización de la Unidad o Responsable de Activos Fijos.
				La no observancia a estas prohibiciones generará responsabilidades establecidas en la Ley Nº 1178 y sus reglamentos </p>
			<p class="eslogan">En señal de conformidad y aceptación se firma el presente acta.</p>
	</main>
	<footer>
		<table>
			<tr>
				<td>
					<div style=" text-align: center; width: 8.66cm;">
						<p style="margin: 0.1cm; text-align: center;">__________________________</p>
						<p style="font-size: x-small; margin: 0.1cm;">Autorizacion de Asignación</p>
					</div>
				</td>
				<td>
					<div style=" text-align: center; width: 8.66cm;">
						<p style="margin: 0.1cm; text-align: center;">_____________________________</p>
						<p style="font-size: x-small; margin: 0.1cm;">Responsable de Activos Fijos</p>
					</div>
				</td>
				<td>
					<div style=" text-align: center; width: 8.66cm;">
						<p style="margin: 0.1cm; text-align: center;">_____________________</p>
						<p style="font-size: x-small; margin: 0.1cm;">Funcionario</p>
					</div>
				</td>
			</tr>
		</table>
	</footer>
	<script type="text/php">
		if ( isset($pdf) ) {
			$pdf->page_script('
				$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "NORMAL");
				$pdf->text(705, 52, "$PAGE_NUM de $PAGE_COUNT", $font, 7);
			');
		}
	</script>
</body>
</html>