<template>
<main class="app-content">
    <div class="app-title row">
             <div class="col-md-8">
                <h1><i class="bi bi-collection"></i> Unidad: {{ titulo }}</h1> 
                <h5><i class="bi bi-people-fill"></i> Usuarios</h5>
             </div>
             <div class="col-md-4">
                 <select class="form-select" @change="onChangeUnidad($event)" v-model="idunidad" v-if="idrol==1">
                     <option v-for="unidad in arrayUnidad" :value="unidad.unidad" v-text="unidad.descrip"></option>
                 </select>
             </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-lg border-secondary rounded-lg mt-12">
                <div class="card-header bg-secondary text-white"><strong> Responsable: </strong></div>
                <div class="card-body">
                    <div class="row">
                    <div class="mb-3 col-md-6">
                        <div class="mb-3 input-group" >
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="bi bi-person-vcard-fill"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control"  placeholder="Ingrese un Carnet de Identidad" v-model="ci" @keyup.enter="buscarResponsable()" >
                        </div>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <button class="btn btn-info form-control btnagregar" type="button" @click="buscarResponsable()"><i class="bi bi-plus-lg" ></i> Agregar</button>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <button class="btn btn-success form-control btnagregar" type="button" @click="abrirModalresp()"><i class="bi bi-search"></i> Buscar</button>
                    </div>
                    </div>

                    <div class="mb-3 row">
                    <label class="form-label col-md-4">Nombre:</label>
                    <div class="col-md-8 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="bi bi-person-square"></i>
                            </span>
                        </div>
                        <input class="form-control" type="text"  disabled v-model="nomresp">
                    </div>
                    </div>

                    <div class="mb-3 row">
                    <label class="form-label col-md-4">Correo Electronico:</label>
                    <div class="col-md-8 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                            </span>
                        </div>
                        <input class="form-control" type="text" v-model="email">
                    </div>
                    </div>

                    <div class="mb-3 row">
                    <label class="form-label col-md-4">Nombre de Usuario:</label>
                    <div class="col-md-8 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="bi bi-person-fill-check"></i>
                            </span>
                        </div>
                        <input class="form-control" type="text"  v-model="username">
                    </div>
                    </div>

                    <div class="mb-3 row">
                    <label class="form-label col-md-4">Contraseña:</label>
                    <div class="col-md-8 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="bi bi-key"></i>
                            </span>
                        </div>
                        <input class="form-control" type="text" v-model="ci">
                    </div>
                    </div>


                    <div class="mb-3 row">
                    <label class="form-label col-md-4">Roles:</label>
                    <div class="col-md-8 input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="bi bi-list-check"></i>
                            </span>
                        </div>
                        <select name="" id="" class="form-select" v-model="rol"  @change="onChange($event)">
                        <option v-for="rol in arrayRoles" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                        </select>
                    </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div v-show="errorUser" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjUser" :key="error" v-text="error">

                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <button class="btn btn-primary form-control" type="button" @click="registrarPersona()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16" >
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg> Guardar</button>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-info">
                    <div class="card-header bg-info">
                        <strong>Listado de Usuarios</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                            <div class="tile-body table-responsive mt-3">
                            <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th>Opciones</th>
                                                    <th>Nombre</th>
                                                    <th>Email</th>
                                                    <th>Usuario</th>
                                                    <th>Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="persona in arrayPersona" :key="persona.id">
                                                    <td>
                                                        <button type="button" @click="abrirModal(persona)" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-pencil"></i>
                                                        </button> &nbsp;
                                                        <template v-if="persona.condicion">
                                                            <button type="button" class="btn btn-success btn-sm" @click="desactivarUsuario(persona.id)">
                                                            <i class="fa fa-check"></i>
                                                            </button>
                                                        </template>
                                                        <template v-else>
                                                            <button type="button" class="btn btn-danger btn-sm" @click="activarUsuario(persona.id)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </template>
                                                    </td>
                                                    <td v-text="persona.name"></td>
                                                    <td v-text="persona.email"></td>
                                                    <td v-text="persona.username"></td>
                                                    <td>
                                                        <template v-if="persona.idrol==1">
                                                            <button type="button" class="btn btn-success btn-sm" @click="administrador(persona.id)">
                                                            <i class="fa fa-check"></i>Administrador
                                                            </button>
                                                        </template>
                                                        <template v-else>
                                                            <button type="button" class="btn btn-warning btn-sm" @click="operador(persona.id)">
                                                                <i class="bi bi-check2-all"></i>Operador
                                                            </button>
                                                        </template>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" > Actualizar Usuario</h4>
                        <button type="button" class="close btn btn-danger" @click="cerrarModal()" aria-label="Close">
                            <span>×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre de la persona">                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Role</label>
                                <div class="col-md-9">
                                    <select name="" id="" class="form-select" v-model="rol"  @change="onChange($event)">
                                    <option v-for="rol in arrayRoles" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Usuario</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="username" class="form-control" placeholder="Nombre del usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">password</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="password del usuario" autocomplete="on">
                                </div>
                            </div>
                            <div v-show="errorPersona" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
          <!----inicio modal-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalresp}" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Buscar Responsable</h4>
                            <button type="button" class="close btn btn-sm btn-danger" @click="cerrarModalresp()" aria-label="Close">
                                <span>×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <select class="form-select col-md-3" v-model="criterio">
                                        <option value="nomresp">Nombre</option>
                                        <option value="ci">Carnet</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarResponsable(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarResponsable(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Carnet</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="responsable in arrayResponsableTodos" :key="responsable.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(responsable)" class="btn btn-success btn-sm">
                                                <i class="fa fa-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="responsable.nomresp"></td>
                                            <td v-text="responsable.ci"></td>
                                            <td>
                                            <div v-if="responsable.api_estado==1">
                                                <span class="me-1 badge badge-pill bg-success">Activo</span>
                                            </div>
                                            <div v-else-if="responsable.api_estado==3">
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
                            <button type="button" class="btn btn-secondary" @click="cerrarModalresp()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
    </main>
</template>

<script>
    export default {
        data (){
            return {
                idunidad:'',
                unidad:'',
                idrol:0,
                titulo:'',
                arrayUnidad:[],
                persona_id: 0,
                name : '',
                email : '',
                username: '',
                password:'',
                rol: '',
                arrayPersona : [],
                arrayRoles : [],
                modal : 0,
                errorPersona : 0,
                errorMostrarMsjPersona : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'name',
                buscar : '',

                cod_resp:0,
                cod_ofi:0,
                ci:'',
                email:'',
                username:'',
                rol:1,
                codigo:'',
                codigoTable:'',
                descripcionTable:'',
                arrayResponsable:[],
                arrayResponsableTodos:[],
                arrayRoles:[],
                errorUser:0,
                errorMostrarMsjUser:[],
                nomresp:'',
                modalresp : 0,
                criterio:'nomresp',
                buscar: '',
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
            onChangeUnidad(event){
                this.idunidad=(event.target.value);
                const res = this.arrayUnidad.find((unidad) => unidad.unidad == this.idunidad);
                this.titulo= res.descrip;
                this.listarPersona(1,this.buscar,this.criterio);
                this.vaciar();
            },
            listarPersona (page,buscar,criterio){
                let me=this;
                var url= '/users?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio + '&unidad='+ me.idunidad;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPersona = respuesta.personas.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRol(){
                let me=this;
                axios.get('/rol/').then( (response) =>{
                    var respuesta= response.data;
                    me.arrayRoles = respuesta.roles.data;
                })
                .catch( (error) =>{
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersona(page,buscar,criterio);
            },
            actualizarPersona(){
               if (this.validarPersona()){
                    return;
                }
                let me = this;
                axios.put('/user/actualizar',{
                    'name': this.name,
                    'email' : this.email,
                    'idrol' : this.rol,
                    'username': this.username,
                    'password': this.password,
                    'id': this.persona_id

                }).then(function (response) {
                    Swal.fire(response.data.message, "", "success");
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            validarPersona(){
                this.errorPersona=0;
                this.errorMostrarMsjPersona =[];

                if (!this.name) this.errorMostrarMsjPersona.push("El nombre de la pesona no puede estar vacío.");
                if (!this.username) this.errorMostrarMsjPersona.push("El nombre de usuario no puede estar vacío.");
                if (!this.password) this.errorMostrarMsjPersona.push("La password del usuario no puede estar vacía.");
                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            cerrarModal(){
                this.modal=0;
            },
            abrirModal(data = []){
                this.selectRol();
                this.modal=1;
                this.persona_id=data['id'];
                this.name = data['name'];
                this.email = data['email'];
                this.username = data['username'];
                this.password=data['password'];
                this.rol=data['idrol'];
            },
            onChange(event) {
                this.rol=event.target.value;
            },
            desactivarUsuario(id){
                 const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
                    Swal.fire({
                        title: "Esta seguro de desactivar este usuario?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si, Desactivar!",
                        cancelButtonText: "Cancelar",
                        }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1,'','nombre');
                        Swal.fire({
                            title: "Borrado!",
                            text: "El Usuario fue Desactivado.",
                            icon: "success"
                            });
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarUsuario(id){
                 const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
                swal.fire({
                title: 'Esta seguro de activar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar!!',
                cancelButtonText: 'Cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1,'','nombre');
                        swal.fire(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            administrador(id){
                const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
                    Swal.fire({
                        title: "Desea Asignar como Operador este usuario?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si",
                        cancelButtonText: "Cancelar",
                        }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/operador',{
                        'id': id,
                        'idrol':2
                    }).then(function (response) {
                        me.listarPersona(1,'','nombre');
                        Swal.fire({
                            title: "Asignado!",
                            text: response.data.message,
                            icon: "success"
                            });
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            operador(id){
                const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
                    Swal.fire({
                        title: "Desea Asignar como Administrador este usuario?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si",
                        cancelButtonText: "Cancelar",
                        }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/administrador',{
                        'id': id,
                        'idrol':1
                    }).then(function (response) {
                        me.listarPersona(1,'','nombre');
                        Swal.fire({
                            title: "Asignado!",
                            text: response.data.message,
                            icon: "success"
                            });
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            buscarResponsable(){
        let me=this;
        if(me.idunidad==''){
          swal.fire('seleccione unidad','','error')
        }
        else{
        var url= '/responsable/buscarResponsable?filtro=' + me.ci + '&unidad=' + this.idunidad;

        axios.get(url).then((response)=>{
            me.arrayResponsable = response.data.responsable;
              me.nomresp=me.arrayResponsable.nomresp;
              me.cargo=me.arrayResponsable.cargo;
              me.oficina=me.arrayResponsable.nomofic;
              me.cod_ofi=me.arrayResponsable.codofic;
              me.cod_resp=me.arrayResponsable.codresp;
        })
        .catch(function (error) {
            console.log(error);
            Swal.fire('No se Encontro Responsable','','error')
        });
      }
            },
            listarResponsable(page, buscar,criterio){
                let me=this;
                var url= '/responsable/?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&unidad=' + this.idunidad;
                axios.get(url).then( (response) =>{
                    var respuesta= response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayResponsableTodos = respuesta.responsables.data;
                })
                .catch( (error) =>{
                    console.log(error);
                });
            },
            listarRoles(){
            let me=this;
                axios.get('/rol/').then( (response) =>{
                    var respuesta= response.data;
                    me.arrayRoles = respuesta.roles.data;
                })
                .catch( (error) =>{
                    console.log(error);
                });
            },
            cerrarModalresp(){
                this.modalresp=0;
            }, 
            abrirModalresp(){  
            this.modalresp = 1;
            this.listarResponsable(1,this.buscar,this.criterio);
            },
            agregarDetalleModal(data){
                this.ci=data.ci;
                this.nomresp=data.nomresp;
                this.cargo=data.cargo;
                this.oficina=data.nomofic;
                this.modal=0;
                this.cod_ofi=data.codofic;
                this.cod_resp=data.codresp;
                this.modalresp=0;
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarResponsable(page,buscar,criterio);
            },
            registrarPersona(){
            let me = this;
            if (this.validarPersona1()){ return;}
                axios.post('/user/registrar',{
                    'name': me.nomresp,
                    'email': me.email,
                    'username' : me.username,
                    'password' : me.ci,
                    'codresp' : me.cod_resp,
                    'codofic' : me.cod_ofi,
                    'idrol' : me.rol,
                    'unidad' : me.idunidad,
                }).then((response)=>{
                Swal.fire(response.data.message, "", "success");
                me.vaciar();
                me.listarUnidad();
                me.listarResponsable(1,'','');
                }).catch((error)=>{
                    console.log(error);
                });
            },
            validarPersona1(){
                this.errorUser=0;
                this.errorMostrarMsjUser =[];

                if (!this.nomresp) this.errorMostrarMsjUser.push("El nombre de la persona no puede estar vacío.");
                if (!this.username) this.errorMostrarMsjUser.push("El nombre de usuario no puede estar vacío.");
                if (!this.ci) this.errorMostrarMsjUser.push("La password del usuario no puede estar vacío.");
                if (this.idrol==0) this.errorMostrarMsjUser.push("Seleccione una Role.");
                if (this.errorMostrarMsjUser.length) this.errorUser = 1;

                return this.errorUser;
            },
            vaciar(){
                this.nomresp="";
                this.email = "";
                this.username = "";
                this.ci=0;
                this.cod_resp=0;
                this.cod_ofi =0;
                this.errorPersona=0;
                this.errorMostrarMsjPersona=[];
                this.errorUser =0;
                this.errorMostrarMsjUser=[];
            }
        },
        mounted() {
            this.listarUnidad();
            this.listarPersona(1,'','');
            this.selectRol();
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
