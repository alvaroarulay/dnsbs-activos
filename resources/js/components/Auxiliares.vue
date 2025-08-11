<template>
    <main class="app-content">
         <div class="app-title row">
             <div class="col-md-8">
                <h1><i class="bi bi-collection"></i> Auxiliares </h1> 
             </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 col-12">
               <div class="card border-secondary mb-3 shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-secondary text-white">
                        Grupo Contable
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Nro. Codigo</label>
                                <input class="form-control" type="text" v-model="nro" disabled>
                            </div>
                            
                            <div class="mb-3">
                            <label class="form-label ">Vida Útil</label>
                                <input class="form-control" v-model="vidautil" disabled>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                            <label class="form-label">Grupo Contable</label>
                                <select class="form-select" @change="onChangeCodigo($event)">
                                    <option value="0" selected> Seleccione...</option>
                                    <option v-for="gcont in arrayContables" :value="gcont.codcont" v-text="gcont.codcont + ' - ' + gcont.nombre">
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                                <textarea name="" id="" cols="30" rows="2" class="form-control" v-model="observaciones" disabled></textarea>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                            <p class="bs-component d-grid">
                                    <button class="btn btn-info" @click="abrirModalcont()">Editar</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="alert alert-primary" role="alert">
  <h4>📘 Registro de Auxiliares: Guía Rápida</h4>

  <p>
    🔹 <strong>Selecciona un Grupo Contable</strong><br>
    Antes de registrar un auxiliar, asegúrate de elegir el grupo contable correspondiente.
  </p>

  <p>
    🔍 <strong>Buscar un auxiliar específico</strong><br>
    Puedes presionar el botón <strong>"Buscar"</strong> para localizar un auxiliar en particular.
  </p>

  <p>
    🏢 <strong>Importante</strong><br>
    Estás creando auxiliares que se aplicarán a <u>todas las unidades administrativas</u>.
  </p>
</div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card border-info mb-3 shadow-lg rounded-lg">
                    <div class="card-header bg-info text-black">
                        <i class="bi bi-calculator"></i> Auxiliares
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="codaux">Código</option>
                                        <option value="nomaux" selected>Auxiliar</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarAuxiliar(1,buscar,criterio,idcont)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarAuxiliar(1,buscar,criterio,idcont)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive table-fixed mt-3">
                                <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombre Auxiliar</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayAuxiliar.length">
                                <tr v-for="auxiliar in arrayAuxiliar" :key="auxiliar.id">
                                    <td>
                                        <button type="button" @click="abrirModal('update',auxiliar)" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                    <td v-text="auxiliar.codaux"></td>
                                    <td v-text="auxiliar.nomaux"></td>
                                </tr>                                
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3">
                                        NO hay Auxiliares en este Grupo
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Cantidad:</th>
                                    <th v-text="totalAuxiliares" colspan="2"></th>
                                </tr>
                            </tfoot>  
                        </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-4 ">
                    <p class="bs-component d-grid">
                        <button class="btn btn-info" @click="abrirModal('new','')">Nuevo Auxiliar</button>    
                    </p></div>
                    </div>
                        </div>
                         
                </div>
            </div>
        </div>
        
      <!-- Fin ejemplo de tabla Listado -->
          
             <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="false">
                 <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h4 class="modal-title" v-text="titulomodal"></h4>
                             <button type="button" class="close btn btn-danger btn-sm" @click="cerrarModal()" aria-label="Close">
                               <span aria-hidden="true">×</span>
                             </button>
                         </div>
                         <div class="modal-body" v-if="editform==1">
                             <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                 <div class="form-group row mb-3">
                                     <div class="col-md-12">
                                        <label class="form-lable" for="text-input">Grupo Contable:</label>
                                         <input type="text" v-model="grupocontable" class="form-control" disabled>                       
                                     </div>
                                 </div>
                                 <div class="form-group row mb-3">
                                     <div class="col-md-6">
                                        <label class="form-label" for="text-input">Nro. Codigo:</label>
                                         <input type="text" v-model="nro" class="form-control">                                        
                                     </div>
                                     <div class="col-md-6">
                                        <label class="form-label" for="text-input">Observaciones:</label>
                                         <input type="text" v-model="observ" class="form-control">                                        
                                     </div>
                                 </div>
                                 <div v-show="errorAuxiliar" class="form-group row div-error">
                                     <div class="text-center text-error">
                                         <div v-for="error in errorMostrarMsjAuxiliar" :key="error" v-text="error">
 
                                         </div>
                                     </div>
                                 </div>
 
                             </form>
                         </div>
                         <div class="modal-body" v-else-if="editform==2">
                             <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                 <div class="form-group row mb-3">
                                     <div class="col-md-12">
                                        <label class="form-lable" for="text-input">Grupo Contable:</label>
                                         <input type="text" v-model="grupocontable" class="form-control" disabled>                       
                                     </div>
                                 </div>
                                 <div class="form-group row mb-3">
                                     <div class="col-md-6">
                                        <label class="form-label" for="text-input">Nombre Auxiliar:</label>
                                         <input type="text" v-model="nomaux" class="form-control">                                        
                                     </div>
                                     <div class="col-md-6">
                                        <label class="form-label" for="text-input">Observaciones:</label>
                                         <input type="text" v-model="observ" class="form-control">                                        
                                     </div>
                                 </div>
                                 <div v-show="errorAuxiliar" class="form-group row div-error">
                                     <div class="text-center text-error">
                                         <div v-for="error in errorMostrarMsjAuxiliar" :key="error" v-text="error">
 
                                         </div>
                                     </div>
                                 </div>
 
                             </form>
                         </div>
                         <div class="modal-footer" v-if="editform==1">
                             <button type="button" class="btn btn-danger" @click="cerrarModal()"><i class="bi bi-x-square"></i>Cerrar</button>
                              
                             <button type="button" class="btn btn-warning" @click="actualizarContable()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                 <path d="M11 2H9v3h2z"/>
                                 <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                 </svg>Actualizar
                             </button>
                        </div>
                        <div class="modal-footer" v-else-if="editform==2">
                             <button type="button" class="btn btn-danger" @click="cerrarModal()"><i class="bi bi-x-square"></i>Cerrar</button>
                              
                             <button type="button" class="btn btn-warning" @click="actualizarAuxiliar()" v-if="estadomodal==0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                 <path d="M11 2H9v3h2z"/>
                                 <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                 </svg>Actualizar
                             </button>
                             <button type="button" class="btn btn-success" @click="nuevoAuxiliar()" v-if="estadomodal==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
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
        nombre_auxiliar:'',
        criterio:'nomaux',
        buscar:'',
        vidautil:0,
        observaciones:'',
        observ:'',
        idcont:0,
        tipoAccion:0,
        grupocontable:'',
        totalAuxiliares:0,
        id:0,
        nomaux:'',
        codcont:0,
        codaux:0,
        arrayAuxiliar : [],
        arrayContables : [],
        arrayActivos : [],
        totalActivos : 0,
        idunidad:'',
        unidad:'',
        idrol:0,
        titulo:'',
        arrayUnidad:[],
        modal : 0,
        errorAuxiliar : 0,
        errorMostrarMsjAuxiliar : [],

        titulomodal:'',
        estadomodal:1,
        editform:0,
        nro:0,
        
        criterio : 'nomaux',
        buscar : '',
        criterio1 : 'descrip',
        buscar1 : ''
    }
  },
  computed:{
            
        },
  methods: {
   
    listarAuxiliar (page,buscar,criterio,cod_cont){
        let me=this;
        var url= '/auxiliar?codcont='+ cod_cont +'&page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.arrayAuxiliar = respuesta.auxiliares;
            me.totalAuxiliares = respuesta.totalaux;
        })
        .catch( (error)=> {
            console.log(error);
        });
    },
    listarContables (){
        let me=this;
        var url= '/contable';
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.arrayContables = respuesta.codigos;
            me.idunidad=respuesta.unidad;
        })
        .catch( (error)=> {
            console.log(error);
        });
    },
    onChangeCodigo(event) {
        this.listado=1;
        this.idcont=event.target.value;
        const res = this.arrayContables.find((grupo)=>grupo.codcont==this.idcont);
        this.vidautil= res.vidautil;
        this.observaciones= res.observ;
        this.grupocontable=res.nombre;
        this.nro = res.codigo;
        this.listarAuxiliar(1,'','',res.codcont);
    },
     abrirModal(action,data = []){
        this.editform=2;
        switch(action){
            case 'update':
                this.modal=1
                this.id = data['id'];
                this.estadomodal=0;
                this.titulomodal='Actualizar Auxiliar';
                this.nomaux=data['nomaux'];
                this.codaux=data['codaux'];
                this.observ=data['observ'];
            break;
            case 'new':
                if(this.idcont==0){
                    Swal.fire('Seleccione un Grupo contable','','error');
                    this.modal=0;
                    break
                }
                this.modal=1;
                this.estadomodal=1;
                this.titulomodal='Nuevo Auxiliar';
                this.nomaux='';
                this.observ='';
            break;
        }
    
    },
    abrirModalcont(){
        if(this.idcont==0){Swal.fire('Seleccione un Grupo contable','','error'); return};
        const res = this.arrayContables.find((g)=>g.codcont==this.idcont);
        this.modal=1;
        this.editform=1;
        this.titulomodal='Editar Grupo Contable';
        this.grupocontable=res.nombre;
        this.nro = res.codigo;
        this.observ = res.observ;
    },
    actualizarContable(){
          let me = this;
          axios.put('/contable/actualizar',{
            'id':this.idcont,
            'codigo':this.nro,
            'observ':this.observ,
          }).then((response)=>{
              me.cerrarModal();
              Swal.fire(response.data.message,"","info");
              me.listarAuxiliar(1,'','',this.codcont);
          }).catch((error)=>{
            Swal.fire('Hubo un Error','','error');
              console.log(error);
          }); 
    },
    cerrarModal(){
      this.modal=0;
      this.nomaux='';
        this.codaux=0;
        this.observ='';
        this.errorAuxiliar=0;
        this.errorMostrarMsjAuxiliar=[];
        
    },
    actualizarAuxiliar(){
          let me = this;
          axios.put('/auxiliar/update',{
            'id':this.id,
            'nomaux':this.nomaux,
            'codcont':this.codcont,
            'codaux':this.codaux,
            'observ':this.observ,
          }).then((response)=>{
              me.cerrarModal();
              Swal.fire(response.data.message,"","info");
              me.listarAuxiliar(1,'','',this.codcont);
          }).catch((error)=>{
            Swal.fire('Hubo un Error','','error');
              console.log(error);
          }); 
    }, 
    nuevoAuxiliar(){
        let me = this;
        if(this.nomaux==''){
            this.errorAuxiliar=1;
            this.errorMostrarMsjAuxiliar=['Ingrese el Nombre del Auxiliar'];
            return false;
        }
        axios.post('/auxiliar/registrar',{
            'nomaux':this.nomaux,
            'codcont':this.idcont,
            'codaux':this.totalActivos + 1,
            'observ':this.observ,
        }).then((response)=>{
            me.cerrarModal();
            Swal.fire(response.data.message,"","info");
            me.listarAuxiliar(1,'','',this.idcont);
        }).catch((error)=>{
          Swal.fire('Hubo un Error','','error');
            console.log(error);
        });
    }, 
    onChangeUnidad(event){
        this.arrayContables=[];
        this.arrayAuxiliar=[];
        this.listado=1;
        this.buscar='';
        this.vidautil=0;
        this.totalAuxiliares=0;
        this.codcont=0;
        this.idunidad=(event.target.value);
        const res = this.arrayUnidad.find((unidad) => unidad.unidad == this.idunidad);
        this.titulo= res.descrip;
        this.listarContables(this.idunidad);
    },
    abrirlistado(data){
        this.listado=2;
        this.nombre_auxiliar = data['nomaux'];
        this.codaux = data['codaux'];
    },
    cerrarlistado(){
        this.listado=1;
        this.arrayActivos = [];
        this.totalActivos = 0;
        this.criterio1='descrip';
        this.buscar1='';
    }
  },

  mounted() {
    this.listarContables('');
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
</style>  