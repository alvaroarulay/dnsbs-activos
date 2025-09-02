<template>
    <main class="app-content">
        <div class="app-title">
            <div>
            <h1><i class="bi bi-laptop"></i> Cards</h1>
            <p>Material design inspired cards</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
            <li class="breadcrumb-item">UI</li>
            <li class="breadcrumb-item"><a href="#">Cards</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-title-w-btn">
                        <h3 class="title">Activo Fijos</h3>
                        <div class="btn-group">
                            <div class="row">
                                <div class="mb-3 d-grid gap-2 col-md-2 mx-auto">
                                    <button @click="abrirModal" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                                    <ModalBuscarActivo v-model:modalactivo="modalactivo" @close="cerrarModal" />

                                </div>
                                <div class="mb-3 d-grid gap-2 col-md-2 mx-auto">
                                    <button class="btn btn-primary" href="#" @click="nuevo">
                                        <i class="bi bi-plus-square fs-5"></i>  
                                        Nuevo 
                                    </button>
                                </div>
                                <div class="mb-3 d-grid gap-2 col-md-2 mx-auto">
                                    <a class="btn btn-primary" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
                                    </svg> Duplicar</a>
                                </div>
                                <div class="mb-3 d-grid gap-2 col-md-2 mx-auto">
                                    <a class="btn btn-primary" href="#"><i class="bi bi-pencil-square fs-5"></i> Editar</a>
                                </div>
                                <div class="mb-3 d-grid gap-2 col-md-2 mx-auto">
                                    <a class="btn btn-danger" href="#"><i class="bi bi-trash fs-5"></i> Eliminar</a>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="tile-body">

                        <div class="bs-component">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation"><a class="nav-link active" data-bs-toggle="tab" href="#datos" aria-selected="false" role="tab" tabindex="-1">Datos</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link " data-bs-toggle="tab" href="#multimedia" aria-selected="true" role="tab">Multimedia</a></li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="datos" role="tabpanel">
                            <fieldset class="border p-2" style="background-color:#eaecee;">
                                <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Código</label>
                                    <input class="form-control" type="text" v-model="codigo" :disabled="inputsDisabled">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Código ADI</label>
                                    <input class="form-control" type="text"  v-model="codigoadi" :disabled="inputsDisabled">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Fecha de Incorporación</label>
                                    <input class="form-control" type="date" v-model="fechainc" :disabled="inputsDisabled">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Indice UFV</label>
                                    <input class="form-control" type="text" v-model="ufv" :disabled="inputsDisabled">
                                </div>
                                </div>
                            </fieldset>
                            <br>
                            <fieldset class="border p-2" style="background-color:#eaecee;">
                                <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Grupo</label>
                                    <v-select 
                                        :options="gcontable" 
                                        v-model="grupocont" 
                                        label="nombre" 
                                        @update:modelValue="listarAuxiliar"
                                        :disabled="inputsDisabled"
                                        >
                                    </v-select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Auxiliar</label>
                                    <v-select 
                                        :options="auxiliares" 
                                        v-model="auxiliar" 
                                        label="nomaux"
                                        :disabled="inputsDisabled"
                                        >
                                    </v-select>
                            </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Estado</label>
                                        <select name="" id="" class="form-select" v-model="estado" :disabled="inputsDisabled">
                                            <option value="1">Bueno</option>
                                            <option value="2">Regular</option>
                                            <option value="3">Malo</option>
                                        </select>
                                </div>
                                </div>

                                <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Oficina</label>
                                    <v-select 
                                        :options="oficinas" 
                                        v-model="oficina" 
                                        label="nomofic" 
                                        @update:modelValue="listarResponsable"
                                        :disabled="inputsDisabled"
                                        >
                                    </v-select>
                            </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Responsable</label>
                                    <v-select 
                                        :options="responsables" 
                                        v-model="responsable" 
                                        label="nomresp" 
                                        @update:modelValue="actualizarCargo"
                                        :disabled="inputsDisabled"
                                        >Seleccione..
                                    </v-select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Cargo</label>
                                        <input type="text" class="form-control" :disabled="inputsDisabled" v-model="cargo" readonly/>
                                </div>
                                </div>

                                <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">ID Bien</label>
                                    <input type="text" class="form-control" :disabled="inputsDisabled" v-model="idbien"/>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Org. Financiador</label>
                                    <v-select :options="organismos" v-model="organismo" :reduce="option => option.of" label="des"></v-select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">NRO. de Convenio</label>
                                        <input type="text" class="form-control" :disabled="inputsDisabled" v-model="convenio"/>
                                </div>
                                </div>

                                <div class="row">
                                <div class="mb-3 col-md-2">
                                    <label class="form-label">Costo Inicial</label>
                                    <input type="text" class="form-control" :disabled="inputsDisabled" v-model="costini"/>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label class="form-label">Dep. Acu. Inicial</label>
                                    <input type="text" class="form-control" :disabled="inputsDisabled" v-model="depacuini"/>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" rows="3" v-text="descripcion" :disabled="inputsDisabled"></textarea>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Observaciones</label>
                                    <textarea class="form-control" rows="3" v-text="obs" :disabled="inputsDisabled"></textarea>
                                </div>
                                </div>
                            </fieldset>
                                </div>
                                <div class="tab-pane fade" id="multimedia" role="tabpanel">
                                <fieldset class="border p-2" style="background-color:#eaecee;">
                                        <div class="row">    
                                            <div class="container mt-4">
                                                <div class="gallery-container">
                                                    <img :src="mainImage" class="main-image">
                                                    <div class="thumbnail-container">
                                                        <img v-for="(img, index) in thumbnails" :key="index" :src="img" class="thumbnail" @click="changeImage(img)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label" for="exampleInputFile">Subir Imagenes</label>
                                                <input class="form-control" id="exampleInputFile" type="file" aria-describedby="fileHelp">
                                            </div>
                                        </div>
                                </fieldset>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="tile-footer">
                        <div class="row">
                            <div class="mb-3 d-grid gap-2 col-2 mx-auto">
                                <button type="button" class="btn btn-info" @click="anterior"><i class="bi bi-caret-left-fill"></i>Anterior</button>
                            </div>
                            <div class="mb-3 d-grid gap-2 col-2 mx-auto">
                                <button type="button" class="btn btn-info" @click="siguiente()">Siguiente <i class="bi bi-caret-right-fill"></i></button>
                            </div>
                            <div class="mb-3 col-4"></div>
                            <div class="mb-3 d-grid gap-2 col-2 mx-auto">
                                <button type="button" class="btn btn-success" ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                <path d="M11 2H9v3h2z"/>
                                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                </svg> Guardar</button>
                            </div>
                            <div class="mb-3 d-grid gap-2 col-2 mx-auto">
                                <button type="button" class="btn btn-danger" @click="cancelar()"><i class="bi bi-x-circle"></i> Cancelar</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
        </div>
    </main>
</template>

<script>
import ModalBuscarActivo from '../components/globales/ModalBuscarActivo.vue';
export default {
    data (){
        return {
            mainImage: '',
            thumbnails: [],
            codigo:'',
            codigoadi:'',
            fechainc:'',
            ufv:0,
            grupocont:null,
            auxiliar:null,
            cod_cont:0,
            cod_aux:0,
            oficina:null,
            responsable:null,
            cod_ofic:0,
            cod_resp:0,
            estado:0,
            organismo:null,
            cargo:'',
            descripcion:'',
            obs:'',
            idbien:0,
            convenio:0,
            costini:0,
            depacuini:0,
            inputsDisabled: true,
            id:1,
            gcontable:[],
            auxiliares:[],
            oficinas:[],
            responsables:[],
            organismos:[],
            modalactivo: false, 
        }
    },
    components: {
        ModalBuscarActivo
    },
    methods: {
        abrirModal() {
        this.modalactivo = true;  // Cambiar el estado a true para abrir el modal
        },
        cerrarModal() {
        this.modalactivo = false;  // Cambiar el estado a false para cerrar el modal
        },
        actualizarModalActivo(nuevoValor) {
        this.modalactivo = nuevoValor;  // Actualizar el valor del modal
        },
        activo(id){
            let me = this;
            var url ='/actuales/unoxuno?id='+id;
            axios.get(url).then( (response) => {
                var respuesta = response.data.activo;
                let day = String(respuesta.dia).padStart(2, '0');
                let month = String(respuesta.mes).padStart(2, '0'); 
                me.codigo = respuesta.codigo;
                me.codigoadi = respuesta.codsec;
                me.cod_cont = respuesta.codcont;
                me.cod_aux = respuesta.codaux;
                me.cod_ofic = respuesta.codofic;
                me.cod_resp = respuesta.codresp;
                me.idbien = respuesta.idbien;
                me.descripcion = respuesta.descripcion;
                me.obs = respuesta.observ; 
                me.organismo = respuesta.org_fin;
                me.fechainc = `${respuesta.año}-${month}-${day}`;   
                me.estado=respuesta.codestado;
                me.costini=respuesta.costo;
                me.depacuini=respuesta.depacu;
                me.listarContables();
                me.listarAuxiliar();
                me.listarOrganismo();
                me.listarOficinas();
                me.listarResponsable();
                me.mostrarImagen();
            })
            .catch( (error) => {
                console.log(error);
            });
        },
        siguiente(){
            this.activo(this.id +=1);
            
        },
        anterior(){
            this.activo(this.id -=1);
            
        },
        changeImage(src) {
            this.mainImage = src;
        },
        nuevo()
        {
            let me = this;
            me.inputsDisabled = false;
            me.cod_cont = 1;
            me.cod_aux = 1;
            me.cod_ofic = 1;
            me.cod_resp = 1;
            me.grupocont = me.gcontable.find(c => c.codcont == me.cod_cont);
            me.oficina = me.oficinas.find(o => o.codofic == me.cod_ofic);
            me.limpiar();
            me.listarAuxiliar();
            me.listarResponsable();
            me.mainImage = '';
            me.thumbnails = [];
        },
        limpiar(){
            let me = this;
            me.codigo='';
            me.codigoadi='';
            me.fechainc='';
            me.ufv=0;
            me.cargo='';
            me.idbien=0;
            me.convenio=0;
            me.costini=0;
            me.depacuini=0;
            me.descripcion='';
            me.obs='';
        },
        cancelar(){
            this.inputsDisabled = true;
            this.activo(this.id);
        },
    listarContables (){
        let me=this;
        var url= '/contable';
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.gcontable = respuesta.codigos;
            if(me.cod_cont != 0){
                me.grupocont = me.gcontable.find(c => c.codcont == me.cod_cont);
            }
        })
        .catch( (error)=> {
            console.log(error);
        });
        
    },
    async listarAuxiliar (){
        let me =this;
        if(me.inputsDisabled==false){
            me.cod_cont = me.grupocont.codcont;
        }
      try {
                const response = await axios.get(`/auxiliar/${me.cod_cont}`);
                me.auxiliares = response.data.auxiliares;
                if (me.cod_aux != 0) {
                    me.auxiliar = me.auxiliares.find(a => a.codaux == me.cod_aux);
                }
            } catch (error) {
                console.error("Error al cargar auxiliares:", error);
            }
    },
    async listarOficinas(){
        let me=this;
        var url= '/oficina';
        try{
            const response = await axios.get(url);
            me.oficinas = response.data.oficinas;
            if(me.cod_ofic != 0){
                me.oficina = me.oficinas.find(o => o.codofic == me.cod_ofic);
            }
        }catch(error){
            console.log("error al cargar oficinas",error);
        }
        
    },
    async listarResponsable(){
        let me =this;
        if(me.inputsDisabled==false){
            me.cod_ofic = me.oficina.codofic;
        }
        try {
                const response = await axios.get(`/responsable/${me.cod_ofic}`);
                me.responsables = response.data;
                if (me.cod_resp != 0) {
                    me.responsable = me.responsables.find(r => r.codresp == me.cod_resp);
                    me.actualizarCargo();
                }
            } catch (error) {
                console.error("Error al cargar responsables:", error);
            }
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
    actualizarCargo() {
      if (this.responsable) {
        this.cargo = this.responsable.cargo;
      } else {
        this.cargo = "";
      }
    },
    mostrarImagen()
        {
            let me = this;
            axios.get('/image/ver/' + me.id).then((response) =>{
                if(response.data.length > 0){
                    me.mainImage = response.data[0];
                    me.thumbnails = response.data;
                }else{
                    
                    me.thumbnails = [];
                    me.mainImage = '/img/no-image.png';
                    me.thumbnails = ['/img/no-image.png'];
                }
                
            }).catch((error)=> {
                console.log(error);
            }); 
        },
    },
    mounted(){
        this.activo(this.id);
    }
}
</script>
<style>
.gallery-container {
    text-align: center;
    padding: 20px;
    border-radius: 10px;
}
.main-image {
    width: auto;
    height: 500px;
    border: 5px solid white;
    border-radius: 10px;
}
.thumbnail-container {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}
.thumbnail {
    width: 80px;
    height: 80px;
    margin: 5px;
    border: 3px solid white;
    border-radius: 5px;
    cursor: pointer;

}

</style>