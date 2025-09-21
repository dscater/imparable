<script setup>
import { onMounted, onBeforeUnmount, onBeforeMount, ref } from "vue";
import { useConfiguracionStore } from "@/stores/configuracion/configuracionStore";
const configuracionStore = useConfiguracionStore();
onMounted(() => {
    configuracionStore.initConfiguracion();
    document.getElementsByTagName("body")[0].classList.add("login");
});
onBeforeUnmount(() => {
    document.getElementsByTagName("body")[0].classList.remove("login");
});
</script>
<template>
    <div
        class="content-login container w-100 h-100 d-flex items-center align-items-center justify-content-center"
    >
        <slot />
    </div>
</template>
<style scoped>
.content-login {
    min-height: 100vh;
}
</style>
