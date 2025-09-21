<script setup>
import MiModal from "@/Components/MiModal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
const props = defineProps({
    muestra_formulario: {
        type: Boolean,
        default: false,
    },
    accion_formulario: {
        type: Number,
        default: 0,
    },
});

const { oUsuario, limpiarUsuario } = useUsuarios();
const accion_form = ref(props.accion_formulario);
const muestra_form = ref(props.muestra_formulario);
const enviando = ref(false);
let form = useForm(oUsuario.value);
watch(
    () => props.muestra_formulario,
    (newValue) => {
        muestra_form.value = newValue;
        console.log(newValue);
        if (muestra_form.value) {
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oUsuario.value);
        } else {
            document
                .getElementsByTagName("body")[0]
                .classList.remove("modal-open");
        }
    }
);
watch(
    () => props.accion_formulario,
    (newValue) => {
        accion_form.value = newValue;
    }
);

const { flash } = usePage().props;

const listTipos = ref([
    { value: "ADMINISTRADOR", label: "ADMINISTRADOR" },
    { value: "GERENTE", label: "GERENTE" },
    { value: "EMPLEADO", label: "EMPLEADO" },
]);
const listExpedido = [
    { value: "LP", label: "La Paz" },
    { value: "CB", label: "Cochabamba" },
    { value: "SC", label: "Santa Cruz" },
    { value: "CH", label: "Chuquisaca" },
    { value: "OR", label: "Oruro" },
    { value: "PT", label: "Potosi" },
    { value: "TJ", label: "Tarija" },
    { value: "PD", label: "Pando" },
    { value: "BN", label: "Beni" },
];
const foto = ref(null);

function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const tituloDialog = computed(() => {
    return accion_form.value == 0
        ? `<i class="fa fa-plus"></i> Nuevo Usuario`
        : `<i class="fa fa-edit"></i> Editar Usuario`;
});

const textBtn = computed(() => {
    if (enviando.value) {
        return `<i class="fa fa-spin fa-spinner"></i> Enviando...`;
    }
    if (accion_form.value == 0) {
        return `<i class="fa fa-save"></i> Guardar`;
    }
    return `<i class="fa fa-edit"></i> Actualizar`;
});

const enviarFormulario = () => {
    enviando.value = true;
    let url =
        form["_method"] == "POST"
            ? route("usuarios.store")
            : route("usuarios.update", form.id);

    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (response) => {
            console.log("correcto");
            const success =
                response.props.flash.success ?? "Proceso realizado con éxito";
            Swal.fire({
                icon: "success",
                title: "Correcto",
                html: `<strong>${success}</strong>`,
                confirmButtonText: `Aceptar`,
                customClass: {
                    confirmButton: "btn-success",
                },
            });
            limpiarUsuario();
            emits("envio-formulario");
        },
        onError: (err, code) => {
            console.log(code ?? "");
            const error =
                "Ocurrió un error inesperado contactese con el Administrador";
            Swal.fire({
                icon: "info",
                title: "Error",
                html: `<strong>${error}</strong>`,
                confirmButtonText: `Aceptar`,
                customClass: {
                    confirmButton: "btn-error",
                },
            });
            console.log("error: " + err.error);
        },
        onFinish: () => {
            enviando.value = false;
        },
    });
};

const emits = defineEmits(["cerrar-formulario", "envio-formulario"]);

watch(muestra_form, (newVal) => {
    if (!newVal) {
        emits("cerrar-formulario");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
};

const cerrarFormulario = () => {
    muestra_form.value = false;
};

const cargarListas = () => {};

onMounted(() => {
    cargarListas();
});
</script>

<template>
    <MiModal
        :open_modal="muestra_form"
        @close="cerrarFormulario"
        :size="'modal-xl'"
        :header-class="'bg-dark'"
        :footer-class="'justify-content-end'"
    >
        <template #header>
            <h4 class="modal-title" v-html="tituloDialog"></h4>
            <button
                type="button"
                class="close"
                @click.prevent="cerrarFormulario()"
            >
                <span aria-hidden="true">×</span>
            </button>
        </template>

        <template #body>
            <form @submit.prevent="enviarFormulario()">
                <div class="row">
                    <div class="col-md-4">
                        <label>Nombre(s)*</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.nombre,
                            }"
                            v-model="form.nombre"
                        />
                        <ul
                            v-if="form.errors?.nombre"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.nombre }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Ap. Paterno*</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.paterno,
                            }"
                            v-model="form.paterno"
                        />

                        <ul
                            v-if="form.errors?.paterno"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.paterno }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Ap. Materno</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.materno,
                            }"
                            v-model="form.materno"
                        />

                        <ul
                            v-if="form.errors?.materno"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.materno }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Número de C.I.*</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.ci,
                            }"
                            v-model="form.ci"
                        />

                        <ul
                            v-if="form.errors?.ci"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.ci }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Expedido*</label>
                        <select
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.ci_exp,
                            }"
                            v-model="form.ci_exp"
                        >
                            <option value="">- Seleccione -</option>
                            <option
                                v-for="item in listExpedido"
                                :value="item.value"
                            >
                                {{ item.label }}
                            </option>
                        </select>
                        <ul
                            v-if="form.errors?.ci_exp"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.ci_exp }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Dirección*</label>
                        <input
                            type="email"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.dir,
                            }"
                            v-model="form.dir"
                        />

                        <ul
                            v-if="form.errors?.dir"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.dir }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Correo electrónico</label>
                        <input
                            type="email"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.correo,
                            }"
                            v-model="form.correo"
                        />

                        <ul
                            v-if="form.errors?.correo"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.correo }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Teléfono/Celular*</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.fono,
                            }"
                            v-model="form.fono"
                        />

                        <ul
                            v-if="form.errors?.fono"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.fono }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Tipo de Usuario*</label>
                        <select
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.tipo,
                            }"
                            v-model="form.tipo"
                        >
                            <option value="">- Seleccione -</option>
                            <option
                                v-for="item in listTipos"
                                :value="item.value"
                            >
                                {{ item.label }}
                            </option>
                        </select>

                        <ul
                            v-if="form.errors?.tipo"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.tipo }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Foto</label>
                        <input
                            type="file"
                            class="form-control"
                            :class="{
                                'parsley-error': form.errors?.foto,
                            }"
                            ref="foto"
                            @change="cargaArchivo($event, 'foto')"
                        />

                        <ul
                            v-if="form.errors?.foto"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.foto }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="flexSwitchCheckChecked">Acceso*</label
                        ><br />
                        <el-switch
                            size="large"
                            v-model="form.acceso"
                            class="mb-2"
                            style="
                                --el-switch-on-color: #13ce66;
                                --el-switch-off-color: #ff4949;
                            "
                            active-text="Habilitado"
                            inactive-text="Deshabilitado"
                            active-value="1"
                            inactive-value="0"
                        />
                        <ul
                            v-if="form.errors?.acceso"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.acceso }}
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-default"
                @click.prevent="cerrarFormulario()"
            >
                Cerrar
            </button>
            <button
                type="button"
                class="btn btn-primary"
                :disabled="enviando"
                @click.prevent="enviarFormulario"
                v-html="textBtn"
            ></button>
        </template>
    </MiModal>
</template>
