<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPasswordRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UsuarioController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/Usuarios/Index");
    }

    public function clientes(): InertiaResponse
    {
        return Inertia::render("Admin/Usuarios/Clientes");
    }

    public function listado(Request $request): JsonResponse
    {
        $usuarios = User::where("id", "!=", 1);

        if (isset($request->tipo) && $request->tipo) {
            $usuarios->where("tipo", $request->tipo);
        }

        $usuarios = $usuarios->where("status", 1)->get();
        return response()->JSON([
            "usuarios" => $usuarios
        ]);
    }

    public function byTipo(Request $request)
    {
        $usuarios = User::where("id", "!=", 1);
        if (isset($request->tipo) && trim($request->tipo) != "") {
            $usuarios = $usuarios->where("tipo", $request->tipo);
        }

        if ($request->order && $request->order == "desc") {
            $usuarios->orderBy("users.id", "desc");
        }

        $usuarios = $usuarios->where("status", 1)->get();

        return response()->JSON([
            "usuarios" => $usuarios
        ]);
    }
    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = User::where("id", "!=", 1);

        if (trim($search) != "") {
            $usuarios->where("nombre", "LIKE", "%$search%");
            $usuarios->orWhere("paterno", "LIKE", "%$search%");
            $usuarios->orWhere("materno", "LIKE", "%$search%");
            $usuarios->orWhere("ci", "LIKE", "%$search%");
        }

        if ($request->orderBy && $request->orderAsc) {
            $usuarios->orderBy($request->orderBy, $request->orderAsc);
        }

        $usuarios = $usuarios->where("status", 1)->paginate($request->perPage);
        return response()->JSON([
            'data' => $usuarios->items(),
            'total' => $usuarios->total(),
            'lastPage' => $usuarios->lastPage(),
        ]);
    }

    /**
     * Store user
     *
     * @param UserStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(UserStoreRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->userService->crear($request->validated());
            DB::commit();
            return redirect()->route("usuarios.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(User $user)
    {
        return response()->JSON($user);
    }

    public function actualizaAcceso(User $user, Request $request)
    {
        $user->acceso = $request->acceso;
        $user->save();
        return response()->JSON([
            "user" => $user,
            "message" => "Acceso actualizado"
        ]);
    }

    /**
     * Update user
     *
     * @param User $user
     * @param UserUpdateRequest $request
     * @return RedirectResponse|Response
     */
    public function update(User $user, UserUpdateRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->userService->actualizar($request->validated(), $user);
            DB::commit();
            return redirect()->route("usuarios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function actualizaPassword(User $user, UserPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->userService->actualizarPassword($request->validated(), $user);
            DB::commit();
            return redirect()->route("usuarios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete user
     *
     * @param User $user
     * @return JsonResponse|Response
     */
    public function destroy(User $user): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->userService->eliminar($user);
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
}
