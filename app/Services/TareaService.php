<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Tarea;
use App\Models\TareaMaterial;
use App\Models\TareaOperario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TareaService
{
    private $modulo = "TAREAS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $tareas = Tarea::select("tareas.*")
            ->join("tarea_operarios", "tarea_operarios.tarea_id", "=", "tareas.id");

        if (Auth::user()->tipo == 'OPERARIOS') {
            $tareas->where("tarea_operarios.user_id", Auth::user()->id);
        }
        $tareas->distinct("tareas.id");
        $tareas->groupBy("tareas.id");
        $tareas = $tareas->get();
        return $tareas;
    }

    public function listadoDataTable(int $length, int $start, int $page, string $search): LengthAwarePaginator
    {
        $tareas = Tarea::with(["area", "producto", "supervisor", "tarea_materials", "tarea_operarios"])->select("tareas.*")
            ->join("tarea_operarios", "tarea_operarios.tarea_id", "=", "tareas.id");
        if ($search && trim($search) != '') {
            $tareas->where("nombre", "LIKE", "%$search%");
        }
        if (Auth::user()->tipo == 'OPERARIOS') {
            $tareas->where("tarea_operarios.user_id", Auth::user()->id);
        }
        $tareas->distinct("tareas.id");
        $tareas->groupBy("tareas.id");
        $tareas = $tareas->paginate($length, ['*'], 'page', $page);
        return $tareas;
    }

    /**
     * Crear tarea
     *
     * @param array $datos
     * @return Tarea
     */
    public function crear(array $datos): Tarea
    {
        $user = Auth::user();
        $tipo = $user->tipo;
        $acodigo = $this->generarCodigoTarea();
        $tarea = Tarea::create([
            "codigo" => $acodigo[1],
            "nro_cod" => $acodigo[0],
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "area_id" => $datos["area_id"],
            "producto_id" => $datos["producto_id"],
            "user_id" => $datos["user_id"],
            "estado" => $tipo == 'SUPERVISOR' ? $datos["estado"] : 'PENDIENTE',
            "fecha_registro" => date("Y-m-d")
        ]);

        foreach ($datos["tarea_materials"] as $item) {
            $tarea->tarea_materials()->create([
                "material_id" => $item["material_id"]
            ]);
        }

        foreach ($datos["tarea_operarios"] as $item) {
            $tarea->tarea_operarios()->create([
                "user_id" => $item["user_id"]
            ]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA TAREA", $tarea, null, ["tarea_materials", "tarea_operarios"]);

        return $tarea;
    }

    /**
     * Actualizar tarea
     *
     * @param array $datos
     * @param Tarea $tarea
     * @return Tarea
     */
    public function actualizar(array $datos, Tarea $tarea): Tarea
    {
        $old_area = clone $tarea;
        $old_area->loadMissing(["tarea_materials", "tarea_operarios"]);

        $user = Auth::user();
        $tipo = $user->tipo;

        $tarea->update([
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "area_id" => $datos["area_id"],
            "producto_id" => $datos["producto_id"],
            "user_id" => $datos["user_id"],
        ]);
        if ($tipo == 'SUPERVISOR') {
            $tarea->estado = $datos["estado"];
            $tarea->save();
        }

        if (isset($datos["eliminados_materials"]) && $datos["eliminados_materials"]) {
            foreach ($datos["eliminados_materials"] as $id) {
                $tarea_material = TareaMaterial::findOrFail($id);
                $tarea_material->delete();
            }
        }


        if (isset($datos["eliminados_operarios"]) && $datos["eliminados_operarios"]) {
            foreach ($datos["eliminados_operarios"] as $id) {
                $tarea_operario = TareaOperario::findOrFail($id);
                $tarea_operario->delete();
            }
        }

        foreach ($datos["tarea_materials"] as $item) {
            if ($item["id"] != 0) {
                $tarea_material = TareaMaterial::findOrFail($item["id"]);
                $tarea_material->update(["material_id" => $item["material_id"]]);
            } else {
                $tarea->tarea_materials()->create([
                    "material_id" => $item["material_id"]
                ]);
            }
        }

        foreach ($datos["tarea_operarios"] as $item) {
            if ($item["id"] != 0) {
                $tarea_operario = TareaOperario::findOrFail($item["id"]);
                $tarea_operario->update(["user_id" => $item["user_id"]]);
            } else {
                $tarea->tarea_operarios()->create([
                    "user_id" => $item["user_id"]
                ]);
            }
        }



        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA TAREA", $old_area, $tarea, ["tarea_materials", "tarea_operarios"]);

        return $tarea;
    }

    /**
     * Eliminar tarea
     *
     * @param Tarea $tarea
     * @return boolean
     */
    public function eliminar(Tarea $tarea): bool
    {
        // verificar usos
        $usos = Tarea::where("area_id", $tarea->id)->get();
        if (count($usos) > 0) {
            throw ValidationException::withMessages([
                'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
            ]);
        }
        $old_area = clone $tarea;
        $old_area->loadMissing(["tarea_materials", "tarea_operarios"]);
        $tarea->tarea_materials()->delete();
        $tarea->tarea_operarios()->delete();
        $tarea->delete();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA TAREA", $old_area);

        return true;
    }
}
