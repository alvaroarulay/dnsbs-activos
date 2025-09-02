<template>
    <main class="app-content">
      <div class="app-title row">
        <div class="col-md-8">
        <h1><i class="bi bi-collection"></i> Unidad Administrativa: </h1> 
        </div>
        <div class="col-md-4">
            <select class="form-select" @change="onChangeUnidad($event)" v-model="idunidad">
                <option :value=0>Seleccione...</option>
                <option v-for="unidad in arrayUnidad" :value="unidad.id" v-text="unidad.descrip"></option>
            </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow-lg border-secondary rounded-lg mb-3">
                <div class="card-header bg-secondary text-white "> Oficina</div>
                <div class="card-body">
                    <div class="row">
                    <div class="mb-3 col-md-6">
                        <div class="mb-3 input-group" >
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <label> Código:</label>
                                </span>
                            </div>
                            <input type="text" class="form-control"  placeholder="Ingrese Código de Oficina" v-model="codofic" @keyup.enter="buscarOficina()" >
                        </div>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <button class="btn btn-info form-control btnagregar" type="button" @click="buscarOficina()"><i class="bi bi-plus-lg" ></i> Agregar</button>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <button class="btn btn-success form-control btnagregar" type="button" @click="abrirModal()"><i class="bi bi-search"></i> Buscar</button>
                    </div>
                    </div>
                     <label><strong>Nombre Oficina:  </strong>{{ nomofic }}</label><br>
                     <label><strong>Estado:  </strong>
                        <span v-if="estado==1" class="badge bg-success">Activo</span>
                        <span v-if="estado==0" class="badge bg-danger">Inactivo</span>
                    </label><br>
                    <label><strong>Observaciones:  </strong>{{ observacion }}</label><br>
                </div> 
                <div class="card-footer text-end">
                    <button class="btn btn-primary mb-3" type="button" @click="abrirModalofic('oficina','registrar')"><i class="bi bi-plus-square"></i> Nuevo</button>
                    <button class="btn btn-warning mb-3" type="button" @click="abrirModalofic('oficina','actualizar')"><i class="bi bi-pencil-square"></i>Modificar</button>
                    <button class="btn btn-info mb-3" type="button" v-if="this.estado==0" @click="ActivarOficina(this.id_oficina)"><i class="bi bi-check2-square"></i>Activar</button>
                    <button class="btn btn-danger mb-3" type="button" v-if="this.estado==1" @click="InactivarOficina(this.id_oficina)"><i class="bi bi-x-square"></i>Inactivar</button>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="card shadow-lg border-info rounded-lg">
                <div class="card-header bg-info">
                    <div class="row">
                        <div class="col-md-10 col-6"><strong>Responsables: </strong></div>
                    
                    </div>
                </div>
                <div class="card-body">
                    
                    <template v-if="listado==1">
                        <div class="row mb-3">
                        <div class="col-md-8 mb-3">
                            <div class="input-group mb-3">
                                <select class="form-select md-3" v-model="criterio2">
                                    <option value="ci">Carnet</option>
                                    <option value="nomresp" selected>Nombre Responsable</option>
                                </select>
                                <input type="text" v-model="buscar2" @keyup.enter="listarPersona(codofic,criterio2, buscar2)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPersona(codofic, criterio2, buscar2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 text-end">
                                <button type="button" @click="mostrarNuevo()" v-if="accion==1" class="btn btn-primary"><i class="bi bi-plus-square"></i>&nbsp;Nuevo</button>
                                <button type="button" @click="ayuda()" class="btn btn-info"><i class="bi bi-question-square"></i>&nbsp;Ayuda</button>
                        </div>
                    </div>
                        <div class="tile-body table-responsive table-fixed">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Activos</th>
                                        <th>Nombre</th>
                                        <th>Carnet</th>
                                        <th>Expedido</th>
                                        <th>Cargo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="persona in arrayPersona" :key="persona.id">
                                        <td>
                                            <button type="button" @click="abrirlistadoActivos(persona)" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Activos">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-suitcase-lg-fill" viewBox="0 0 16 16">
                                                    <path d="M7 0a2 2 0 0 0-2 2H1.5A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14H2a.5.5 0 0 0 1 0h10a.5.5 0 0 0 1 0h.5a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2H11a2 2 0 0 0-2-2zM6 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1zM3 13V3h1v10zm9 0V3h1v10z"/>
                                                </svg>
                                            </button>
                                            <button type="button" @click="editpersona(persona)" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Activos">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                        <td v-text="persona.nomresp"></td>
                                        <td v-text="persona.ci"></td>
                                        <td v-text="persona.sigla"></td>
                                        <td v-text="persona.cargo"></td>
                                        <td>
                                            <div v-if="persona.estado==1">
                                                <span class="me-1 badge badge-pill bg-success">Activo</span>
                                            </div>
                                            <div v-else-if="persona.estado==3">
                                                <span class="me-1 badge badge-pill bg-danger">Inactivo</span>
                                            </div>
                                            
                                        </td>
                                    </tr>                      
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>
                                            Cantidad:
                                        </th>
                                        <th colspan="5">{{ total }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </template>
                    <!-- listado 2-->
                    <template v-else-if="listado==2">
                        <div class="tile-body">

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre de Responsable:</label>
                                    <input class="form-control" type="text" v-model="nomresp">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Carnet de Identidad:</label>
                                    <input class="form-control" type="text" v-model="ci">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Expedido en:</label>
                                    <div class="input-group">
                                        <select v-model="expedido" class="form-select">
                                        <option :value= 1>CHUQUISACA</option>
                                        <option :value= 2>LA PAZ</option>
                                        <option :value= 3>COCHABAMBA</option>
                                        <option :value= 4>ORURO</option>
                                        <option :value= 5>POTOSI</option>
                                        <option :value= 6>TARIJA</option>
                                        <option :value= 7>SANTA CRUZ</option>
                                        <option :value= 8>BENI</option>
                                        <option :value= 9>PANDO</option>
                                        <option :value= 10>OTROS</option>
                                        </select>   
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Cargo:</label>
                                    <input class="form-control" type="text" v-model="cargo">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Observaciones:</label>
                                    <textarea class="form-control" rows="3" v-model="observ"></textarea> 
                                </div>
                                    <div class="col-md-3">
                                    <label class="form-label">Estado:</label>
                                    <div class="input-group">
                                        <select v-model="estadoresp" class="form-select">
                                            <option :value=3>INACTIVO</option>
                                            <option :value=1>ACTIVO</option>
                                        </select>   
                                    </div>
                                </div>
                            </div>
                            <div v-show="errorResponsable" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjResponsable" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="tile-footer">
                            <div class="form-group row">
                                <div class="col-md-2 mb-3">
                                    <p class="bs-component d-grid">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-danger btn-block"><i class="bi bi-x-square"></i>Cerrar</button>
                                    </p>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <p class="bs-component d-grid">
                                    <button type="button" class="btn btn-success btn-block" v-if="accionresp==1" @click="registrarResponsable()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z"/>
                                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                </svg>Guardar</button>
                                <button type="button" class="btn btn-warning btn-block" v-if="accionresp==2" @click="actualizarResponsable()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z"/>
                                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                </svg>Actualizar</button>
                                </p>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else-if="listado==3">
                <h3 class="tile-title">Activos Fijos del Responsable: {{ nombre_responsable }}</h3>
                <div class="row mb-3">
                        <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-select col-md-3" v-model="criterio1">
                                <option value="codigo">Código</option>
                                <option value="descripcion">Descripcion</option>
                            </select>
                            <input type="text" v-model="buscar1" @keyup.enter="listarActivos(codofic, codresp, buscar1, criterio1)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarActivos(codofic, codresp, buscar1, criterio1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="tile-body table-responsive table-fixed">
                    <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayActivos.length">
                                <tr v-for="activo in arrayActivos" :key="activo.id">
                                    <td>{{ activo.codigo }}</td>
                                    <td>{{ activo.descripcion }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                    <tr>
                                        <td colspan="3">
                                            NO hay Activos
                                        </td>
                                    </tr>
                                </tbody>
                            <tfoot>
                                <tr>
                                <th>Cantidad</th>
                                    <th>{{ totalActivos }}</th> 
                                </tr>
                            </tfoot>
                            </table>
                            </div>
                            <div class="tile-footer">
                                <button class="btn btn-danger" @click="cerrarlistado()">Cerrar</button>
                            </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
<!-- Fin Listado -->
<!----inicio modal-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar Oficinas</h4>
                <button type="button" class="close btn btn-sm btn-danger" @click="cerrarModal()" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <select class="form-select col-md-3" v-model="criterio">
                                <option value ="codofic">Codigo</option>
                                <option value ="nomofic">Nombre Oficina</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarOficina(1,buscar,criterio)" class="form-control" placeholder="Ingrese un Codigo">
                            <button type="submit" @click="listarOficina(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Código</th>
                                <th>Oficina</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="oficina in arrayOficinas" :key="oficina.id">
                                <td>
                                    <button type="button" @click="agregarDetalleModal(oficina)" class="btn btn-success btn-sm">
                                    <i class="fa fa-check"></i>
                                    </button>
                                </td>
                                <td v-text="oficina.codofic"></td>
                                <td v-text="oficina.nomofic"></td>
                                <td>
                                <div v-if="oficina.api_estado==1">
                                    <span class="me-1 badge badge-pill bg-success">Activo</span>
                                </div>
                                <div v-else-if="oficina.api_estado==3">
                                    <span class="me-1 badge badge-pill bg-danger">Inactivo</span>
                                </div>
                                
                            </td>
                            </tr>                                
                        </tbody>
                    </table>
                    <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modalofic}" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close btn btn-danger btn-sm" @click="cerrarModal()" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body" v-if="newform==1">
                        <div class="form-group row mb-3">
                            <label class="col-md-4" for="text-input">Nombre Unidad Administrativa</label>
                            <div class="col-md-8">
                                <input type="text" v-model="descripunidad" class="form-control">                                        
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4" for="text-input">Sigla Unidad</label>
                            <div class="col-md-8">
                                <input type="text" v-model="siglaunidad" class="form-control">                                       
                            </div>
                        </div>
                        <div v-show="errorOficina" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjOficina" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-body" v-else-if="newform==2">
                        <div class="form-group row mb-3">
                            <label class="col-md-4" for="text-input">Nombre Establecimiento</label>
                            <div class="col-md-8">
                                <input type="text" v-model="descripestab" class="form-control">                                        
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4" for="text-input">letra de Indentificación</label>
                            <div class="col-md-8">
                                <input type="text" v-model="siglaestab" class="form-control">                                       
                            </div>
                        </div>
                        <div v-show="errorOficina" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjOficina" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-body" v-else-if="newform==3">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row mb-3">
                            <label class="col-md-2" for="text-input">Nombre Oficina</label>
                            <div class="col-md-10">
                                <input type="text" v-model="nomofic" class="form-control">                                        
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                            <div class="form-group row mb-3">
                                <label class="col-md-4" for="text-input">Observaciones</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="3" v-model="observaciones"></textarea>                                      
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group row mb-3">
                            <label class="col-md-3" for="text-input" >Estado:</label>
                            <div class="col-md-9">
                                <select class="form-select" v-model="estado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>                                    
                            </div>
                            </div>
                            </div>
                        </div>
                        <div v-show="errorOficina" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjOficina" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer" v-if="newform==1">
                    <button type="button" class="btn btn-danger" @click="cerrarModal()"><i class="bi bi-x-square"></i>Cerrar</button>
                    <button type="button" class="btn btn-warning" v-if="tipoAccion==2" @click="actualizarUnidad()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Actualizar
                    </button>
                    <button type="button" class="btn btn-success" v-if="tipoAccion==1" @click="registrarunidades()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Guardar
                    </button>
                </div>
                <div class="modal-footer" v-else-if="newform==2">
                    <button type="button" class="btn btn-danger" @click="cerrarModal()"><i class="bi bi-x-square"></i>Cerrar</button>
                    <button type="button" class="btn btn-warning" v-if="tipoAccion==2" @click="actualizarestab()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Actualizar
                    </button>
                    <button type="button" class="btn btn-success" v-if="tipoAccion==1" @click="registrarestab()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Guardar
                    </button>
                </div>
                <div class="modal-footer" v-else-if="newform==3">
                    <button type="button" class="btn btn-danger" @click="cerrarModal()"><i class="bi bi-x-square"></i>Cerrar</button>
                    <button type="button" class="btn btn-warning" v-if="tipoAccion==2" @click="actualizarOficina()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Actualizar
                    </button>
                    <button type="button" class="btn btn-success" v-else-if="tipoAccion==1" @click="registrarOficina()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Guardar
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>
</main>
</template>
<script>
import axios from 'axios';
export default {
  data() {
    return {
        tituloModal:'',
        observaciones:'',
        tipoAccion:0,
        id_oficina:0,
        accionresp:1,
        idresp:0,
        ciudad:0,
        arrayCiudad:[],
        edif:0,
        arrayEdif:[],
        newform:0,
        descripestab:'',
        siglaestab:'',

        listado:1,
        accion:1,
        codofic:0,
        estado:0,
        nomofic:'',
        observacion:'',

        idunidad:0,
        descripunidad:'',
        siglaunidad:'',
        unidad:'',
        titulo:'',
        idrol:0,

        nombre_responsable:'',
        codresp:0,

        nomresp:'',
        cargo:'',
        observ:'',
        ci:'',
        total:0,
        expedido: 1,
        estadoresp : 0,

        arrayOficina:[],
        arrayOficinas:[],
        arrayPersona:[],
        arrayUnidad:[],
        arrayActivos:[],
        totalActivos:0,
        errorResponsable:0,
        errorMostrarMsjResponsable:[],
        errorOficina:0,
        errorMostrarMsjOficina:[],
        modal : 0,
        modalofic : 0,
        criterio:'codofic',
        buscar: '',
        criterio1:'codigo',
        buscar1: '',
        criterio2:'ci',
        buscar2: '',
        pagination : {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0,
      },
      offset : 3,
    }

  },
  computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
  methods: {
    listarUnidad (){
            let me=this;
            var url= '/unidad';
            axios.get(url).then( (response) =>{
                if(response.data){
                    var respuesta= response.data;
                    me.arrayUnidad = respuesta.unidad;
                }
        })
        .catch( (error)=> {
            console.log(error);
        });
    },
    onChangeUnidad(e){
        this.idunidad=e.target.value;
        this.limpiaroficina();
    },
    buscarOficina(){
        let me=this;
        if(me.idunidad==0){
            Swal.fire('Seleccione una Unidad Administrativa','','warning');
            return;
        }
        var url= '/oficinas/buscarOficina?filtro=' + me.codofic + '&idunidad=' + me.idunidad;
        axios.get(url).then((response)=>{
            me.arrayOficina = response.data.oficina;
            me.codofic=me.arrayOficina.codofic;
            me.nomofic=me.arrayOficina.nomofic;
            me.observacion = me.arrayOficina.observ;
            me.estado = me.arrayOficina.api_estado;
            me.id_oficina = me.arrayOficina.id;
            me.listarPersona(me.codofic,'','');
        })
        .catch(function (error) {
            console.log(error);
            Swal.fire('No se Encontro Oficina','','error')
        });
    },
    limpiaroficina(){
        this.codofic=0;
        this.nomofic='';
        this.observ='';
        this.total=0;
        this.estado=0;
        this.arrayOficina=[];
        this.arrayOficinas=[];
        this.arrayPersona=[];
    },
    listarOficina(page, buscar,criterio){
        let me=this;
        var url= '/oficinas/?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&idunidad=' + me.idunidad;
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.arrayOficinas = respuesta.oficinas.data;
            me.pagination = respuesta.pagination;
        })
        .catch( (error) =>{
            console.log(error);
        });
    },
    listarPersona (codofic,criterio2,buscar2){
        let me=this;
        var url= '/responsable/listar?codofic='+ codofic + '&idunidad=' + me.idunidad + '&criterio=' + criterio2 + '&buscar=' + buscar2;
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.arrayPersona = respuesta.responsables;
            me.total = respuesta.total;
        })
        .catch( (error)=> {
            console.log(error);
        });
    },
    listarActivos(codofic,codresp,buscar1,criterio1){
        let me = this;
        const res = this.arrayUnidad.find((unidad) => unidad.id == this.idunidad);
        this.siglaunidad = res.unidad;
        var url = '/actuales/responsable?codofic=' + codofic + '&codresp=' + codresp + '&buscar=' + buscar1 + '&criterio=' + criterio1 + '&unidad=' + me.siglaunidad;
        axios.get(url).then( (response) =>{
            console.log(response.data);
            var respuesta= response.data;
            me.arrayActivos = respuesta.actuales;
            me.totalActivos = respuesta.totalactuales;
        })
        .catch( (error)=> {
            console.log(error);
        });
    },
    cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarOficina(page,buscar,criterio);
    },
    cerrarModal(){
        this.modal=0;
        this.modalofic=0;
        this.arrayOficina=[];        
        this.listado = 1;
    }, 
    abrirModal(){  
         if(this.idunidad==0){
            Swal.fire('Seleccione una Unidad Administrativa','','warning');
            return;
        }
        this.modal = 1;
        this.listarOficina(1,this.buscar,this.criterio);
    },
    agregarDetalleModal(data){
        this.id_oficina=data.id;
        this.codofic=data.codofic;
        this.nomofic=data.nomofic;
        this.estado=data.api_estado;
        this.observacion=data.observ;
        this.listarPersona(data.codofic,'','');
        this.cerrarModal();
    },
    registrarResponsable(){
      let me = this;
      if (me.validarResponsable()){ return;}
      axios.post('/responsable/registrar',{
            'codofic': me.codofic,
            'nomresp': me.nomresp,
            'cargo' : me.cargo,
            'ci' : me.ci,
            'expedido' : me.expedido,
            'observ' : me.observ,
            'estado' :me.estadoresp,
            'idunidad' : me.idunidad,
        }).then((response)=>{
          Swal.fire(response.data.message, "", "success");
          me.siglaunidad=response.data.idunidad;
          me.codofic=response.data.codofic;
          me.listarPersona(me.codofic,'','');
          me.listado=1;
        }).catch((error)=>{
            console.log(error);
        });
    },
    actualizarResponsable(){
          let me = this;

          axios.put('/responsable/actualizar',{
            'id':me.idresp,
            'codresp':me.codresp,
            'codofic':me.codofic,
            'nomresp': me.nomresp,
            'cargo':me.cargo,
            'ci':me.ci,
            'observ':me.observ,
            'cod_exp':me.expedido,
            'estado':me.estadoresp,
          }).then(function (response) {
                Swal.fire(response.data.message,"","info");
                me.codofic=0;
                me.nomofic='';
                me.estado=0;
                me.observ='';
                me.arrayPersona=[];
                me.listado=1;
                me.nomresp='';
                me.cargo='';
                me.ci='';
                me.expedido= 2;
                me.observ='';
          }).catch(function (error) {
            Swal.error('Hubo un Error');
              console.log(error);
          }); 
    }, 
    validarResponsable(){
        let me = this;
        this.errorResponsable=0;
        this.errorMostrarMsjResponsable =[];
        let validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/;
        let validnumber = /^([0-9])*$/;
        if (me.nomresp == null || me.nomresp.length == 0 || !validText.test(me.nomresp)) me.errorMostrarMsjResponsable.push("Nombre de responsable incorrecto");
        if (me.cargo == null || me.cargo.length == 0 || !validText.test(me.cargo)) me.errorMostrarMsjResponsable.push("Cargo incorrecto");
        if (me.ci == null || me.ci.length == 0 || !validnumber.test(me.ci)) me.errorMostrarMsjResponsable.push("Carnet de Identidad incorrecto");
      
        if (this.codofic==0){ this.errorMostrarMsjResponsable.push("Seleccione una Oficina.");};
        if (this.expedido==0){ this.errorMostrarMsjResponsable.push("Seleccione un Departamento.");};
        if (this.errorMostrarMsjResponsable.length) this.errorResponsable = 1;

        return this.errorResponsable;
    },
    mostrarNuevo(){
        let me=this;
        if (me.codofic==0 || me.estado==0)
        {
            Swal.fire('Seleccione una Oficina u Oficina Inactiva','','error');
            me.listado=1;
            me.accionresp=1;
        }
        else{
            me.listado=2;
            me.accionresp=1;
        }
    },
    editpersona(data=[]){
        this.listado=2;
        this.accionresp=2;
        this.idresp=data['id'];
        this.nomresp=data['nomresp'];
        this.ci=data['ci'];
        this.cargo=data['cargo'];
        this.observ=data['observ'];
        this.expedido=data['cod_exp'];
        this.estadoresp=data['api_estado'];
    },
    ocultarDetalle(){
        this.listado=1;
        this.accion=1;
        this.nomresp='';
        this.cargo='';
        this.observ='';
        this.ci=0;
        this.expedido=2;
        this.estadoresp=1;
        this.errorMostrarMsjResponsable=[];
        this.errorResponsable=[];
    },
    abrirlistadoActivos(data){
        this.listado=3;
        this.nombre_responsable = data['nomresp'];
        this.codresp = data['codresp'];
        this.listarActivos(data['codofic'],data['codresp'],'','');
    },
    cerrarlistado(){
        this.listado=1;
    },
    abrirModalofic(modelo, accion){
        switch(modelo){
            case "oficina":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.modalofic = 1;
                        this.newform=3;
                        this.tituloModal = 'Registrar Oficina';
                        this.id_oficina=0;
                        this.nomofic='';
                        this.observaciones='';
                        this.estado=0;
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        if(this.id_oficina==0){Swal.fire("Seleccione una Oficina." , "", "error"); this.modal=0; break}
                        this.modalofic=1;
                        this.newform=3;
                        this.tituloModal = 'Actualizar Oficina';
                        this.id=this.id_oficina;
                        this.nomofic=this.nomofic;
                        this.observaciones=this.observ;
                        this.tipoAccion=2;
                        break;
                    }
                }
            }
        }
     
    },
    abrirModalUnidad(accion){
        switch(accion){
            case 'registrar':
            {
                if(this.ciudad==0){Swal.fire("Seleccione una Ciudad." , "", "error"); this.modal=0; break}
                this.modalofic = 1;
                this.newform = 1;
                this.tituloModal = 'Registrar Unidad';
                this.descripunidad='';
                this.siglaunidad='';
                this.tipoAccion = 1;
                this.edif = 0;
                break;
            }
            case 'actualizar':
            {
                if(this.idunidad==0){Swal.fire("Seleccione una Unidad." , "", "error"); this.modal=0; break}
                this.modalofic=1;
                this.newform=1;
                this.tituloModal = 'Actualizar Unidad';
                this.tipoAccion=2;
                const res = this.arrayUnidad.find((unidad) => unidad.id == this.idunidad);
                this.descripunidad = res.descrip;
                this.siglaunidad = res.unidad;
                this.edif = 0;
                break;
            }
        }
    },
    registrarunidades(){
        let me = this;
        if (me.validarOficina()){return;}
        Swal.fire({
            title: "Realizando tareas, por favor espere!",
            icon: 'info',
            timer: 12000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                axios.post('/unidad/registrar',{
                    'descripunidad':this.descripunidad,
                    'sigla':this.siglaunidad,
                    'idciudad': this.ciudad,
                }).then(function (response) {
                    me.cerrarModal();
                    console.log(response);
                    Swal.fire(response.data.message,"","success");
                    me.listarUnidad(response.data.idciudad);
                    me.idunidad = response.data.id;
                }).catch(function (error) {
                    Swal.fire('Hubo un Error','','error');
                    console.log(error);
                }); 
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        });
    },
    actualizarUnidad(){
        let me = this;
        if (me.validarOficina()){return;}
        Swal.fire({
            title: "Realizando tareas, por favor espere!",
            icon: 'info',
            timer: 12000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                axios.put('/unidad/actualizar',{
                    'id':me.idunidad,
                    'descripunidad':me.descripunidad,
                    'sigla':me.siglaunidad,
                }).then(function (response) {
                    me.cerrarModal();
                    Swal.fire(response.data.message,"","success");
                    me.listarUnidad(me.ciudad);
                    me.idunidad = response.data.id;
                }).catch(function (error) {
                    Swal.fire('Hubo un Error','','error');
                    console.log(error);
                }); 
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        });
    },
    abrirModalEstab(accion){
        switch(accion){
            case 'registrar':
            {
                if(this.idunidad==0){Swal.fire("Seleccione una Unidad Administrativa." , "", "error"); this.modalofic=0; break}
                this.modalofic = 1;
                this.newform = 2;
                this.tituloModal = 'Registrar Establecimiento';
                this.descripestab='';
                this.siglaestab='';
                this.tipoAccion = 1;
                break;
            }
            case 'actualizar':
            {
                if(this.idunidad==0){Swal.fire("Seleccione una Unidad Administrativa." , "", "error"); this.modalofic=0; break}
                this.modalofic=1;
                this.newform=2;
                this.tituloModal = 'Actualizar Establecimiento';
                this.tipoAccion=2;
                const res = this.arrayEdif.find((edif) => edif.id == this.edif);
                this.descripestab = res.descripcion;
                this.siglaestab = res.sigla;
                break;
            }
        }
    },
    registrarestab(){
        let me = this;
        if (me.validarOficina()){return;}
        Swal.fire({
            title: "Realizando tareas, por favor espere!",
            icon: 'info',
            timer: 12000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                axios.post('/establecimientos/registrar',{
                    'descripestab':this.descripestab,
                    'sigla':this.siglaestab,
                    'idunidad': this.idunidad,
                }).then(function (response) {
                    me.cerrarModal();
                    Swal.fire(response.data.message,"","success");
                    me.listarEdif(me.idunidad);
                    me.edif = response.data.id;
                }).catch(function (error) {
                    Swal.fire('Hubo un Error','','error');
                    console.log(error);
                }); 
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        });
    },
    actualizarestab(){
        let me = this;
        if (me.validarOficina()){return;}
        Swal.fire({
            title: "Realizando tareas, por favor espere!",
            icon: 'info',
            timer: 12000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                axios.put('/establecimientos/actualizar',{
                    'id':me.edif,
                    'descripestab':me.descripestab,
                    'sigla':me.siglaestab,
                }).then(function (response) {
                    me.cerrarModal();
                    Swal.fire(response.data.message,"","success");
                    me.listarEdif(me.idunidad);
                    me.edif = response.data.id;
                }).catch(function (error) {
                    Swal.fire('Hubo un Error','','error');
                    console.log(error);
                }); 
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        });
    },
    validarOficina(){
        let me=this;
        me.errorOficina=0;
        me.errorMostrarMsjOficina =[];
        if(this.newform==1){
            if (me.descripunidad=='') me.errorMostrarMsjOficina.push("Ingrese una Descripción de Unidad");
            if (me.sigla=='') me.errorMostrarMsjOficina.push("Ingrese un Sigla de Unidad");
            if (me.errorMostrarMsjOficina.length) me.errorOficina = 1;
        }else{
            if(this.newform==2){
                if (me.descripestab=='') me.errorMostrarMsjOficina.push("Ingrese una Descripción de Establecimiento");
                if (me.siglaestab=='') me.errorMostrarMsjOficina.push("Ingrese un Sigla de Establecimiento");
                if (me.errorMostrarMsjOficina.length) me.errorOficina = 1;
            }else{
                if (me.nomofic=='') me.errorMostrarMsjOficina.push("Ingrese un Nombre de Oficina");
                if (me.errorMostrarMsjOficina.length) me.errorOficina = 1;
            }
        }
        
        return me.errorOficina;
    },
    actualizarOficina(){
        let me = this;
        axios.put('/oficinas/actualizar',{
            'id': me.id_oficina,
            'nomofic': me.nomofic,
            'observ' : me.observaciones,
            'estado' : me.estado,
            'idedif':me.edif,
        }).then( (response) =>{
            me.cerrarModal();
            Swal.fire(response.data.message, "", "success");
            me.codofic=response.data.codofic;
            me.edif=response.data.idedif;
            me.buscarOficina();
        }).catch(function (error) {
            Swal.fire(error , "", "error");
        });
    },
    registrarOficina(){
        let me = this;
        if (me.validarOficina()){return;}
        axios.post('/oficinas/registrar',{
            'nomofic': me.nomofic,
            'observ' : me.observaciones,
            'estado' : me.estado,
            'idedif':me.edif,
            'idunidad' : me.idunidad
        }).then( (response) => {
            me.cerrarModal();
            Swal.fire(response.data.message, "", "success");
            me.codofic=response.data.codofic;
            me.edif=response.data.idedif;
            me.buscarOficina();
        }).catch( (error) => {
            Swal.fire(error , "", "error");
        });
    },
    InactivarOficina(id){
        Swal.fire({
            title: "Esta Seguro?",
            text: "Se desactivará esta Oficina!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancelar!',
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Desactivar!"
            }).then((result) => {
            if (result.isConfirmed) {
                let me = this;
                axios.put('/oficinas/desactivar',{
                    'id': id
                }).then((response) =>{
                    Swal.fire({
                        title: "Desctivado!",
                        text: response.data.message,
                        icon: "success"
                        });
                    me.estado=0;
            }).catch((error) =>{
            console.log(error);
            });
            }
            });
    },
    ActivarOficina (id){
    Swal.fire({
        title: "Esta Seguro?",
        text: "Se activará esta Oficina!",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancelar!',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Activar!"
        }).then((result) => {
        if (result.isConfirmed) {
            let me = this;
        axios.put('/oficinas/activar',{
        'id': id
        }).then((response) =>{
            Swal.fire({
            title: "Activado!",
            text: response.data.message,
            icon: "success"
            });
            me.estado=1;
        }).catch((error) =>{
        console.log(error);
        });
        }
        });
    },
    ayuda() {
       Swal.fire({
                title: '🏢 Gestión de Responsables',
                html: `
                    <div style="text-align: left; font-size: 16px;">
                    <p>🔹 <strong>Selecciona ubicación</strong><br>
                    Elige <strong>Ciudad</strong>, <strong>Unidad Administrativa</strong>, <strong>Establecimiento</strong> y <strong>Oficina</strong> para ver la lista de responsables.</p>

                    <p>🔍 <strong>Buscar responsable específico</strong><br>
                    Usa el botón <strong>"Buscar"</strong> si deseas encontrar a un responsable en particular.</p>

                    <p>🛠️ <strong>Acciones disponibles</strong><br>
                    Puedes <strong>crear</strong>, <strong>editar</strong> y <strong>ver</strong> los activos asignados a cada responsable.</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'swal-wide'
                }
                });
    },
    },
  mounted() {
    this.listarUnidad();
  }
}
</script>
<style>
.table-fixed {
  max-height: 400px;
  overflow-y: auto;
  position: relative;
}

.table-fixed thead th,
.table-fixed tfoot th {
    position: sticky;
    top: 0;
    background: #fff; /* Fondo blanco para los encabezados */
    z-index: 1; /* Asegura que los encabezados estén por encima del contenido */
    align-items: center;
}
.table-fixed thead th {
    top: 0; /* Encabezado fijo en la parte superior */
}

.table-fixed tfoot th {
    bottom: 0; /* Pie fijo en la parte inferior */
}
.swal-wide {
  width: 600px !important;
}


</style>  