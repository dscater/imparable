import { onMounted, reactive } from "vue";

const oTarea = reactive({
    id: 0,
    codigo: "",
    nro_cod: "",
    descripcion: "",
    area_id: "",
    producto_id: "",
    user_id: "",
    estado: "PENDIENTE",
    tarea_materials: [],
    tarea_operarios: [],
    eliminados_materials: [],
    eliminados_operarios: [],
    _method: "POST",
});

export const useTareas = () => {
    const setTarea = (item = null, ver = false) => {
        if (item) {
            oTarea.id = item.id;
            oTarea.codigo = item.codigo;
            oTarea.nro_cod = item.nro_cod;
            oTarea.descripcion = item.descripcion;
            oTarea.area_id = item.area_id;
            oTarea.producto_id = item.producto_id;
            oTarea.user_id = item.user_id;
            oTarea.estado = item.estado;
            oTarea.tarea_materials = item.tarea_materials;
            oTarea.tarea_operarios = item.tarea_operarios;
            oTarea.eliminados_materials = [];
            oTarea.eliminados_operarios = [];

            if (ver) {
                oTarea.area = item.area;
                oTarea.producto = item.producto;
                oTarea.supervisor = item.supervisor;
            }
            oTarea._method = "PUT";
            return oTarea;
        }
        return false;
    };

    const limpiarTarea = () => {
        oTarea.id = 0;
        oTarea.codigo = "";
        oTarea.nro_cod = "";
        oTarea.descripcion = "";
        oTarea.area_id = "";
        oTarea.producto_id = "";
        oTarea.user_id = "";
        oTarea.estado = "PENDIENTE";
        oTarea.tarea_materials = [];
        oTarea.tarea_operarios = [];
        oTarea.eliminados_materials = [];
        oTarea.eliminados_operarios = [];
        oTarea._method = "POST";
    };

    onMounted(() => {});

    return {
        oTarea,
        setTarea,
        limpiarTarea,
    };
};
