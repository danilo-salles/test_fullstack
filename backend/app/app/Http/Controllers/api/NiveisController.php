<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Niveis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NiveisController extends Controller
{
    public function index(Request $request)
    {
        $qtdPorPagina = $request->input('per_page', 10);

        $niveis = Niveis::paginate($qtdPorPagina);

        if ($niveis->isEmpty()) {
            return response()->json(['message' => 'Não há nenhum nível cadastrado'], 404);
        }

        $niveisArray = $niveis->items();
        $niveisArray = collect($niveisArray)->map(function ($nivel) {
            return [
                'id'      => $nivel->id,
                'nivel'   => $nivel->nivel,
            ];
        });

        return response()->json([
            'data' => $niveisArray,
            'meta' => [
                'total'         => $niveis->total(),
                'per_page'      => $niveis->perPage(),
                'current_page'  => $niveis->currentPage(),
                'last_page'     => $niveis->lastPage(),
            ]
        ], 200);
    }

    public function show($id)
    {
        $nivel = Niveis::find($id);
        if (!$nivel) {
            return response()->json(['error' => 'Nível não encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $nivel->id,
                'nivel' => $nivel->nivel
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $valida = Validator::make($request->all(), [
            'nivel' => 'required'
        ]);

        if ($valida->fails()) {
            return response()->json(['error' => $valida->errors()], 400);
        }

        if (Niveis::where('nivel', $request->input('nivel'))->exists()) {
            return response()->json(['error' => 'O nível já existe'], 400);
        }

        $niveis = Niveis::create($request->all());
        $niveis->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Nível criado com sucesso.',
            'data' => $niveis
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $nivel = Niveis::find($id);
        if (!$nivel) {
            return response()->json(['error' => 'Nível não encontrado'], 404);
        }

        $valida = Validator::make($request->all(), [
            'nivel' => 'required'
        ]);

        if ($valida->fails()) {
            return response()->json(['error' => $valida->errors()], 400);
        }

        if ($nivel->nivel === $request->input('nivel')) {
            return response()->json(['message' => 'Nenhuma alteração foi feita.'], 400);
        }

        $nivel->update($request->all());
        $nivel->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Nível atualizado com sucesso.',
            'data' => $nivel
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $niveis = Niveis::findOrFail($id);
            $niveis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Nível deletado com sucesso.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Nível não encontrado para deletar'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao tentar deletar o nível'], 500);
        }
    }
}
