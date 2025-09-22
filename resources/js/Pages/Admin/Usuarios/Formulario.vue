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
            console.log(oUsuario.value)
            console.log("AAAAAAAAAAAAA")
            options.value = [
                {
                    value: oUsuario.value.persona_id,
                    label: oUsuario.value.persona
                        ? `${oUsuario.value.persona.full_name} - ${oUsuario.value.persona.ci}`
                        : "Cargando...",
                },
            ];
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
            console.log(form.errors);
            if (form.errors) {
                const error =
                    "Existen errores en el formulario, por favor verifique";
                Swal.fire({
                    icon: "info",
                    title: "Error",
                    html: `<strong>${error}</strong>`,
                    confirmButtonText: `Aceptar`,
                    customClass: {
                        confirmButton: "btn-error",
                    },
                });
            } else {
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
            }
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

const cerrarFormulario = () => {
    muestra_form.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
};

const cargarListas = () => {};

const options = ref([]);
const loading = ref(false);
const remoteMethod = async (query) => {
    if (query !== "") {
        loading.value = true;
        try {
            const response = await axios.get(
                route("personas.listado") +
                    `?search=${encodeURIComponent(query)}`
            );
            const data = response.data.personas;
            // Suponiendo que data es un array de personas [{id, nombre}]
            options.value = data.map((persona) => ({
                value: persona.id,
                label: `${persona.full_name} - ${persona.ci}`,
            }));
        } catch (error) {
            options.value = [];
        }
        loading.value = false;
    } else {
        options.value = [];
    }
};
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
                <p class="text-muted text-xs mb-0">
                    Todos los campos con
                    <span class="text-danger">(*)</span> son obligatorios.
                </p>
                <div class="row">
                    <div class="col-md-4 mt-2">
                        <label class="required"
                            ><i class="fa fa-search"></i> Buscar Persona
                        </label>
                        <el-select-v2
                            v-model="form.persona_id"
                            style="width: 240px"
                            filterable
                            remote
                            :remote-method="remoteMethod"
                            clearable
                            :options="options"
                            :loading="loading"
                            placeholder="Buscar Persona..."
                            size="large"
                            no-data-text="Sin resultados"
                            loading-text="Buscando..."
                            :class="{
                                'is-invalid': form.errors?.persona_id,
                            }"
                            class="w-100"
                        />
                        <ul
                            v-if="form.errors?.persona_id"
                            class="parsley-errors-list filled"
                        >
                            <li class="parsley-required">
                                {{ form.errors?.persona_id }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="required">Tipo de Usuario</label>
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
                        <label class="required">Acceso</label><br />
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
