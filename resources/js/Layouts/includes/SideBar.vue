<script setup>
import { onMounted, onUnmounted, ref, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import ItemMenu from "@/Components/ItemMenu.vue";
import { useSideBar } from "@/composables/useSidebar.js";
import { useAppStore } from "@/stores/aplicacion/appStore";
import { useConfiguracionStore } from "@/stores/configuracion/configuracionStore";
const { closeSidebar, toggleSubMenuELem } = useSideBar();
const configuracionStore = useConfiguracionStore();
const appStore = useAppStore();
const usuario = ref(null);
const toggleSubMenu = (e) => {
    e.stopPropagation();
    const elem = e.currentTarget;
    if (
        elem.classList.contains("menu-is-opening") &&
        elem.classList.contains("menu-open")
    ) {
        elem.classList.remove("menu-is-opening");
        elem.classList.remove("menu-open");
        toggleSubMenuELem(elem, false);
    } else {
        elem.classList.add("menu-is-opening");
        elem.classList.add("menu-open");
        toggleSubMenuELem(elem, true);
    }
};

const route_current = ref("");
router.on("navigate", (event) => {
    route_current.value = route().current();
    closeSidebar();
});

onMounted(() => {
    configuracionStore.initConfiguracion();
    usuario.value = appStore.getUsuario;
    // Selecciona el elemento del widget
    var sidebarSearchElement = $('[data-widget="sidebar-search"]');
    // Configura manualmente el texto de "no encontrado"
    sidebarSearchElement.data("notFoundText", "Sin resultados");
});

onUnmounted(() => {});
</script>
<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img
                :src="configuracionStore.oConfiguracion.url_logo"
                alt="Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span class="brand-text font-weight-light title_Chau_Philomene_One">{{
                configuracionStore.oConfiguracion.nombre_sistema
            }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar p-0">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="usuario?.url_foto"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ usuario?.full_name }}</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input
                        class="form-control form-control-sidebar"
                        type="search"
                        placeholder="Buscar"
                        aria-label="Buscar"
                    />
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <ItemMenu
                        :label="'Inicio'"
                        :ruta="'inicio'"
                        :icon="'fa fa-home'"
                    ></ItemMenu>
                    <li class="nav-header font-weight-bold bg-navy ">
                        OPERACIONES
                    </li>
                    <ItemMenu
                        :label="'Inscripciones'"
                        :ruta="'inscripcions.index'"
                        :icon="'fa fa-clipboard-list'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Ventas'"
                        :ruta="'ventas.index'"
                        :icon="'fa fa-list'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Caja'"
                        :ruta="'cajas.index'"
                        :icon="'fa fa-cash-register'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Ingreso de Productos'"
                        :ruta="'inscripcions.index'"
                        :icon="'fa fa-sign-in-alt'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Salida de Productos'"
                        :ruta="'inscripcions.index'"
                        :icon="'fa fa-sign-out-alt'"
                    ></ItemMenu>
                    <li class="nav-header font-weight-bold bg-navy">
                        ADMINISTRACIÓN
                    </li>
                    <ItemMenu
                        :label="'Personas'"
                        :ruta="'personas.index'"
                        :icon="'fa fa-clipboard-list'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Clientes'"
                        :ruta="'clientes.index'"
                        :icon="'fa fa-user-friends'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Usuarios'"
                        :ruta="'usuarios.index'"
                        :icon="'fa fa-users'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Productos y servicios'"
                        :ruta="'producto_servicios.index'"
                        :icon="'fa fa-table'"
                    ></ItemMenu>
                    <li class="nav-header font-weight-bold bg-navy">
                        REPORTES
                    </li>
                    <li class="nav-item">
                        <a
                            href="#"
                            class="nav-link sub-menu"
                            :class="[
                                route_current == 'reportes.usuarios' ||
                                route_current == 'reportes.pagos'
                                    ? 'active menu-is-opening menu-open'
                                    : '',
                            ]"
                            @click.stop="toggleSubMenu($event)"
                        >
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Reportes
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <ItemMenu
                                :label="'Usuarios'"
                                :ruta="'reportes.usuarios'"
                                :icon="'fa fa-angle-right'"
                            ></ItemMenu>
                        </ul>
                    </li>
                    <li class="nav-header font-weight-bold bg-navy">OTROS</li>
                    <ItemMenu
                        :label="'Disciplinas'"
                        :ruta="'disciplinas.index'"
                        :icon="'fa fa-clipboard-list'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Configuración Sistema'"
                        :ruta="'configuracions.index'"
                        :icon="'fa fa-cog'"
                    ></ItemMenu>
                    <ItemMenu
                        :label="'Salir'"
                        :ruta="'logout'"
                        :method="'POST'"
                        :icon="'fa fa-power-off'"
                    ></ItemMenu>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>
<style scoped></style>
