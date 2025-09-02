<template>
    <main class="app-content">
      <div class="app-title row">
          <div class="col-md-6">
            <h1><i class="bi bi-database"></i> Base de Datos</h1>
          </div>
          <div class="col-md-6 text-end">
            <button class="btn btn-primary float-end" @click="downloadSql"><i class="bi bi-download"></i> Generar Backup</button>
          </div>
      </div>
        <div class="row justify-content-center">
            
            <div class="col-md-4 col-12">
                <div class="mb-3 card shadow-lg border-0 rounded-lg mt-12 border-warning">
                    <div class="card-header bg-warning text-black mb-3 text-center">
                        <b>Subir: SQL</b>
                     </div>   
                         <form @submit.prevent="subirSQL">
                            <div class="card-body">
                                <input type="file" name="archivo_sql" accept=".sql" class="form-control" @change="handleArchivosql">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="mb-3 col-md-6 col-12 form-group">
                                        <button class="btn btn-info form-control" type="submit"><i class="bi bi-upload"></i> Subir</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
                <div class="mb-3 card shadow-lg border-0 rounded-lg mt-12 boder-secondary">
                    <div class="card-header bg-secondary text-white mb-3 text-center">
                        <b>Backup: Vsiaf</b>
                    </div>
                    <form @submit.prevent="subirZip">
                    <div class="card-body">
                        <input type="file" name="archivo_zip" accept=".zip" class="form-control" @change="handleArchivo">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="mb-3 col-md-3 col-12 form-group">
                                <button class="btn btn-warning form-control btnagregar" type="button" @click="actualizar()"><i class="bi bi-arrow-clockwise"></i> Actualizar</button>
                            </div>
                            <div class="mb-3 col-md-3 col-12 form-group">
                                <button class="btn btn-success form-control" @click="viafzip()" ><i class="bi bi-box-arrow-down"></i>Descargar</button>
                            </div>
                            <div class="mb-3 col-md-3 col-12 form-group">
                                <button type="submit" class="btn btn-success form-control" :disabled="cargando"><i class="bi bi-box-arrow-in-down"></i>Subir ZIP</button>
                            </div>
                            <div class="mb-3 col-md-3 col-12 form-group">
                                <button type="button" class="btn btn-info form-control" @click="procesarZip" :disabled="procesando"><i class="bi bi-hourglass"></i>Procesar</button>
                            </div>
                        </div>
                    </div>
                    </form>

                </div>
                <div class="alert alert-warning" role="alert">
                    <h4>🔄 Restaurar copia de seguridad</h4>
                    <p>
                      Usa el botón <strong>Restaurar</strong> para aplicar la copia de seguridad seleccionada.<br>
                      <em>⚠️ Esta acción <strong>no</strong> restaura la base de datos del <strong>VSIAF</strong>.</em>
                    </p>

                    <p>
                      📁 Los registros de <strong>activos, oficinas, responsables y auxiliares</strong> serán <u>reemplazados por completo</u> con el contenido de la copia de seguridad.
                    </p>

                    <p>
                      ⏳ Si la copia de seguridad es muy grande, puede agotar el tiempo de espera del servidor.<br>
                      🛠️ En ese caso, se recomienda ejecutar la restauración mediante la <strong>línea de comandos</strong>.
                    </p>
                  </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card shadow-lg rounded-lg mt-12 border-info">
                    <div class="card-header text-bg-info mb-3 text-center">
                        <b>Backup: Sistema de Activos Fijos</b>
                    </div>
                  <div class="card-body">
                  <div class="table-responsive table-fixed">
                  <table class="table table-bordered table-striped table-sm">
                     <thead>
                         <tr>
                             <th>Opciones</th>
                             <th>Archivo</th>
                             <th>Creado</th>
                             <th>Usuario</th>
                         </tr>
                     </thead>
                     <tbody v-if="arrayDb.length">
                         <tr v-for="base in arrayDb" :key="base.id">
                             <td>
                                 <div class="bs-component">
                                   <button type="button" @click="restaurar(base.id)" class="btn btn-warning btn-sm"  >
                                   <i class="bi bi-arrow-clockwise"></i>
                                 </button>
                                 <button type="button" @click="eliminar(base.id)" class="btn btn-danger btn-sm" >
                                   <i class="bi bi-trash"></i>
                                 </button>
                               </div>
                             </td>
                             <td v-text="base.archivo"></td>
                             <td>{{ formatFecha(base.created_at) }}</td>
                             <td v-text="base.usuar"></td>
                         </tr>                                
                     </tbody>
                     <tbody v-else>
                         <tr>
                             <td colspan="3">
                                 NO hay Datos
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
                </div>

            </div>
        </div>
    </main>
</template>
<script>
import axios from 'axios';
export default {

  data() {
    return {
        archivo: null,
        mensaje: '',
        cargando: true,
        procesando: true,
        archivosql: null,
        mensajesql: '',
        cargandosql: true,
        procesandosql: true,
        arrayDb:[],
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
    Obtenerbase(page, buscar,criterio){
        let me=this;
        var url= '/backup/?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio ;
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.arrayDb = respuesta.base.data;
            me.pagination = respuesta.pagination;
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
        me.Obtenerbase(page,buscar,criterio);
    },
     handleArchivo(event) {
        this.cargando = false;
        const file = event.target.files[0];
        if (file) {
            const nombre = file.name.toLowerCase();
            const extensionValida = nombre.endsWith('.zip');

            if (extensionValida) {
            this.archivo = file;
            this.mensaje = '';
            } else {
            this.archivo = null;
            this.mensaje = 'Solo se permiten archivos con extensión .zip';
            }
        }
    },
    handleArchivosql(event) {
      this.cargandosql = false;
      const file = event.target.files[0];
      
      if (file) {
        const extensionValida = /\.sql$/i.test(file.name);
      if (extensionValida) {
          this.archivosql = file;
        } else {
          this.archivosql = null;
          swal.fire({
            title: 'Error',
            text: 'Solo se permiten archivos con extensión .sql',
            icon: 'error',
            confirmButtonText: 'Aceptar'
          });
        }
      }
    },
    async subirSQL() {
      this.cargandosql = true;
      this.mensajesql = '';

      const formData = new FormData();
      formData.append('archivo_sql', this.archivosql);
      try {
        const respuesta = await fetch('/subir-sql', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        });
        const resultado = await respuesta.json();
        swal.fire({
          title: resultado.titulo || 'Éxito',
          text: resultado.mensaje || 'Operación completada exitosamente',
          icon: resultado.icono || 'success',
          confirmButtonText: 'Aceptar'
        });
        this.archivosql = null;
        this.cargandosql = true;
        this.procesandosql = false;
        this.Obtenerbase(1,'','');
      } catch (error) {
        this.mensajesql = 'Error al subir el SQL';
      } finally {
        this.cargandosql = false;
      }
    },
    async procesarSql() {
      this.mensajesql = 'Procesando el archivo SQL...';
      this.procesandosql = true;
      try {
        const respuesta = await axios.get('/procesar-sql');
        swal.fire({
          title: respuesta.titulo || 'Éxito',
          text: respuesta.mensaje || 'Operación completada exitosamente',
          icon: respuesta.icono || 'success',
          confirmButtonText: 'Aceptar'
        });
        this.archivosql = null;
        this.cargandosql = true;
        this.procesandosql = false;
      } catch (error) {
        this.mensajesql = 'Error al procesar el SQL';
      } finally {
        this.cargandosql = false;
      }
    },
    async subirZip() {

      this.cargando = true;
      this.mensaje = '';

      const formData = new FormData();
      formData.append('archivo_zip', this.archivo);

      try {
        const respuesta = await fetch('/reemplazar-zip', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        });
        const resultado = await respuesta.json();
        swal.fire({
          title: resultado.titulo || 'Éxito',
          text: resultado.mensaje || 'Operación completada exitosamente',
          icon: resultado.icono || 'success',
          confirmButtonText: 'Aceptar'
        });
        this.archivo = null;
        this.cargando = true;
        this.procesando = false;
      } catch (error) {
        this.mensaje = 'Error al subir el ZIP';
      } finally {
        this.cargando = false;
      }
    },
    async procesarZip() {
      
      this.mensaje = 'Procesando el archivo ZIP...';
      this.procesando = true;
      try {
        const respuesta = await axios.get('/procesar-zip');
        swal.fire({
          title: respuesta.titulo || 'Éxito',
          text: respuesta.mensaje || 'Operación completada exitosamente',
          icon: respuesta.icono || 'success',
          confirmButtonText: 'Aceptar'
        });
        this.archivo = null;
        this.cargando = true;
        this.procesando = false;
      } catch (error) {
        this.mensaje = 'Error al procesar el ZIP';
      } finally {
        this.cargando = false;
      }
    },
    async actualizar() {
      this.cargando = true;
      try {
        const respuesta = await axios.get('/actualizar');
        swal.fire({
          title: 'Éxito',
          text: respuesta.data.mensaje ,
          icon:'success',
          confirmButtonText: 'Aceptar'
        });
      } catch (error) {
        swal.fire({
          title: 'Error',
          text: 'Hubo un error al actualizar los datos',
          icon: 'error',
          confirmButtonText: 'Aceptar'
        });
      } finally {
        this.cargando = false;
      }
    },
    async restaurar(id){
      try {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, restaurar!',
                cancelButtonText: 'Cancelar',
            });

            if (result.isConfirmed) {
                const response = await axios.get(`/procesar-sql/${id}`)
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            }

        } catch (error) {
            console.error('Error al eliminar la provedor:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo Restaurar',
            });
        }
    },
    downloadSql() {
      window.open('/download-sql');
      this.Obtenerbase(1,'','');
    },
    viafzip(){
      window.open('/download-zipvsiaf');
    },
    formatFecha(fecha) {
      return new Date(fecha).toLocaleString('es-BO', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
    },
  async eliminar(id){
      try {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar',
            });

            if (result.isConfirmed) {
                const response = await axios.delete(`/eliminar-sql/${id}`)
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            }
             this.Obtenerbase(1,'','');
        } catch (error) {
            console.error('Error al eliminar la Backup:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar',
            });
        }
    },
  },
  mounted() {
    this.Obtenerbase(1,'','');
  }
}

</script>