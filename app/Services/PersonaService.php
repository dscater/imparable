<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PersonaService
{
    private $modulo = "PERSONAS";
    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}


    public function listado(string $search): array
    {
        return Persona::where("status", 1)
            ->where(function ($query) use ($search) {
                $query->where("ci", "LIKE", "%$search%")
                    ->orWhereRaw("CONCAT(nombre, ' ', paterno, ' ', materno) LIKE ?", ["%$search%"]);
            })
            ->orderBy("nombre")
            ->get()
            ->toArray();
    }

    /**
     * Lista de personas paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginado(int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = []): LengthAwarePaginator
    {
        $personas = Persona::select("personas.*");

        $personas->where("status", 1);

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $personas->where("personas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $personas->whereBetween("personas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $personas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("personas.$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $personas->orderBy($value[0], $value[1]);
            }
        }


        $personas = $personas->paginate($length, ['*'], 'page', $page);
        return $personas;
    }


    /**
     * Lista de personas paginado con filtros (eliminados)
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginadoEliminados(int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = []): LengthAwarePaginator
    {
        $personas = Persona::select("personas.*");

        $personas->where("status", 0);

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $personas->where("personas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $personas->whereBetween("personas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $personas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("personas.$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $personas->orderBy($value[0], $value[1]);
            }
        }


        $personas = $personas->paginate($length, ['*'], 'page', $page);
        return $personas;
    }

    /**
     * Crear persona
     *
     * @param array $datos
     * @return Persona
     */
    public function crear(array $datos): Persona
    {
        $persona = Persona::create([
            "nombre" => mb_strtoupper($datos["nombre"]),
            "paterno" => mb_strtoupper($datos["paterno"]),
            "materno" => mb_strtoupper($datos["materno"]),
            "dir" => mb_strtoupper($datos["dir"]),
            "ci" => $datos["ci"],
            "ci_exp" => $datos["ci_exp"],
            "fono" => $datos["fono"],
            "correo" => $datos["correo"],
            "fecha_registro" => date("Y-m-d")
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA PERSONA", $persona);

        return $persona;
    }

    /**
     * Actualizar persona
     *
     * @param array $datos
     * @param Persona $persona
     * @return Persona
     */
    public function actualizar(array $datos, Persona $persona): Persona
    {
        $old_user = clone $persona;

        $persona->update([
            "nombre" => mb_strtoupper($datos["nombre"]),
            "paterno" => mb_strtoupper($datos["paterno"]),
            "materno" => mb_strtoupper($datos["materno"]),
            "dir" => mb_strtoupper($datos["dir"]),
            "ci" => $datos["ci"],
            "ci_exp" => $datos["ci_exp"],
            "fono" => $datos["fono"],
            "correo" => $datos["correo"],
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ EL REGISTRO DE UNA PERSONA", $old_user, $persona->withoutRelations());

        return $persona;
    }

    /**
     * Eliminar persona
     *
     * @param Persona $persona
     * @return boolean
     */
    public function eliminar(Persona $persona): bool
    {
        // no eliminar users predeterminados para el funcionamiento del sistema
        $old_user = clone $persona;
        $persona->status = 0;
        $persona->save();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ EL REGISTRO DE UNA PERSONA " . $old_user->usuario, $old_user, $persona);
        return true;
    }

    /**
     * Reestablecer persona
     *
     * @param Persona $persona
     * @return boolean
     */
    public function reestablecer(Persona $persona): bool
    {
        $old_persona = clone $persona;
        $persona->status = 1;
        $persona->save();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "REESTABLECER", "REESTABLECIÓ EL REGISTRO DE UNA PERSONA " . $old_persona->usuario, $old_persona, $persona);
        return true;
    }

    /**
     * Eliminación permanente de persona
     *
     * @param Persona $persona
     * @return boolean
     */
    public function eliminacion_permanente(Persona $persona): bool
    {
        $old_persona = clone $persona;
        $persona->delete();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN PERMANENTE", "ELIMINÓ PERMANENTEMENTE EL REGISTRO DE UNA PERSONA " . $old_persona->usuario, $old_persona);
        return true;
    }
}
