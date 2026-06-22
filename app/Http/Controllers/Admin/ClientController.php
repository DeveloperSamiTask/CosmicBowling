<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Client;
use Illuminate\Http\Request;
use App\Models\Frontend\Client as FrontendClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Frontend\Proxy;


class ClientController extends Controller
{
    public function index()
    {
        $data['title'] = "Cliente";
        return view('admin.client.index', $data);
    }

    public function indexSocio()
    {

        Log::info("Entrando en el metodo correcto");

        $data['title'] = "Cliente";
        return view('admin.client.indexSocio', $data);
    }

    public function show()
    {
        $clients = Client::getClients();
        return response()->json(['data' => $clients]);
    }

    public function showSocio()
    {
        $clients = Client::with(['partner', 'sunatTypedoc'])
            ->whereHas('partner')
            ->get()
            ->map(fn($c) => [
                'id'          => $c->id_client,
                'names'       => $c->lastname_pat . ' '. $c->lastname_mat . ' '.$c->names_client  ,
                'type_doc'    => $c->sunatTypedoc->name_doc ?? $c->document_id,
                'number_doc'  => $c->number_doc,
                'email'       => $c->email_client,
                'phone'       => $c->phone_client,
                'address'     => $c->address_client,
                'birthday'    => $c->birthday_client,
                'nTarjNumb'   => $c->partner->nTarjNumb   ?? '-',
                'dEmisDate'   => $c->partner->dEmisDate   ?? null,
                'dCaduDate'   => $c->partner->dCaduDate   ?? null,
                'affiliation' => $c->partner->affiliation ?? '-',
                'cTarjActi'   => $c->partner->cTarjActi   ?? '-',
                'validado'    => $c->partner->validado     ? 'Validado' : 'No validado',

                'phone_number'       => $c->partner->phone_number       ?? '-', // ← de ClientSocio
                'confirmation_email' => $c->partner->confirmation_email ?? '-',
            ]);

        return response()->json(['data' => $clients]);
    }

    public function search(Request $request)
    {
        // Quitamos el .proxy de aquí para que no explote
        $client = FrontendClient::with(['partner'])->where($request->select, $request->search)->first();

        if (!$client) {
            return response()->json(['icon' => 'warning', 'message' => 'No se encontró nada.']);
        }

        $data = $client->toArray();

        if ($client->partner && $client->partner->proxy_id) {
            // Buscamos el proxy de forma manual y directa
            $proxy = \App\Models\Frontend\Proxy::find($client->partner->proxy_id);
            if ($proxy) {
                $data['partner']['proxy'] = $proxy->toArray();
            }
        }

        return response()->json($data);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Formatear fecha
            $birthday = null;
            if ($request->editbirthdate) {
                $birthday = \Carbon\Carbon::parse($request->editbirthdate)->format('Y-m-d');
            }

            // 2. Buscar al Cliente (Tabla Principal)
            $client = FrontendClient::findOrFail($request->editCodeHidden);

            // 3. Actualizar datos básicos del Cliente
            $client->update([
                'lastname_pat'    => $request->editpattername,
                'lastname_mat'    => $request->editmattername,
                'names_client'    => $request->editnames,
                'number_doc'      => $request->editdoc,
                'birthday_client' => $birthday,
                'address_client'  => $request->editaddress,
                'phone_client'    => $request->editphone,
                'email_client'    => $request->editmail,
            ]);

            // 4. Lógica del Apoderado (Proxy) - Relación N a 1
            $proxyId = null;
            if ($request->filled('editproxyDoc')) {
                $proxy = Proxy::updateOrCreate(
                    ['proxy_doc' => $request->editproxyDoc], // Buscamos por DNI para no duplicar padres
                    [
                        'proxy_pattername' => $request->editproxyPatter, // Ajusta nombres según tu form
                        'proxy_mattername' => $request->editproxyMatter,
                        'proxy_names'      => $request->editproxyNames,
                    ]
                );
                $proxyId = $proxy->proxy_id;
            }

            // 5. Actualizar la tabla ClientSocio (Donde vive la FK proxy_id ahora)
            // Usamos la relación 'partner' que definimos en el modelo Client
            if ($client->partner) {
                $client->partner->update([
                    'proxy_id'     => $proxyId,
                    'affiliation'  => $request->editaffiliation,
                    'phone_number' => $request->editphone, // Mantener sincronizado el cel
                    'confirmation_email' => $request->editmail,
                ]);
            }

            DB::commit();
            return response()->json(['icon' => 'success', 'message' => 'Datos actualizados correctamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'icon' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }



}
