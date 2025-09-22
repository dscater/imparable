<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermisoService
{
    protected $arrayPermisos = [
        "ADMINISTRADOR" => [
            "usuarios.paginado",
            "usuarios.index",
            "usuarios.listado",
            "usuarios.create",
            "usuarios.store",
            "usuarios.edit",
            "usuarios.show",
            "usuarios.update",
            "usuarios.destroy",
            "usuarios.password",
            "usuarios.eliminados",
            "usuarios.paginado_eliminados",
            "usuarios.reestablecer",
            "usuarios.eliminacion_permanente",

            "personas.listado",
            "personas.paginado",
            "personas.index",
            "personas.create",
            "personas.store",
            "personas.edit",
            "personas.show",
            "personas.update",
            "personas.destroy",
            "personas.eliminados",
            "personas.paginado_eliminados",
            "personas.reestablecer",
            "personas.eliminacion_permanente",

            "clientes.paginado",
            "clientes.listado",
            "clientes.index",
            "clientes.create",
            "clientes.store",
            "clientes.edit",
            "clientes.show",
            "clientes.update",
            "clientes.destroy",

            "producto_servicios.paginado",
            "producto_servicios.listado",
            "producto_servicios.index",
            "producto_servicios.create",
            "producto_servicios.store",
            "producto_servicios.edit",
            "producto_servicios.show",
            "producto_servicios.update",
            "producto_servicios.destroy",

            "proveedors.paginado",
            "proveedors.listado",
            "proveedors.index",
            "proveedors.create",
            "proveedors.store",
            "proveedors.edit",
            "proveedors.show",
            "proveedors.update",
            "proveedors.destroy",

            "ingreso_productos.paginado",
            "ingreso_productos.listado",
            "ingreso_productos.index",
            "ingreso_productos.create",
            "ingreso_productos.store",
            "ingreso_productos.edit",
            "ingreso_productos.show",
            "ingreso_productos.update",
            "ingreso_productos.destroy",

            "salida_productos.paginado",
            "salida_productos.listado",
            "salida_productos.index",
            "salida_productos.create",
            "salida_productos.store",
            "salida_productos.edit",
            "salida_productos.show",
            "salida_productos.update",
            "salida_productos.destroy",

            "ventas.paginado",
            "ventas.listado",
            "ventas.index",
            "ventas.create",
            "ventas.store",
            "ventas.edit",
            "ventas.show",
            "ventas.update",
            "ventas.destroy",

            "disciplinas.paginado",
            "disciplinas.listado",
            "disciplinas.index",
            "disciplinas.create",
            "disciplinas.store",
            "disciplinas.edit",
            "disciplinas.show",
            "disciplinas.update",
            "disciplinas.destroy",

            "inscripcions.paginado",
            "inscripcions.listado",
            "inscripcions.index",
            "inscripcions.create",
            "inscripcions.store",
            "inscripcions.edit",
            "inscripcions.show",
            "inscripcions.update",
            "inscripcions.destroy",

            "cajas.paginado",
            "cajas.listado",
            "cajas.index",
            "cajas.create",
            "cajas.store",
            "cajas.edit",
            "cajas.show",
            "cajas.update",
            "cajas.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.update",
            "configuracions.destroy",

            "reportes.usuarios",
            "reportes.r_usuarios",
        ],
        "GERENTE" => [
            "reportes.tareas",
            "reportes.r_tareas",
        ],
        "EMPLEADO" => [],
    ];

    public function getPermisosUser()
    {
        $user = Auth::user();
        $permisos = [];
        if ($user) {
            return $this->arrayPermisos[$user->tipo];
        }

        return $permisos;
    }
}
