<template>
     <main class="app-content">
      <div class="row user">
        <div class="col-md-3">
          <div class="tile p-0">
            <ul class="nav flex-column nav-tabs user-tabs">
              <li class="nav-item"><a class="nav-link active" href="#user-activos" data-bs-toggle="tab">Mis Activos</a></li>
              <li class="nav-item"><a class="nav-link" href="#user-settings" data-bs-toggle="tab">Mis Datos</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-9">
          <div class="tab-content">
            <div class="tab-pane active card shadow-lg border-info" id="user-activos">
              <div class="card-header bg-info"><strong>Mis Activos</strong></div>
                    <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-striped table-hover">
                            <thead>
                                <tr class="table-secondary">
                                    <th>Nro.</th>
                                    <th>Unidad</th>
                                    <th>Código</th>
                                    <th>Código Secundario</th>
                                    <th>Descripción</th>
                                    <th>Oficina</th>
                                    <th>Observaciones</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayActivos !== 0">
                                <tr v-for="(activo,index) in arrayActivos" :key="activo.id">
                                    <td v-text="index+1"></td>
                                    <td v-text="activo.unidad"></td>
                                    <td v-text="activo.codigo"></td>
                                    <td v-text="activo.codigosec"></td>
                                    <td v-text="activo.descripcion"></td>
                                    <td v-text="activo.nomofic"></td>
                                    <td v-text="activo.observ"></td>
                                    <td>
                                        <div v-if="activo.estado === 1">
                                            <span class="me-1 badge badge-pill bg-success">Bueno</span>
                                        </div>
                                        <div v-else-if="activo.estado === 2">
                                            <span class="me-1 badge badge-pill bg-warning">Regular</span>
                                        </div>
                                        <div v-else>
                                            <span class="me-1 badge badge-pill bg-danger">Malo</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                              <tr>
                                <th colspan="6">No tiene activos asignados!!!</th>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                  </div>
                  <div class="card-footer">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                  </div>
            </div>
            <div class="tab-pane fade card shadow-lg border-info" id="user-settings">
              <div class="card-header bg-info"><strong>Mis Datos</strong></div>
              <div class="card-body user-settings">
                  <div class="row mb-4">
                    <div class="col-md-4">
                      <label>Nombre de Usuario</label>
                      <input class="form-control" type="text" v-model="username">
                    </div>
                    <div class="col-md-4">
                        <label>Contraseña</label>
                        <div class="input-group">
                            <input :type="showPassword ? 'text' : 'password'" class="form-control" aria-describedby="basic-addon2" >
                            <span class="input-group-text" id="basic-addon2"><button @click="showPassword = !showPassword" class="btn btn-sm">
                                Mostrar
                            </button></span>
                        </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-8 mb-4">
                      <label>Nombre</label>
                      <input class="form-control" type="text" v-model="nomresp">
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-8 mb-4">
                      <label>Cargo</label>
                      <input class="form-control" type="text" v-model="cargo">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8 mb-4">
                      <label>Email</label>
                      <input class="form-control" type="text" v-model="email">
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-2 mb-4">
                      <label>Carnet No.</label>
                      <input class="form-control" type="text" v-model="ci">
                    </div>
                    <div class="col-md-2 mb-4">
                      <label>Expedido en</label>
                      <select name="" id="" class="form-select" v-model="cod_exp">
                        <option value="2">La Paz</option>
                        <option value="4">Oruro</option>
                        <option value="5">Potosí</option>
                        <option value="1">Chuquisaca</option>
                        <option value="6">Tarija</option>
                        <option value="3">Cochabamba</option>
                        <option value="7">Santa Cruz</option>
                        <option value="8">Beni</option>
                        <option value="9">Pando</option>
                        <option value="10">Qr</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-10">
                    <div class="col-md-12">
                      <button class="btn btn-primary" type="button" @click="actualizarResponsable()"> <i class="bi bi-check-circle-fill me-2"></i> Actualizar Datos</button>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </main>
</template>
<script>


export default {
  data() {
    return {
        showPassword: false,
        arrayActivos:[],
        username:'',
        nomresp:'',
        cargo:'',
        email:'',
        ci:'',
        cod_exp:'1',
        iduser:0,
        idresp:0,
        pagination : {
                        'total' : 0,
                        'current_page' : 0,
                        'per_page' : 0,
                        'last_page' : 0,
                        'from' : 0,
                        'to' : 0,
                    },
        offset : 1,
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
    
                    var to = from + (this.offset); 
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
        listarArticulo (page){
            let me=this;
            var url= '/activosxuser?page=' + page;
            axios.get(url).then( (response) => {
                var respuesta= response.data;
                me.arrayActivos = respuesta.actuales.data;
                me.pagination= respuesta.pagination;
            })
            .catch( (error) => {
                console.log(error);
            });
        },
        listarUsuario(){
            let me = this;
            var url= '/users/responsable';
            axios.get(url).then( (response) => {
                var respuesta = response.data.responsable;
                me.username = respuesta.username;
                me.nomresp = respuesta.nomresp;
                me.cargo = respuesta.cargo;
                me.email = respuesta.email;
                me.ci = respuesta.ci;
                me.cod_exp = respuesta.cod_exp;
                me.iduser = respuesta.user;
                me.idresp = respuesta.resp;
            })
            .catch( (error) => {
                console.log(error);
            });
        },
        actualizarResponsable(){
            let me = this;
            axios.put('/user/updateresponsable',{
                'iduser': me.iduser,
                'idresp':me.idresp,
                'username': me.username,
                'password': me.password,
                'nomresp' : me.nomresp,
                'cargo' : me.cargo,
                'email' : me.email,
                'ci': me.ci,
                'cod_exp': me.cod_exp,
            }).then( (response) =>{
                me.listarUsuario();
                Swal.fire(response.data.message, "", "success");
            }).catch( (error) =>{
                Swal.fire(error , "", "error");
            });
        },
        cambiarPagina(page){
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarArticulo(page);
        },
    },
    mounted() {
                this.listarArticulo(1);
                this.listarUsuario();
            }
  
}
</script>