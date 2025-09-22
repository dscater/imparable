<script setup>
import Content from "@/Components/Content.vue";
import MiTable from "@/Components/MiTable.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { ref, onMounted, onBeforeMount } from "vue";
import Formulario from "./Formulario.vue";
import FormPassword from "./FormPassword.vue";
import { useAppStore } from "@/stores/aplicacion/appStore";
import { useAxios } from "@/composables/axios/useAxios";
const { props: props_page } = usePage();
const appStore = useAppStore();
const { axiosDelete, axiosPost } = useAxios();

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

const restaurar = (item) => {
    Swal.fire({
        title: "¿Quierés resturar este registro?",
        html: `<strong>${item.full_name}</strong>`,
        showCancelButton: true,
        confirmButtonText: "Si, restaurar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
        customClass: {
            confirmButton: "btn-success",
        },
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await axiosPost(
                route("usuarios.reestablecer", item.id),
                {
                    _method: "patch",
                }
            );
            if (respuesta && respuesta.sw) {
                updateDatatable();
            }
        }
    });
};

const eliminarUsuario = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar definitivamente este registro?",
        html: `<strong>${item.full_name}</strong><br/>Esta acción no se puede deshacer.`,
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
        customClass: {
            confirmButton: "btn-danger",
        },
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await axiosDelete(
                route("usuarios.eliminacion_permanente", item.id)
            );
            if (respuesta && respuesta.sw) {
                updateDatatable();
            }
        }
    });
};

onMounted(async () => {
    appStore.stopLoading();
});
</script>
<template>
    <Head title="Usuarios"></Head>

    <Content>
        <template #header>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-lg">
                        Usuarios <small>> Eliminados</small>
                    </h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <Link :href="route('inicio')">Inicio</Link>
                        </li>
                        <li class="breadcrumb-item">
                            <Link :href="route('usuarios.index')"
                                >Usuarios</Link
                            >
                        </li>
                        <li class="breadcrumb-item active">Eliminados</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </template>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
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
                        <Link
                            v-if="
                                props_page.auth?.user.permisos == '*' ||
                                props_page.auth?.user.permisos.includes(
                                    'usuarios.index'
                                )
                            "
                            class="btn btn-outline-dark mx-1"
                            :href="route('usuarios.index')"
                        >
                            <i class="fa fa-arrow-left"></i> Volver
                        </Link>
                    </div>
                    <div class="col-md-8 my-1">
                        <div class="row justify-content-end">
                            <div class="col-md-5">
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <MiTable
                            :tableClass="'bg-white mitabla'"
                            ref="miTable"
                            :cols="headers"
                            :api="true"
                            :url="route('usuarios.paginado_eliminados')"
                            :numPages="5"
                            :multiSearch="multiSearch"
                            :syncOrderBy="'id'"
                            :syncOrderAsc="'DESC'"
                            table-responsive
                            fixed-header
                        >
                            <template #foto="{ item }">
                                <img
                                    class="direct-chat-img"
                                    :src="item.url_foto"
                                    alt="Foto"
                                />
                            </template>

                            <template #acceso="{ item }">
                                <div
                                    class="badge text-sm"
                                    :class="[
                                        item.acceso == 1
                                            ? 'bg-success'
                                            : 'bg-danger',
                                    ]"
                                >
                                    {{
                                        item.acceso == 1
                                            ? "HABILITADO"
                                            : "DESHABILITADO"
                                    }}
                                </div>
                            </template>
                            <template #accion="{ item }">
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    content="Restaurar"
                                    placement="left-start"
                                >
                                    <button
                                        class="btn btn-success"
                                        @click="restaurar(item)"
                                        v-if="
                                            props_page.auth.user.permisos ==
                                                '*' ||
                                            props_page.auth?.user.permisos.includes(
                                                'personas.reestablecer'
                                            )
                                        "
                                    >
                                        <i class="fa fa-sync"></i></button
                                ></el-tooltip>
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    content="Eliminar definitivamente"
                                    placement="left-start"
                                >
                                    <button
                                        class="btn btn-danger"
                                        @click="eliminarUsuario(item)"
                                        v-if="
                                            props_page.auth.user.permisos ==
                                                '*' ||
                                            props_page.auth?.user.permisos.includes(
                                                'personas.eliminacion_permanente'
                                            )
                                        "
                                    >
                                        <i class="fa fa-trash-alt"></i></button
                                ></el-tooltip>
                            </template>
                        </MiTable>
                    </div>
                </div>
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
