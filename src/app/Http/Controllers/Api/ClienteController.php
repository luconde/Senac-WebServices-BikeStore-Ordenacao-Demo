<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Support\Str;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Se há input
        if($request->input('ordenacao','')) {
            $sorts = explode(',', $request->input('ordenacao',''));

            $query = Cliente::query();
            foreach($sorts as $sortColumn) {
                $sortDirection = Str::startsWith($sortColumn,'-')?'desc':'asc';
                $sortColumn = ltrim($sortColumn, '-');

                // Transforma os nomes dos parametros em nomes dos campos do Modelo
                switch($sortColumn) {
                    case("nome"):
                        $query->orderBy('nome', $sortDirection);
                        break;
                    case("email"):
                        $query->orderBy('email', $sortDirection);
                        break;
                    case("cidade"):
                        $query->orderBy('cidade', $sortDirection);
                        break;
                    case("estado"):
                        $query->orderBy('estado', $sortDirection);
                        break;                        
                }
            }            
            $clientes = $query->get();
        }
        else {
            $clientes = Cliente::all();
        }
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Lista de clientes retornada',
            'clientes' => ClienteResource::collection($clientes)
]       , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClienteRequest $request)
    {
	        // Cria o objeto 
	        $cliente =new Cliente();
	        // Transfere os valores
	        $cliente->nome = $request->nome;
            $cliente->telefone = $request->telefone;
            $cliente->email = $request->email;
            $cliente->rua = $request->rua;
            $cliente->cidade = $request->cidade;
            $cliente->estado = $request->estado;
            $cliente->cep = $request->cep;
	        // Salva
	        $cliente->save();
	        // Retorna o resultado
	        return response() -> json([
	            'status' => 200,
                'mensagem' => 'Cliente armazenado',
	            'cliente' => new ClienteResource($cliente)
    ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        $cliente = Cliente::find($cliente->pkcliente);
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Cliente retornado',
            'cliente' => new ClienteResource($cliente)
    ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
	       // $marca = Marca::find($marca->pkcategoria);
           $cliente->nome = $request->nome;
           $cliente->telefone = $request->telefone;
           $cliente->email = $request->email;
           $cliente->rua = $request->rua;
           $cliente->cidade = $request->cidade;
           $cliente->estado = $request->estado;
           $cliente->cep = $request->cep;

           $cliente->update();
           return response() -> json([
               'status' => 200,
               'mensagem' => 'Cliente atualizado'
       ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Cliente apagado'
    ], 200);
    }
}
