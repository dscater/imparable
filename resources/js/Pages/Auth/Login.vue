<script>
export default {
    layout: Login,
};
</script>

<script setup>
import Login from "@/Layouts/Login.vue";
import { onMounted } from "vue";
import { useForm, Head, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { useConfiguracionStore } from "@/stores/configuracion/configuracionStore";
const configuracionStore = useConfiguracionStore();

const { url_assets } = usePage().props;

const form = useForm({
    usuario: "",
    password: "",
});

const submit = () => {
    form.post(route("login"), {
        onError: (err) => {
            if (err.acceso) {
                Swal.fire({
                    icon: "info",
                    title: "Error",
                    text: `${err.acceso}`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
            }
        },
        onFinish: () => form.reset("password"),
    });
};

const url_asset = ref("/");

onMounted(() => {
    configuracionStore.initConfiguracion();
    url_asset.value = url_assets;
});
</script>

<template>
    <Head title="Login"></Head>
    <div class="row login">
        <div class="col-md-6 p-0">
            <img
                :src="configuracionStore.oConfiguracion.url_logo"
                alt="Logo"
                class="w-100 d-none d-md-block"
            />
        </div>
        <div class="col-md-6 bg__primary2 p-5">
            <a
                href="/"
                class="h1 w-100 text-center d-block nombre_sistema title_Chau_Philomene_One"
                >{{ configuracionStore.oConfiguracion.nombre_sistema }}</a
            >

            <form @submit.prevent="submit">
                <p class="login-box-msg">Ingresa tú usuario y contraseña</p>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Usuario"
                        v-model="form.usuario"
                        autofocus
                    />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input
                        type="password"
                        class="form-control"
                        placeholder="Contraseña"
                        v-model="form.password"
                        autocomplete="false"
                    />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button
                            type="submit"
                            class="btn btn-primary btn-block"
                            :disabled="form.processing"
                        >
                            Acceder
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.row.login img {
    height: 100%;
    object-fit: cover;
}

.nombre_sistema:hover {
    color: var(--text-white1);
}
</style>
