import { onMounted, ref } from "vue";

const oArea = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
    _method: "POST",
});

export const useAreas = () => {
    const setArea = (item = null) => {
        if (item) {
            oArea.value.id = item.id;
            oArea.value.nombre = item.nombre;
            oArea.value.descripcion = item.descripcion;
            oArea.value.fecha_registro = item.fecha_registro;
            oArea.value._method = "PUT";
            return oArea;
        }
        return false;
    };

    const limpiarArea = () => {
        oArea.value.id = 0;
        oArea.value.nombre = "";
        oArea.value.descripcion = "";
        oArea.value.fecha_registro = "";
        oArea.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oArea,
        setArea,
        limpiarArea,
    };
};
