<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Configuracion;
use App\Models\ConfiguracionMaterial;
use App\Models\ConfiguracionOperario;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConfiguracionService
{
    private $modulo = "CONFIGURACIÓN SISTEMA";

    public function __construct(private HistorialAccionService $historialAccionService, private CargarArchivoService $cargarArchivoService) {}

    /**
     * Actualizar configuracion
     *
     * @param array $datos
     * @param Configuracion $configuracion
     * @return Configuracion
     */
    public function actualizar(array $datos, Configuracion $configuracion): Configuracion
    {
        $old_area = clone $configuracion;

        $configuracion->update([
            "nombre_sistema" => mb_strtoupper($datos["nombre_sistema"]),
            "razon_social" => mb_strtoupper($datos["razon_social"]),
            "alias" => mb_strtoupper($datos["alias"]),
        ]);

        // cargar logo
        if ($datos["logo"] && !is_string($datos["logo"])) {
            $this->cargarLogo($configuracion, $datos["logo"]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ LA CONFIGURACIÓN DEL SISTEMA", $old_area, $configuracion);

        return $configuracion;
    }

    public function cargarLogo(Configuracion $configuracion, UploadedFile $logo): void
    {
        if ($configuracion->logo) {
            \File::delete(public_path("imgs/" . $this->configuracion->logo));
        }
        $nombre = $configuracion->id . time();
        $configuracion->logo = $this->cargarArchivoService->cargarArchivo($logo, public_path("imgs"), $nombre);
        $configuracion->save();
    }
}
