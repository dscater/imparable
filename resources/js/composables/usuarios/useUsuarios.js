import { onMounted, ref } from "vue";

const oUsuario = ref({
    id: 0,
    persona_id: "",
    usuario: "",
    password: "",
    acceso: "" + 0,
    tipo: "",
    foto: "",
    _method: "POST",
});

export const useUsuarios = () => {
    const setUsuario = (item = null, ver = false) => {
        if (item) {
            oUsuario.value.id = item.id;
            oUsuario.value.persona_id = item.persona_id;
            oUsuario.value.usuario = item.usuario;
            oUsuario.value.password = item.password;
            oUsuario.value.acceso = "" + item.acceso;
            oUsuario.value.tipo = item.tipo;
            oUsuario.value.foto = "";
            oUsuario.value.persona = item.persona;
            oUsuario.value._method = "PUT";
            return oUsuario;
        }
        return false;
    };

    const limpiarUsuario = () => {
        oUsuario.value.id = 0;
        oUsuario.value.persona_id = "";
        oUsuario.value.usuario = "";
        oUsuario.value.password = "";
        oUsuario.value.acceso = "" + 0;
        oUsuario.value.tipo = "";
        oUsuario.value.foto = "";
        oUsuario._method = "POST";
    };

    onMounted(() => {});

    return {
        oUsuario,
        setUsuario,
        limpiarUsuario,
    };
};
