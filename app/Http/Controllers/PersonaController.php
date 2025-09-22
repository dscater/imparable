<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonaStoreRequest;
use App\Http\Requests\PersonaUpdateRequest;
use App\Models\Persona;
use App\Services\PersonaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PersonaController extends Controller
{
    public function __construct(private PersonaService $personaService) {}

    /**
     * Página index
     *
     * @return Response
     */
    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/Personas/Index");
    }

    /**
     * Listado de personas
     *
     * @return JsonResponse
     */
    public function listado(Request $request): JsonResponse
    {
        $search = (string)$request->input("search", "");
        return response()->JSON([
            "personas" => $this->personaService->listado($search)
        ]);
    }


    /**
     * Listado Páginado de personas
     *
     * @param Request $request
     * @return void
     */
    public function paginado(Request $request): JsonResponse
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderByCol;
        $desc = $request->desc;

        $columnsSerachLike = ["nombre", "paterno", "materno", "ci", "fono", "dir"];
        $columnsFilter = [];
        $columnsBetweenFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        $personas = $this->personaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $personas->items(),
            "total" => $personas->total(),
            "lastPage" => $personas->lastPage()
        ]);
    }

    /**
     * Registrar un nuevo persona
     *
     * @param PersonaStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(PersonaStoreRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            // crear el Persona
            $this->personaService->crear($request->validated());
            DB::commit();
            return redirect()->route("personas.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Mostrar un persona
     *
     * @param Persona $persona
     * @return JsonResponse
     */
    public function show(Persona $persona): JsonResponse
    {
        return response()->JSON($persona->load(["area", "producto", "supervisor", "persona_materials.material", "persona_operarios.user"]));
    }

    /**
     * Editar una persona
     *
     * @param Persona $persona
     * @return JsonResponse
     */
    public function update(Persona $persona, PersonaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar persona
            $this->personaService->actualizar($request->validated(), $persona);
            DB::commit();
            return redirect()->route("personas.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Eliminar persona
     *
     * @param Persona $persona
     * @return JsonResponse|Response
     */
    public function destroy(Persona $persona): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->personaService->eliminar($persona);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Página eliminados
     *
     * @return Response
     */
    public function eliminados(): InertiaResponse
    {
        return Inertia::render("Admin/Personas/Eliminados");
    }

    /**
     * Listado Páginado de personas eliminados
     *
     * @param Request $request
     * @return void
     */
    public function paginado_eliminados(Request $request): JsonResponse
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderByCol;
        $desc = $request->desc;

        $columnsSerachLike = ["nombre", "paterno", "materno", "ci", "fono", "dir"];
        $columnsFilter = [];
        $columnsBetweenFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        $personas = $this->personaService->listadoPaginadoEliminados($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $personas->items(),
            "total" => $personas->total(),
            "lastPage" => $personas->lastPage()
        ]);
    }

    /**
     * Reestablecer una persona
     *
     * @param Persona $persona
     * @return JsonResponse
     */
    public function reestablecer(Persona $persona)
    {
        DB::beginTransaction();
        try {
            $this->personaService->reestablecer($persona);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se reestableció correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Eliminación permanente de una persona
     *
     * @param Persona $persona
     * @return JsonResponse
     */
    public function eliminacion_permanente(Persona $persona)
    {
        DB::beginTransaction();
        try {
            $this->personaService->eliminacion_permanente($persona);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó permanentemente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
