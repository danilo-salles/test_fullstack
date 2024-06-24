<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Desenvolvedor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Helpers\IdadeHelper;
use App\Helpers\FormataDataHelper;

class DesenvolvedorController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $desenvolvedores = Desenvolvedor::with('nivel')->paginate($perPage);

        if ($desenvolvedores->isEmpty()) {
            return response()->json(['message' => 'Não há desenvolvedores cadastrados'], 404);
        }

        $arrayDevs = $desenvolvedores->items();

        $arrayDevs = collect($arrayDevs)->map(function ($desenvolvedor) {
            return [
                'id'                => $desenvolvedor->id,
                'nome'              => $desenvolvedor->nome,
                'sexo'              => $desenvolvedor->sexo,
                'data_nascimento'   => FormataDataHelper::ajustaData($desenvolvedor->data_nascimento),
                'idade'             => IdadeHelper::calcularIdade($desenvolvedor->data_nascimento),
                'hobby'             => $desenvolvedor->hobby,
                'nivel'             => [
                    'id'    => $desenvolvedor->nivel->id,
                    'nivel' => $desenvolvedor->nivel->nivel,
                ],
            ];
        });

        return response()->json([
            'data' => $arrayDevs,
            'meta' => [
                'total'         => $desenvolvedores->total(),
                'per_page'      => $desenvolvedores->perPage(),
                'current_page'  => $desenvolvedores->currentPage(),
                'last_page'     => $desenvolvedores->lastPage(),
            ]
        ], 200);
    }

    public function store(Request $request)
    {

        $valida = Validator::make($request->all(), [
            'nivel_id'          => 'required|exists:niveis,id',
            'nome'              => 'required',
            'sexo'              => 'required',
            'data_nascimento'   => 'required|date',
            'hobby'             => 'required',
        ]);

        if ($valida->fails()) {
            return response()->json(['error' => $valida->errors()], 400);
        }

        if (Desenvolvedor::where('nome', $request->input('nome'))
            ->where('sexo', $request->input('sexo'))
            ->where('data_nascimento', $request->input('data_nascimento'))
            ->where('hobby', $request->input('hobby'))
            ->where('nivel_id', $request->input('nivel_id'))
            ->exists()
        ) {
            return response()->json(['error' => 'Desenvolvedor já existe com os mesmos dados'], 400);
        }

        $desenvolvedor = Desenvolvedor::create($request->all());
        $desenvolvedor->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Desenvolvedor criado com sucesso.',
            'data' => $desenvolvedor
        ], 201);
    }

    public function show($id)
    {
        $desenvolvedor = Desenvolvedor::find($id);

        if (!$desenvolvedor) {
            return response()->json(['error' => 'Desenvolvedor não encontrado'], 404);
        }
        $desenvolvedor->makeHidden(['created_at', 'updated_at']);
        return response()->json($desenvolvedor, 200);
    }

    public function update(Request $request, $id)
    {
        $desenvolvedor = Desenvolvedor::find($id);

        if (!$desenvolvedor) {
            return response()->json(['error' => 'Desenvolvedor não encontrado'], 404);
        }

        $valida = Validator::make($request->all(), [
            'nivel_id'          => 'required|exists:niveis,id',
            'nome'              => 'required',
            'sexo'              => 'required',
            'data_nascimento'   => 'required|date',
            'hobby'             => 'required',
        ]);

        if ($valida->fails()) {
            return response()->json(['error' => $valida->errors()], 400);
        }

        if (
            $desenvolvedor->nivel_id == $request->input('nivel_id') &&
            $desenvolvedor->nome == $request->input('nome') &&
            $desenvolvedor->sexo == $request->input('sexo') &&
            $desenvolvedor->data_nascimento == $request->input('data_nascimento') &&
            $desenvolvedor->hobby == $request->input('hobby')
        ) {
            return response()->json(['message' => 'Nenhuma alteração foi feita.'], 200);
        }


        $desenvolvedor->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Desenvolvedor atualizado com sucesso.',
            'data' => $desenvolvedor
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $desenvolvedor = Desenvolvedor::findOrFail($id);
            $desenvolvedor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Desenvolvedor deletado com sucesso.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Desenvolvedor não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao tentar deletar o desenvolvedor'], 500);
        }
    }

    public function countByNivel()
    {
        try {
            $query = DB::table('desenvolvedores')
                ->select('niveis.id', 'niveis.nivel', DB::raw('count(*) as quantidade'))
                ->join('niveis', 'desenvolvedores.nivel_id', '=', 'niveis.id')
                ->groupBy('niveis.id', 'niveis.nivel')
                ->get();

            if ($query->isEmpty()) {
                return response()->json(['message' => 'Não há desenvolvedores cadastrados por nível'], 404);
            }

            return response()->json([
                'data' => $query,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao tentar recuperar os dados dos desenvolvedores por nível'], 500);
        }
    }
}
