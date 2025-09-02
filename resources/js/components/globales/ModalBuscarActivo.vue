<template>
    <Teleport to="body">
                <div v-if="modalactivo" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Buscar Activo</h4>
           
              <button @click="$emit('update:modalactivo', false)"><span aria-hidden="true">x</span></button>
            </div>
  
            <div class="modal-body">
              <div class="col-md-6">
                <div class="input-group mb-3">
                  <select class="form-select col-md-3" v-model="criterio">
                    <option value="codigo">Código</option>
                    <option value="codigosec">Código Secundario</option>
                    <option value="descripcion" selected>Descripción</option>
                  </select>
                  <input type="text" v-model="buscar" @keyup.enter="listarArticulo" class="form-control" placeholder="Texto a buscar">
                  <button type="submit" @click="listarArticulo" class="btn btn-primary">
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
  
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Opciones</th>
                      <th>Codigo</th>
                      <th>Descripción</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="activo in arrayActivos" :key="activo.id">
                      <td>
                        <button type="button" @click="agregarActivo(activo)" class="btn btn-success btn-sm">
                          <i class="fa fa-check"></i>
                        </button>
                      </td>
                      <td v-text="activo.codigo"></td>
                      <td v-text="activo.descripcion"></td>
                      <td>
                        <span v-if="activo.codestado==1" class="badge badge-pill bg-success">Bueno</span>
                        <span v-else-if="activo.codestado==2" class="badge badge-pill bg-warning">Regular</span>
                        <span v-else-if="activo.codestado==3" class="badge badge-pill bg-danger">Malo</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                
              </div>
            </div>
  
            <div class="modal-footer">
               <button @click="$emit('update:modalactivo', false)">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </template>
  
  <script setup>
  import { ref, watch, defineProps, defineEmits } from "vue";
    const props = defineProps({modelValue: Boolean});
    const emit = defineEmits(["update:modelValue", "close"]);
    const modalactivo = ref(props.modelValue);
    const criterio = ref("descripcion");
    const buscar = ref("");
    const arrayActivos = ref([]);
  
  // Función para obtener la lista de activos con paginación
  const listarArticulo = (page = 1) => {
    let url = `/actuales/inventario?page=${page}&buscar=${buscar.value}&criterio=${criterio.value}`;
  
    axios
      .get(url)
      .then((response) => {
        arrayActivos.value = response.data.actuales.data;
        pagination.value = response.data.pagination;
      })
      .catch((error) => console.log(error));
  };
  
  
  // Detectar cuando el modal se abre y cargar los datos automáticamente
  
  watch(() => props.modelValue, (newVal) => {
    if (newVal) {
      modalactivo.value = newVal;
    }
    });
  // Función para cerrar el modal
  const cerramodalActivo = () => {
  emit("update:modelValue", false);  // Esto cerrará el modal al cambiar el valor
  emit("close");  // Puedes emitir un evento adicional si necesitas
    };
  </script>
  <script>
  export default {
    props: {
      modalactivo: Boolean  // Definir la prop que se está pasando desde el padre
    },
    emits: ["update:modalactivo", "close"], // Emitir eventos correctamente
  };
  </script>
  <style scoped>
  .modal {
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(170, 170, 170, 0.5);
    z-index: 1050;
  }
  </style>
  