import { onMounted, ref } from "vue";

const oPersona = ref({
    id: 0,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    dir: "",
    fono: "",
    fecha_nac: "",
    correo: "",
    _method: "POST",
});

export const usePersonas = () => {
    const setPersona = (item = null, ver = false) => {
        if (item) {
            oPersona.value.id = item.id;
            oPersona.value.nombre = item.nombre;
            oPersona.value.paterno = item.paterno;
            oPersona.value.materno = item.materno;
            oPersona.value.ci = item.ci;
            oPersona.value.ci_exp = item.ci_exp;
            oPersona.value.dir = item.dir;
            oPersona.value.fono = item.fono;
            oPersona.value.fecha_nac = item.fecha_nac;
            oPersona.value.correo = item.correo;
            oPersona.value._method = "PUT";
            return oPersona;
        }
        return false;
    };

    const limpiarPersona = () => {
        oPersona.id = 0;
        oPersona.value.nombre = "";
        oPersona.value.paterno = "";
        oPersona.value.materno = "";
        oPersona.value.ci = "";
        oPersona.value.ci_exp = "";
        oPersona.value.dir = "";
        oPersona.value.fono = "";
        oPersona.value.fecha_nac = "";
        oPersona.value.correo = "";
        oPersona._method = "POST";
    };

    onMounted(() => {});

    return {
        oPersona,
        setPersona,
        limpiarPersona,
    };
};
