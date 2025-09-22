<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\IngresoProductoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoServicioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SalidaProductoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\UrbanizacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
});

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    return 'Cache eliminado <a href="/">Ir al inicio</a>';
})->name('clear.cache');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("login");

Route::post('/registro/validaForm1', [RegisteredUserController::class, 'validaForm1'])->name("registro.validaForm1");
Route::get('/registro', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Register');
})->name("registro");

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

// PORTAL

// ADMINISTRACION
Route::middleware(['auth', 'permisoUsuario'])->prefix("admin")->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');

    // CONFIGURACION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisosUsuario", [UserController::class, 'permisosUsuario']);


    // PERSONAS
    Route::get("personas/paginado", [PersonaController::class, 'paginado'])->name("personas.paginado");
    Route::get("personas/listado", [PersonaController::class, 'listado'])->name("personas.listado");
    Route::get("personas/eliminados", [PersonaController::class, 'eliminados'])->name("personas.eliminados");
    Route::get("personas/paginado_eliminados", [PersonaController::class, 'paginado_eliminados'])->name("personas.paginado_eliminados");
    Route::patch("personas/reestablecer/{persona}", [PersonaController::class, 'reestablecer'])->name("personas.reestablecer");
    Route::delete("personas/eliminacion_permanente/{persona}", [PersonaController::class, 'eliminacion_permanente'])->name("personas.eliminacion_permanente");
    Route::resource("personas", PersonaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // USUARIOS

    Route::get("usuarios/eliminados", [UsuarioController::class, 'eliminados'])->name("usuarios.eliminados");
    Route::get("usuarios/paginado_eliminados", [UsuarioController::class, 'paginado_eliminados'])->name("usuarios.paginado_eliminados");
    Route::patch("usuarios/reestablecer/{user}", [UsuarioController::class, 'reestablecer'])->name("usuarios.reestablecer");
    Route::delete("usuarios/eliminacion_permanente/{user}", [UsuarioController::class, 'eliminacion_permanente'])->name("usuarios.eliminacion_permanente");
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/api", [UsuarioController::class, 'api'])->name("usuarios.api");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // CLIENTES
    Route::get("clientes/api", [ClienteController::class, 'api'])->name("clientes.api");
    Route::get("clientes/paginado", [ClienteController::class, 'paginado'])->name("clientes.paginado");
    Route::get("clientes/listado", [ClienteController::class, 'listado'])->name("clientes.listado");
    Route::resource("clientes", ClienteController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // PRODUCTO SERVICIOS
    Route::get("producto_servicios/api", [ProductoServicioController::class, 'api'])->name("producto_servicios.api");
    Route::get("producto_servicios/paginado", [ProductoServicioController::class, 'paginado'])->name("producto_servicios.paginado");
    Route::get("producto_servicios/listado", [ProductoServicioController::class, 'listado'])->name("producto_servicios.listado");
    Route::resource("producto_servicios", ProductoServicioController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // PROVEEDORS
    Route::get("proveedors/api", [ProveedorController::class, 'api'])->name("proveedors.api");
    Route::get("proveedors/paginado", [ProveedorController::class, 'paginado'])->name("proveedors.paginado");
    Route::get("proveedors/listado", [ProveedorController::class, 'listado'])->name("proveedors.listado");
    Route::resource("proveedors", ProveedorController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // CAJAS
    Route::get("cajas/api", [ProveedorController::class, 'api'])->name("cajas.api");
    Route::get("cajas/paginado", [ProveedorController::class, 'paginado'])->name("cajas.paginado");
    Route::get("cajas/listado", [ProveedorController::class, 'listado'])->name("cajas.listado");
    Route::resource("cajas", ProveedorController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // INGRESO PRODUCTOS
    Route::get("ingreso_productos/api", [IngresoProductoController::class, 'api'])->name("ingreso_productos.api");
    Route::get("ingreso_productos/paginado", [IngresoProductoController::class, 'paginado'])->name("ingreso_productos.paginado");
    Route::get("ingreso_productos/listado", [IngresoProductoController::class, 'listado'])->name("ingreso_productos.listado");
    Route::resource("ingreso_productos", IngresoProductoController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // SALIDA PRODUCTOS
    Route::get("salida_productos/api", [SalidaProductoController::class, 'api'])->name("salida_productos.api");
    Route::get("salida_productos/paginado", [SalidaProductoController::class, 'paginado'])->name("salida_productos.paginado");
    Route::get("salida_productos/listado", [SalidaProductoController::class, 'listado'])->name("salida_productos.listado");
    Route::resource("salida_productos", SalidaProductoController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // VENTAS
    Route::get("ventas/api", [VentaController::class, 'api'])->name("ventas.api");
    Route::get("ventas/paginado", [VentaController::class, 'paginado'])->name("ventas.paginado");
    Route::get("ventas/listado", [VentaController::class, 'listado'])->name("ventas.listado");
    Route::resource("ventas", VentaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // DISCIPLINAS
    Route::get("disciplinas/api", [DisciplinaController::class, 'api'])->name("disciplinas.api");
    Route::get("disciplinas/paginado", [DisciplinaController::class, 'paginado'])->name("disciplinas.paginado");
    Route::get("disciplinas/listado", [DisciplinaController::class, 'listado'])->name("disciplinas.listado");
    Route::resource("disciplinas", DisciplinaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // INSCRIPCIONES
    Route::get("inscripcions/api", [InscripcionController::class, 'api'])->name("inscripcions.api");
    Route::get("inscripcions/paginado", [InscripcionController::class, 'paginado'])->name("inscripcions.paginado");
    Route::get("inscripcions/listado", [InscripcionController::class, 'listado'])->name("inscripcions.listado");
    Route::resource("inscripcions", InscripcionController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");
});
require __DIR__ . '/auth.php';
