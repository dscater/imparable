<script setup>
import App from "@/Layouts/App.vue";
defineOptions({
    layout: App,
});
import Content from "@/Components/Content.vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { onMounted, onBeforeMount, ref, computed } from "vue";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
import accessibility from "highcharts/modules/accessibility";
import { useAppStore } from "@/stores/aplicacion/appStore";
import axios from "axios";
const { auth } = usePage().props;
const user = ref(auth.user);

exporting(Highcharts);
accessibility(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});

const props_page = defineProps({
    array_infos: {
        type: Array,
    },
});

const appStore = useAppStore();
onBeforeMount(() => {
    appStore.startLoading();
});

const { props } = usePage();

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Grafico...";
    }
    return "Generar Grafico";
});

const obtenerFechaActual = () => {
    const fecha = new Date();
    const anio = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Mes empieza desde 0
    const dia = String(fecha.getDate()).padStart(2, "0"); // Día del mes
    return `${anio}-${mes}-${dia}`;
};

const form = ref({
    estado: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const listEstados = ref([
    { value: "todos", label: "TODOS" },
    { value: "PENDIENTE", label: "PENDIENTE" },
    { value: "INICIADO", label: "INICIADO" },
    { value: "FINALIZADO", label: "FINALIZADO" },
]);

// const graficoResumen = () => {
//     Highcharts.chart("graficoResumen", {
//         chart: {
//             type: "column",
//         },
//         title: {
//             align: "center",
//             text: `RESUMEN COSTOS-SALDOS`,
//         },
//         subtitle: {
//             align: "center",
//             text: ``,
//         },
//         accessibility: {
//             announceNewData: {
//                 enabled: true,
//             },
//         },
//         xAxis: {
//             type: "category",
//         },
//         yAxis: {
//             title: {
//                 text: "Monto de la Oferta/Puja",
//             },
//         },
//         legend: {
//             enabled: true,
//         },
//         plotOptions: {
//             series: {
//                 borderWidth: 0,
//                 dataLabels: {
//                     enabled: true,
//                     // format: "{point.y}",
//                     style: {
//                         fontSize: "11px",
//                         fontWeight: "bold",
//                     },
//                     formatter: function () {
//                         return getFormatoMoneda(this.point.y); // Aquí se aplica el formato de moneda
//                     },
//                 },
//             },
//         },
//         tooltip: {
//             formatter: function () {
//                 return `<span style="font-size:11px;color:${
//                     this.point.color
//                 }" class="font-weight-bold">${
//                     this.point.name
//                 }</span><br/><div style="display:block;width:100%;" class="text-md text-center">${getFormatoMoneda(
//                     this.point.y
//                 )}</div>`;
//             },
//         },
//         series: [
//             {
//                 name: "TOTALES",
//                 data: [
//                     {
//                         name: "CANCELADO",
//                         y: parseFloat(props.total_cancelado),
//                         color: "#67c23a",
//                     },
//                     {
//                         name: "SALDO ENVIADOS/CONCLUIDOS",
//                         y: parseFloat(props.total_saldo_enviando),
//                         color: "#f56c6c",
//                     },
//                     {
//                         name: "SALDO TOTAL",
//                         y: parseFloat(props.total_saldo),
//                         color: "#e6a23c",
//                     },
//                     {
//                         name: "TOTAL",
//                         y: parseFloat(props.costo_total),
//                         color: "#409eff",
//                     },
//                 ],
//             },
//         ],
//     });
// };

// const filtroGPagos = ref({
//     filtro: "poranio",
//     anio: getAnioActual(),
// });

// const graficoPagos = () => {
//     axios
//         .get(route("entrenamientos.graficoPagos"), {
//             params: filtroGPagos.value,
//         })
//         .then((response) => {
//             Highcharts.chart("graficoPagos", {
//                 chart: {
//                     type: "line",
//                 },
//                 title: {
//                     align: "center",
//                     text: `PAGOS`,
//                 },
//                 subtitle: {
//                     align: "center",
//                     text: ``,
//                 },
//                 accessibility: {
//                     announceNewData: {
//                         enabled: true,
//                     },
//                 },
//                 xAxis: {
//                     categories: response.data.categories,
//                 },
//                 yAxis: {
//                     title: {
//                         text: "Monto de la Oferta/Puja",
//                     },
//                 },
//                 legend: {
//                     enabled: true,
//                 },
//                 plotOptions: {
//                     series: {
//                         borderWidth: 0,
//                         dataLabels: {
//                             enabled: true,
//                             // format: "{point.y}",
//                             style: {
//                                 fontSize: "11px",
//                                 fontWeight: "bold",
//                             },
//                             formatter: function () {
//                                 return getFormatoMoneda(this.point.y); // Aquí se aplica el formato de moneda
//                             },
//                         },
//                     },
//                 },
//                 tooltip: {
//                     formatter: function () {
//                         return `<span style="font-size:11px" class="font-weight-bold">${
//                             this.point.category
//                         }</span><br/><div class="text-md d-block w-100 text-center">${getFormatoMoneda(
//                             this.point.y
//                         )}</div>`;
//                     },
//                 },
//                 series: [
//                     {
//                         name: "TOTALES",
//                         data: response.data.data,
//                     },
//                 ],
//             });
//         });
// };
onMounted(() => {
    appStore.stopLoading();
});
</script>
<template>
    <Head title="Inicio"></Head>
    <Content>
        <template #header>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inicio</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inicio</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </template>

        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body bg__dark5">
                        <p class="h5">CANTIDAD TRABAJOS</p>
                        <p class="font-weight-bold h4">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body bg__dark5">
                        <p class="h5">CANTIDAD CANCELADOS</p>
                        <p class="font-weight-bold h4">0</p>
                    </div>
                </div>
            </div>
        </div>
    </Content>
</template>
<style scoped>
.item_btn {
    margin: 10px;
}

.contenido_item i {
    color: black;
}

.contenido_item {
    transition: all 0.8s;
    color: black;
    padding: 10px;
    cursor: pointer;
    background-color: rgb(248, 229, 229);
    border: solid 2px rgb(243, 211, 211);
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 1.3em;
    flex-direction: column;
}
</style>
