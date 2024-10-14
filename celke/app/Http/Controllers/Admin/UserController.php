<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        //Recuperar registro do banco de dados
        $users = User::get();


        //Carregar view
        return view('users.index', ['users' => $users]);
    }

    public function import(Request $request)
    {
        //Validar o arquivo
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'O arquivo é obrigatório',
            'file.mimes' => 'O arquivo deve ser do tipo CSV',
            'file.max' => 'O arquivo não deve ultrapassar 2MB',

        ]);

        //Receber o arquivo, tranformar em array
        $dataFile = collect(array_map('str_getcsv', file($request->file('file')->getRealPath())));

        $count = 0;

        foreach ($dataFile as $row) {
            try {

                $contentRow = explode(';', $row[$count]);

                $user = User::create([
                    'name' => $contentRow[0],
                    'email' => $contentRow[1],
                    'password' => $contentRow[2],
                ]);

                if ($user) {
                    $userData[] = $user;
                }
            } catch (QueryException $e) {
                return response ([
                    'status' => false,
                    'message' => "Falha na importação" .$e->getMessage()
                    
                ],Response::HTTP_BAD_REQUEST);
                
            }
        }

        return response ([
            'status' => true,
            'message' => "Importação criada com sucesso",
            'data' => $userData  ?? []
        ],Response::HTTP_OK);
    }
}
