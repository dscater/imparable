<script setup>
import Content from "@/Components/Content.vue";
import MiTable from "@/Components/MiTable.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { ref, onMounted, onBeforeMount } from "vue";
import Formulario from "./Formulario.vue";
import FormPassword from "./FormPassword.vue";
import { useAppStore } from "@/stores/aplicacion/appStore";
const { props: props_page } = usePage();
const appStore = useAppStore();

onBeforeMount(() => {
    appStore.startLoading();
});

const { setUsuario, limpiarUsuario } = useUsuarios();

const miTable = ref(null);
const headers = [
    {
        label: "",
        key: "id",
        sortable: true,
        width: "3%",
    },
    {
        label: "FOTO",
        key: "foto",
        sortable: false,
        width: "3%",
    },
    {
        label: "USUARIO",
        key: "usuario",
        sortable: true,
    },
    {
        label: "AP. PATERNO",
        key: "persona.paterno",
        keySortable: "personas.paterno",
        sortable: true,
    },
    {
        label: "AP. MATERNO",
        key: "persona.materno",
        keySortable: "personas.materno",
        sortable: true,
    },
    {
        label: "NOMBRE(S)",
        key: "persona.nombre",
        keySortable: "personas.nombre",
        sortable: true,
    },
    {
        label: "C.I.",
        key: "persona.full_ci",
        keySortable: "personas.full_ci",
        sortable: true,
    },
    {
        label: "DIRECCIÓN",
        key: "persona.dir",
        keySortable: "personas.dir",
        sortable: true,
    },
    {
        label: "CORREO",
        key: "persona.correo",
        keySortable: "personas.correo",
    },
    {
        label: "TELÉFONO",
        key: "persona.fono",
        keySortable: "personas.fono",
    },
    {
        label: "TIPO",
        key: "tipo",
        keySortable: "tipo",
        sortable: true,
    },
    {
        label: "ACCESO",
        key: "acceso",
        keySortable: "acceso",
        sortable: true,
    },
    {
        label: "ACCIÓN",
        key: "accion",
        fixed: "right",
        width: "4%",
    },
];

const multiSearch = ref({
    search: "",
    filtro: [],
});

const accion_formulario = ref(0);
const muestra_formulario = ref(false);
const accion_formulario_pass = ref(0);
const muestra_formulario_pass = ref(false);

const agregarRegistro = () => {
    limpiarUsuario();
    accion_formulario.value = 0;
    muestra_formulario.value = true;
};

const updateDatatable = async () => {
    if (miTable.value) {
        await miTable.value.cargarDatos();
        muestra_formulario.value = false;
    }
};

onMounted(async () => {
    appStore.stopLoading();
});
</script>
<template>
    <Head title="Usuarios"></Head>

    <Content>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN panel -->
                <div class="card">
                    <!-- BEGIN card-header -->
                    <div class="card-header">
                        <h4 class="panel-title btn-nuevo">
                            <button
                                v-if="
                                    props_page.auth?.user.permisos == '*' ||
                                    props_page.auth?.user.permisos.includes(
                                        'usuarios.create'
                                    )
                                "
                                type="button"
                                class="btn btn-primary"
                                @click="agregarRegistro"
                            >
                                <i class="fa fa-plus"></i> Nuevo Usuario
                            </button>
                        </h4>
                    </div>
                    <!-- END panel-heading -->
                    <!-- BEGIN panel-body -->
                    <div class="card-body">
                        <div class="row justify-content-end">
                            <div class="col-md-5 my-1 d-flex">
                                <div
                                    class="input-group"
                                    style="align-items: end"
                                >
                                    <input
                                        v-model="multiSearch.search"
                                        placeholder="Buscar"
                                        class="form-control border-1 border-right-0"
                                    />
                                    <div class="input-append">
                                        <button
                                            class="btn btn-default rounded-0 border-left-0"
                                            @click="updateDatos"
                                        >
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <MiTable
                                    :tableClass="'bg-white mitabla'"
                                    ref="miTable"
                                    :cols="headers"
                                    :api="true"
                                    :url="route('usuarios.paginado')"
                                    :numPages="5"
                                    :multiSearch="multiSearch"
                                    :syncOrderBy="'id'"
                                    :syncOrderAsc="'DESC'"
                                    table-responsive
                                    fixed-header
                                >
                                    <!-- <template #foto="{ item }">
                                        <img :src="item.url_foto" alt="" />
                                    </template> -->

                                    <!-- <template #descripcion="{ item }">
                                        <div class="w-100" v-html="item.descripcion"></div>
                                    </template> -->
                                    <template #accion="{ item }">
                                        ACCIONES
                                    </template>
                                </MiTable>
                            </div>
                        </div>
                    </div>
                    <!-- END panel-body -->
                </div>
                <!-- END panel -->
            </div>
        </div>
    </Content>

    <Formulario
        :muestra_formulario="muestra_formulario"
        :accion_formulario="accion_formulario"
        @envio-formulario="updateDatatable"
        @cerrar-formulario="muestra_formulario = false"
    ></Formulario>
    <FormPassword
        :muestra_formulario="muestra_formulario_pass"
        :accion_formulario="accion_formulario_pass"
        @envio-formulario="muestra_formulario_pass = false"
        @cerrar-formulario="muestra_formulario_pass = false"
    ></FormPassword>
</template>
