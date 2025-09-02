<template>
    <main class="app-content">
        <div class="app-title row">
                <div class="col-md-8">
                    <h1><i class="bi bi-collection"></i> Unidad: {{ titulo }}</h1> 
                </div>
                <div class="col-md-4">
                    <select class="form-select" @change="onChangeUnidad($event)" v-model="idunidad" v-if="idrol==1">
                        <option v-for="unidad in arrayUnidad" :value="unidad.unidad" v-text="unidad.descrip"></option>
                    </select>
                </div>
        </div>
        <div class="app-title">
            <h1><i class="fa fa-th-list"></i> Articulos</h1>
            <button type="submit" @click="nuevoactivo()" class="btn btn-success" v-if="idrol==1"><i class="bi bi-plus-square"></i> Nuevo</button>
        </div>
        <template v-if="listado==1">
        <div class="form-group row p-3 mb-2 bg-primary text-white">
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-select col-md-3" v-model="criterio">
                        <option value="codigo">Código</option>
                        <option value="codigosec">Código Secundario</option>
                        <option value="descripcion" selected>Descripción</option>
                    </select>
                    <input type="text" v-model="buscar" @keyup.enter="listarbusqueda(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                    <button type="submit" @click="listarbusqueda(1,buscar,criterio)" class="btn btn-info"><i class="fa fa-search"></i> Buscar</button>
                    </div>
            </div>
        </div>
        
            <div class="row">
                <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body table-responsive">
                    <table class="table table-hover table-sm  table-striped table-bordered" >
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">&nbsp;ACCIONES&nbsp;</th>
                            <th scope="col">UNIDAD</th>
                            <th scope="col">CÓDIGO</th>
                            <th scope="col">CÓDIGO SECUNDARIO</th>
                            <th scope="col">GRUPO CONTABLE</th>
                            <th scope="col">AUXLIAR</th>
                            <th scope="col">VIDA ÚTIL</th>
                            <th scope="col">OFICINA</th>
                            <th scope="col">RESPONSABLE</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col">FECHA DE INCORPORACIÓN</th>
                            <th scope="col">ID BIEN</th>
                            <th scope="col">ESTADO</th>
                            <th scope="col">ASIGNACIÓN</th>
                        </tr>
                        </thead>
                        <tbody v-if="arrayArticulo !== 0">
                            <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                <th scope="row">
                                    <button type="button" @click="updateactivo(articulo)" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></button>
                                </th>
                                <td v-text="articulo.unidad"></td>
                                <td>{{ articulo.codigo }}</td>
                                <td v-text="articulo.codigosec"></td>
                                <td v-text="articulo.nombre"></td>
                                <td v-text="articulo.nomaux"></td>
                                <td v-text="articulo.vidautil"></td>
                                <td v-text="articulo.nomofic"></td>
                                <td v-text="articulo.nomresp"></td>
                                <td v-text="articulo.descripcion"></td>
                                <td>{{ articulo.dia + '/' + articulo.mes + '/' + articulo.año }}</td>
                                <td v-text="articulo.cod_rube"></td>
                                <td>
                                    <div v-if="articulo.codestado === 1">
                                        <span class="me-1 badge badge-pill bg-success">Bueno</span>
                                    </div>
                                    <div v-else-if="articulo.codestado === 2">
                                        <span class="me-1 badge badge-pill bg-warning">Regular</span>
                                    </div>
                                    <div v-else>
                                        <span class="me-1 badge badge-pill bg-danger">Malo</span>
                                    </div>
                                </td>
                                <td>
                                    <div v-if="articulo.estadoasignacion === 1">
                                        <span class="me-1 badge badge-pill bg-success">Asignado</span>
                                    </div>
                                    <div v-else>
                                        <span class="me-1 badge badge-pill bg-warning">No Asignado</span>
                                    </div>
                                </td>
                                
                            </tr>                 
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <th colspan="11">No hay datos disponibles</th>
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
                </div>
            </div>
            <div class="row p-3 mb-2 bg-primary text-white text-end">
                <h4><b>Total Activos: </b>{{ total }}</h4>
            </div>
        </template>
        <template v-else>
            <div class="row">
                <div class="col-md-6">  
                    <div class="card border-info mb-3">
                        <div class="card-header text-bg-info mb-3">Dependencias</div>
                        <div class="card-body text-primary">
                            <div class="row">
                                <div class="col-md-6 mb-3 ">
                                <label for="" class="form-label">Grupo Contable</label>   
                                <v-select 
                                        :options="gruposContables" 
                                        v-model="gruposeleccionado" 
                                        label="nombre" 
                                        @update:modelValue="listarAuxiliar"
                                        :disabled="editactivo==1"
                                        >
                                </v-select>
                                </div>  
                                <div class="col-md-6 mb-3 ">
                                <label for="" class="form-label">Auxiliar</label>    
                                    <v-select 
                                        :options="auxiliares" 
                                        v-model="auxiliarSeleccionado" 
                                        label="nomaux" 
                                        >
                                    </v-select>
                                </div> 
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mb-3 ">
                                <label for="" class="form-label">Oficina</label>   
                                <v-select 
                                        :options="arrayOficina" 
                                        v-model="oficinaseleccionada" 
                                        label="nomofic" 
                                        @update:modelValue="listarResposables"
                                        :disabled="editactivo==1"
                                        >
                                </v-select>
                                </div>  
                                <div class="col-md-6 mb-3 ">
                                <label for="" class="form-label">Responsable</label>    
                                    <v-select 
                                        :options="arrayResponsables" 
                                        v-model="respSeleccionado" 
                                        label="nomresp" 
                                        :disabled="editactivo==1"
                                        >
                                    </v-select>
                                </div> 
                            </div>
                            <hr>
                           <div class="row">
                             <label for="" class="form-label">Organismo Financiador</label>
                                <div class="col-md-12 mb-3 ">
                                  <v-select :options="organismos" v-model="organismo"  label="des" :disabled="editactivo==1"></v-select>
                                </div>
                           </div>
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Id Bien</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="bien"><i class="bi bi-shop"></i></span>
                                        <input type="text" class="form-control" aria-describedby="bien" v-model="idbien">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Nro. de Convenio</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="convenio"><i class="bi bi-123"></i></span>
                                        <input type="text" class="form-control" aria-describedby="convenio" v-model="nroConvenio">
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-success me-md-2" type="button" v-if="newactivo==1" @click="saveactivo()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg>Guardar</button>
                            <button class="btn btn-success me-md-2" type="button" v-if="editactivo==1" @click="updateactivos()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg>Actualizar</button>
                            <button class="btn btn-danger" type="button" @click="cancelar" v-if="newactivo==1 || editactivo==1"><i class="bi bi-x-square"></i>Cancelar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-primary mb-3">
                        <div class="card-header text-bg-primary">Activo Fijos</div>
                        <div class="card-body text-primary">
                            <div class="row">
                                <label for="" class="form-label">Código</label>
                                <div class="input-group mb-3 input-group-lg">
                                    <span class="input-group-text" id="codigo"><i class="bi bi-ticket"></i></span>
                                    <input type="text" class="form-control fw-bold bg-warning " aria-describedby="codigo" 
                                        v-model="codigoactivo" 
                                        disabled
                                        >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Fecha de Incorporación</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="fecha"><i class="bi bi-calendar-date"></i></span>
                                        <input type="date" class="form-control" aria-describedby="fecha" v-model="fechaIncorporacion" :disabled="editactivo==1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Estado</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="estado"><i class="bi bi-list"></i></span>
                                        <select name="" id="" class="form-select" v-model="estadoasignacion">
                                            <option :value=1>Bueno</option>
                                            <option :value=2>Regular</option>
                                            <option :value=3>Malo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Descripción</label>
                                    <div class="input-group mb-3 ">
                                       <span class="input-group-text" id="descripcion"><i class="bi bi-receipt"></i></span> 
                                        <textarea name="descripcion" id="" rows="4" v-model="descripcion" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <label for="" class="form-label">Observaciones</label>
                                    <div class="input-group mb-3 ">
                                       <span class="input-group-text" id="observaciones"><i class="bi bi-eye"></i></span> 
                                        <textarea name="observaciones" id="" rows="4" v-model="observaciones" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Cantidad</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="cantidad"><i class="bi bi-123"></i></span>
                                        <input type="number" class="form-control" aria-describedby="cantidad" v-model="cantidad" :disabled="editactivo==1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Costo Incial</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="costoi"><i class="bi bi-currency-dollar"></i></span>
                                        <input type="number" class="form-control" aria-describedby="costoi" v-model="costoi" :disabled="editactivo==1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-primary">
                             <div v-if="errorArticulo" class="alert alert-danger">
                                <strong>Por favor corrige los siguientes errores:</strong>
                                <ul>
                                <li v-for="error in errorMostrarMsjArticulo" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </template>
    </main>
</template>
    
<script>
    import axios from 'axios';
    export default {
        data (){
            return {
                listado: 1,
                codigo : '',
                codcont:0,
                codaux:0,
                descripcion : '',  
                observaciones : '',      
                codestado : 0,
                estadoasignacion :1,
                codigosec:0,
                articulo_id: 0,
                popUp: false,
                idArticulo:0,
                total:0,
                newactivo:0,
                editactivo:0,
                codigoactivo: '',
                fechaIncorporacion: '',
                gruposeleccionado: null,
                auxiliarSeleccionado: null,
                idbien: '',
                nroConvenio: '',
                gruposContables: [],
                auxiliares: [],
                cantidad:1,
                costoi:0,
                cod_cont: 0,
                cod_aux: 0,
                organismo: {id:0,of: 0, des: 'Seleccione un Organismo'},
                organismos: [],
                arrayOficina:[],
                oficinaseleccionada:null,
                arrayResponsables:[],
                respSeleccionado:null,
                codigoparcial:'',
                correlativo:0,

                arrayUnidad:[],
                unidad:'',
                idunidad:'',
                idrol:0,
                titulo:'',

            
                selectedImages: [],
                imagenes: [],
                
                nombre : '',
                
                arrayArticulo : [],
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'descripcion',
                buscar : '',
                mostrandoEditar: false,
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
        methods : {
            
            listarbusqueda(page,buscar,criterio){
                if (this.buscar=='') {
                    Swal.fire('Escriba un texto','','error');
                };
                this.listarArticulo (page,buscar,criterio);
            },
            listarArticulo (page,buscar,criterio){
                let me=this;
                
                var url= '/actuales?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&unidad=' + me.idunidad;
                axios.get(url).then( (response) => {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.actuales.data;
                    me.pagination= respuesta.pagination;
                    me.idrol = respuesta.idrol;
                    me.total = respuesta.total;
                    me.titulo = respuesta.titulo;
                })
                .catch( (error) => {
                    console.log(error);
                });
            },
            listarContables (){
                let me=this;
                var url= '/contable';
                axios.get(url).then( (response) =>{
                    var respuesta= response.data;
                    me.gruposContables = respuesta.codigos;
                })
                .catch( (error)=> {
                    console.log(error);
                });
            },
            async listarAuxiliar (){
                let me = this;
                me.auxiliares = [];
                me.auxiliarSeleccionado = {id: 0, codaux: 0, nomaux: 'Seleccione un auxiliar'};
                try {
                        const response = await axios.get(`/auxiliar?codcont=${me.gruposeleccionado.codcont}`);
                        me.auxiliares = response.data.auxiliares;
                         if(me.newactivo==1){
                            me.checkAllSelected()
                        }
                    } catch (error) {
                        console.error("Error al cargar auxiliares:", error);
                    }
                },
            listarOficina(){
                let me=this;
                me.arrayOficina=[];
                me.oficinaseleccionada={id:0,codofic:0,nomofic:'Seleccione una Oficina'}
                var url= '/oficinas/activos/'+me.idunidad;
                axios.get(url).then( (response) =>{
                     me.arrayOficina = response.data;
                })
                .catch( (error)=> {
                    console.log(error);
                });
            },
            async listarResposables(){
                let me = this;
                me.arrayResponsables = [];
                me.respSeleccionado = {id: 0, codresp: 0, nomresp: 'Seleccione un responsable'};
                try {
                        const response = await axios.get(`/responsable/actual/${me.oficinaseleccionada.codofic}/${me.oficinaseleccionada.unidad}`);
                        me.arrayResponsables = response.data;
                        if(me.newactivo==1){
                            me.checkAllSelected()
                        }
                    } catch (error) {
                        console.error("Error al cargar responsables:", error);
                    }
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarArticulo(page,buscar,criterio);
            },
            onChangeUnidad(event){
                this.codigoactivo='';
                this.arrayArticulo = [];
                this.idunidad=event.target.value;
                const res = this.arrayUnidad.find((unidad) => unidad.unidad == this.idunidad);
                this.unidad= res.descrip;
                this.listarArticulo(1,this.buscar,this.criterio);
                if(this.listado!=1){
                    this.listarOficina();
                    this.listarResposables();
                    this.respSeleccionado = {id: 0, codresp: 0, nomresp: 'Seleccione un responsable'};
                }
            },
            checkAllSelected() {
                if (this.gruposeleccionado.id!=0 && this.oficinaseleccionada.id!=0 && this.idunidad) {
                    this.ejecutarFuncionFinal()
                }
            },
            ejecutarFuncionFinal() {
                let me = this;
                axios.post('/actual/codigo', {
                    'unidad': me.idunidad,
                    'idcont': me.gruposeleccionado.codcont
                }).then((res)=>{
                    me.correlativo=res.data.correlativo;
                    me.codigoparcial=res.data.codigo;
                    if(me.correlativo<10){
                        me.correlativo= '0000' + (me.correlativo+1);
                    } else if(me.correlativo<100){
                        me.correlativo= '000' + (me.correlativo+1);
                    } else if(me.correlativo<1000){
                        me.correlativo= '00' + (me.correlativo+1);
                    }else{
                        me.correlativo= '0' + (me.correlativo+1);
                    }
                    me.codigoactivo=me.codigoparcial + '-' + me.correlativo;
                }).catch((e)=>{
                    console.log(e);
                })
            },
            listarOrganismo(){
                let me=this;
                var url= '/organismos';
                axios.get(url).then( (response) =>{
                    var respuesta= response.data;
                    me.organismos = respuesta.organismos;
                })
                .catch( (error)=> {
                    console.log(error);
                });
                
            },
            actualizarArticulo(){
                if (this.validarArticulo()){return;}
                let me = this;
                axios.put('/actual/actualizar',{
                    'id': this.idArticulo,
                    'codigo':this.codigo,
                    'codcont': this.codcont,
                    'codaux': this.codaux,
                    'descripcion' : this.descripcion,
                    'observacion' : this.observaciones,
                    'estado' : this.codestado,
                    'codsec' : this.codigosec
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarArticulo(1,'','descripcion');
                    Swal.fire(response.data.message, "", "success");
                }).catch(function (error) {
                    Swal.fire(error.response.data, "", "error");
                }); 
            },
            async updateactivos (){
                let me = this;
                    try {
                        const response = await axios.put('/actual/actualizar', {
                            'id':this.idArticulo,
                            'unidad': this.idunidad,
                            'codaux': this.auxiliarSeleccionado.codaux,
                            'descripcion': this.descripcion,
                            'observacion': this.observaciones,
                            'estado': this.estadoasignacion,
                            'idbien': this.idbien,
                            'nroConvenio': this.nroConvenio
                        });
                        Swal.fire(response.data.message, "", "success");
                        me.listarArticulo(1,'','descripcion');
                        me.cancelar();
                    } catch (error) {
                        Swal.fire(error.response.data, "", "error");
                    }
            },
            validarArticulo(){
                this.errorArticulo=0;
                this.errorMostrarMsjArticulo =[];
                const validnumber = /^[-+]?\d+(\.\d+)?$/
                const validtext = /\w+/;
                const validcodigo =/^[a-zA-Z0-9-.]+$/;
                if(this.codigoactivo.length==0||!validcodigo.test(this.codigoactivo)){this.errorMostrarMsjArticulo.push('campo codigo vacio o invalido!')};
                if(this.fechaIncorporacion==null){this.errorMostrarMsjArticulo.push('campo fecha vacio o invalido!')};
                if(this.estadoasignacion==0){this.errorMostrarMsjArticulo.push('seleccione un estado')};
                if(this.gruposeleccionado.id==0){this.errorMostrarMsjArticulo.push('seleccione un grupo contable')};
                if(this.auxiliarSeleccionado.id==0){this.errorMostrarMsjArticulo.push('seleccione un auxiliar')};
                if(this.oficinaseleccionada.id==0){this.errorMostrarMsjArticulo.push('seleccione una Oficina')};
                if(this.respSeleccionado.id==0){this.errorMostrarMsjArticulo.push('seleccione un Responsable')};
                if(this.organismo.id==0){this.errorMostrarMsjArticulo.push('seleccione un oganismo financiador')};
                if(this.cantidad==0 ||!validnumber.test(this.cantidad)) { this.errorMostrarMsjArticulo.push('campo cantidad vacio o invalido!')};
                if(this.costoi==0 ||!validnumber.test(this.costoi)) { this.errorMostrarMsjArticulo.push('campo costo vacio o invalido!')};
                if(this.descripcion.length == 0||!validtext.test(this.descripcion)) this.errorMostrarMsjArticulo.push("campo descripcion vacia o invalida!.");
                if(this.errorMostrarMsjArticulo.length){this.errorArticulo=1}
                

                if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

                return this.errorArticulo;
            },
            listarUnidad (){
                    let me=this;
                    var url= '/unidad/select';
                    axios.get(url).then( (response) =>{
                    var respuesta= response.data;
                    me.arrayUnidad = respuesta.unidad;
                    me.unidad = respuesta.descripcion;
                    me.idunidad = respuesta.idunidad;
                    me.idrol = respuesta.idrol;
                    me.titulo = respuesta.titulo;
                })
                .catch( (error)=> {
                    console.log(error);
                });
            },
            nuevoactivo(){
                this.listado=2;
                this.newactivo=1;
                this.editactivo=0;
                this.arrayResponsables=[];
                this.respSeleccionado={id:0,nomresp:'Seleccione un responsable'};
                this.gruposeleccionado = {id: 1, codcont: 1, nombre: 'EDIFICACIONES'};
                this.listarContables ();
                this.listarAuxiliar ();
                this.listarOficina();
                this.organismo={id:0,of:0,des:'Seleccione un organismo'};
                this.listarOrganismo();
                this.codigoactivo = '';  
                this.descripcion = '';
                this.observaciones = '';
                this.codestado = 0;
                this.estadoasignacion = 1;
                this.codigosec=0;
                this.errorArticulo=0;
                this.cantidad = 1;
                this.costoi = 0;
                this.idbien = '';
                this.nroConvenio = '';
                this.fechaIncorporacion = new Date().toISOString().split('T')[0];
            },
            updateactivo(data=[]){
                let day = String(data['dia']).padStart(2, '0');
                let month = String(data['mes']).padStart(2, '0'); 
                this.listado=2;
                this.newactivo=0;
                this.editactivo=1;
                this.idArticulo = data['id'];
                this.codigoactivo = data['codigo'];
                this.codcont= data['codcont'];
                this.codaux = data['codaux'];
                this.fechaIncorporacion= `${data['año']}-${month}-${day}`; 
                this.listarContables();
                this.gruposeleccionado = {id: data['id'], codcont: data['codcont'], nombre: data['nombre']};    
                this.listarAuxiliar();
                this.auxiliarSeleccionado = {id: data['id'], codaux: data['codaux'], nomaux: data['nomaux']}; 
                this.listarOrganismo();
                this.organismo = this.organismos.find(o => o.of == data['org_fin']);this.descripcion = data['descripcion'];
                this.oficinaseleccionada = {id: 1,codofic:data['codofic'],nomofic:data['nomofic']};
                this.respSeleccionado = {id:1,codresp:data['codresp'],nomresp:data['nomresp']};
                this.observaciones = data['observ'];
                this.estadoasignacion =data['codestado'];
                this.codigosec=data['codigosec'];
                this.cantidad = 1;
                this.costoi = data['costo'];
                this.idbien = data['cod_rube'];
                this.nroConvenio = data['nro_conv'];
            },
            cancelar(){
                this.listado=1;
                this.arrayArticulo = [];
                this.idunidad = '';
                this.unidad = '';
                this.titulo = '';
                this.idrol = 0;
                this.codigo = '';
                this.codcont=0; 
                this.codaux=0;
                this.descripcion = '';
                this.observaciones = '';
                this.codestado = 0;
                this.estadoasignacion = 0;
                this.codigosec=0;
                this.errorArticulo=0;   
                this.selectedImages= [];   
                this.listarUnidad();
                this.listarArticulo(1,this.buscar,this.criterio); 
            },
        async saveactivo(){
                if(this.validarArticulo()){return;};
                let me = this;
                try {
                    const response = await axios.post('/actual/registrar', {
                        'unidad': this.idunidad,
                        'codigo': this.codigoactivo,
                        'codcont': this.gruposeleccionado.codcont,
                        'codaux': this.auxiliarSeleccionado.codaux,
                        'descripcion': this.descripcion,
                        'observacion': this.observaciones,
                        'estado': this.estadoasignacion,
                        'codigosec': this.codigosec,
                        'cantidad': this.cantidad,
                        'costo': this.costoi,
                        'idbien': this.idbien,
                        'nroConvenio': this.nroConvenio,
                        'fechaIncorporacion': this.fechaIncorporacion,
                        'organismoFinanciador': this.organismo.of,
                        'codresp':this.respSeleccionado.codresp,
                        'codofic':this.oficinaseleccionada.codofic,
                        'codigoparcial':this.codigoparcial,
                        'correlativo':this.correlativo,
                    });
                    Swal.fire(response.data.message, "", "success");
                    me.listarArticulo(1,'','descripcion');
                    me.cancelar();
                } catch (error) {
                    Swal.fire(error.response.data, "", "error");
                }
            }
        },
        mounted() {
            this.listarUnidad();
            this.listarArticulo(1,this.buscar,this.criterio);
        }
    }
</script>