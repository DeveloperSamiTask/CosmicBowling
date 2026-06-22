<?php

namespace App\Http\Controllers\Frontend;
// PrintCupon
use App\Http\Controllers\Controller;
use App\Mail\RecoverClient;
use Illuminate\Http\Request;
use App\Models\Frontend\Client as FrontendClient;
use App\Models\Frontend\ClientSocio;
use App\Models\Frontend\PrintCupon;
use App\Models\Frontend\Proxy;
use App\Models\Frontend\LogPartnet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
//Mailing
use App\Mail\VerifyClient;
use App\Models\Admin\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
class Client extends Controller
{
        public function __construct()
        {
            $this->middleware('client.auth')->only(['show']);
        }

       /*
        1. Si el cliente ya existe y quiere ser socio , se ignoran los datos del from y se usa los datos de client mediante la FK que es id cliente (excepto : email al que llega verificacion , telefono )
        2. Si no existe lo crea todo desde 0 y luego asocia mediante la FK , el correo de confirmacion de cuenta y de socio son lo mismo y se manda al mismo lugar
    */
    public function insertSocio(Request $request)
    {

        $dEmisDate = Carbon::parse($request->initdate)->format('Y-m-d');
        // $dCaduDate = Carbon::parse($request->enddate)->format('Y-m-d');

        $dCaduDate = Carbon::parse($request->initdate)->addYear()->format('Y-m-d');
        $cumpleaños = Carbon::parse($request->birthdate)->format('Y-m-d');
        if ($dEmisDate === $dCaduDate) {
            Log::warning("Ojo: La fecha de emisión y caducidad son iguales.");
        }

            $client = FrontendClient::where('number_doc', $request->doc)->first();

            if ($client) {
                // CASO 1 — Ya existe en clients
                // ¿Ya es socio?
                $yaEsSocio = ClientSocio::where('client_id', $client->id_client)->first();
                if ($yaEsSocio) {
                    return response()->json([
                        'icon'    => 'warning',
                        'message' => 'Este cliente ya está registrado como socio.'
                    ], 200);
                }
                // NO se toca clients, solo se marca como socio
                $client->socio = 1;
                $client->save();

                // VERIFICACIÓN DE FECHA DE NACIMIENTO
                $birthdateForm = Carbon::parse($request->birthdate)->format('Y-m-d');
                $birthdateDB   = Carbon::parse($client->birthday_client)->format('Y-m-d');

                // if ($birthdateForm !== $birthdateDB) {
                //     return response()->json([
                //         'icon'    => 'error',
                //         'message' => 'La fecha de nacimiento no coincide con la registrada.'
                //     ], 200);
                // }



                // El email de confirmación viene del form (puede ser diferente al de clients)
                $confirmationEmail = $request->mail ?? $client->email_client;
                $tipoCaso          = 'socio'; // CASO B


            } else {
                // CASO 2 — No existe, crea todo desde 0

                $client = FrontendClient::create([
                    'document_id'     => '01',                          // DNI por defecto
                    'lastname_pat'    => $request->pattername,
                    'lastname_mat'    => $request->mattername,
                    'names_client'    => $request->names,
                    'number_doc'      => $request->doc,                // Esto va a ser su usuario y contraseña en caso se este creando ambos por pimera ves
                    'email_client'    => $request->mail,
                    'phone_client'    => $request->phone,
                    'address_client'  => $request->address,                 // DNI como dirección
                    'birthday_client' => $cumpleaños,

                    'password_client' => Hash::make($request->doc),     // DNI como password -> la contraseña si ess la misma al DNI
                    'socio'           => 1,
                    'validacion'      => 0,
                ]);

                // El email de confirmación es el mismo que se registró
                $confirmationEmail = $request->mail;
                $tipoCaso          = 'nuevo'; // CASO C
            }

            // PASO 2 — Apoderado (ambos casos)
            $apodNombre = null;
            $apodDoc    = null;

            $phone_number = $request->phone;
            // mismo cumpleaños
            $nTarjNumb = str_pad($client->id_client, 8, '0', STR_PAD_LEFT);

            try {
                ClientSocio::create([
                    'client_id'          => $client->id_client,
                    'nTarjNumb'          => $nTarjNumb,
                    'cTarjActi'          => 1,
                    'dEmisDate'          => $dEmisDate,
                    'dCaduDate'          => $dCaduDate,
                    'affiliation'        => $request->affiliation,
                    'validado'           => 0,
                    'status_magic'       => 0,
                    'confirmation_email' => $confirmationEmail,
                    // 'apod_nombre'        => $apodNombre,
                    // 'apod_doc'           => $apodDoc,
                    'user_new'           => auth()->user()->name ?? 'ATC',
                    'phone_number'       => $phone_number,
                ]);

                    if ($request->filled('proxyNames') || $request->filled('proxyDoc')) {
                        Proxy::updateOrCreate(
                            ['proxy_client' => $client->id_client],
                            [
                                'proxy_pattername' => $request->proxyPatter, // Quité el "edit"
                                'proxy_mattername' => $request->proxyMatter,
                                'proxy_names'      => $request->proxyNames,
                                'proxy_doc'        => $request->proxyDoc,
                            ]
                        );
                    }

                // Genera token con nombre según el caso
                $token = $client->createToken($tipoCaso)->plainTextToken;

                Log::info("Enviando correo de confirmación...", [
                    'destino' => $confirmationEmail,
                    'caso'    => $tipoCaso,
                    'token'   => $token
                ]);

                // Manda el email
                Mail::to($confirmationEmail)->send(
                    new VerifyClient(
                        $token,
                        $client->names_client,
                        $tipoCaso,
                        $client->number_doc,   // <-- Asegúrate que se llame así en tu objeto $client
                        $cumpleaños,
                        $nTarjNumb,
                    )
                );

                Log::info("Correo enviado exitosamente a: " . $confirmationEmail);

                return response()->json([
                    'icon'    => 'success',
                    'message' => 'Socio registrado correctamente.'
                ]);

                } catch (\Throwable $th) {
                    return response()->json([
                        'icon'    => 'error',
                        'message' => $th->getMessage()
                    ], 500);
                }
    }

    public function loginSocios(Request $request)
    {
        $tarjeta = (string) $request->txt_usuario;

        // 1. Log del intento
        Log::info('Datos recibidos:', [
            'tarjeta' => $tarjeta,
            'dia_input' => $request->txt_dia,
            'mes_input' => $request->txt_mes,
            'anio_input' => $request->txt_anio
        ]);


        $socio = ClientSocio::where('nTarjNumb', $tarjeta)->first();

        if (!$socio) {
            Log::warning('Login fallido: Tarjeta no encontrada', ['tarjeta' => $tarjeta]);
            return back()->withErrors(['msg' => 'Credenciales incorrectas (Tarjeta no existe)']);
        }


        $client = $socio->client;

        if (!$client) {
            Log::error('Error integridad: Socio sin cliente asociado', ['socio_id' => $socio->id]);
            return back()->withErrors(['msg' => 'Error en cuenta de usuario']);
        }

        $dia = str_pad($request->txt_dia, 2, '0', STR_PAD_LEFT);
        $mes = str_pad($request->txt_mes, 2, '0', STR_PAD_LEFT);
        $anio = $request->txt_anio;

        $fechaIngresada = "{$anio}-{$mes}-{$dia}";

        Log::info("Comparando: DB[{$client->birthday_client}] vs Input[{$fechaIngresada}]");

        if (trim($client->birthday_client) === trim($fechaIngresada)) {
           Auth::guard('client')->login($client);

            Log::info('Login exitoso:', ['client_id' => $client->id_client]);

            // Usa el nombre de la ruta para estar seguros
            return redirect()->route('client.profile');
        }

        Log::warning('Login fallido: Fecha incorrecta', ['client_id' => $client->id_client]);
        return back()->withErrors(['msg' => 'Credenciales incorrectas (Fecha)']);
    }

    public function renovarVista()
    {
        $clientId = Auth::guard('client')->id();

        // Buscamos al socio con su relación 'partner' (por si la usas en la vista)
        $socio = FrontendClient::with('partner')->find($clientId);

        // Pasamos el objeto $socio a la vista 'renovar'
        return view('frontend.client.renovar', compact('socio'));
    }

    public function profile()
    {

       $clientId = Auth::guard('client')->id();
       $socio = FrontendClient::with('partner')->find($clientId);

        if (!$socio) {
            return redirect()->route('login')->with('error', 'No se encontró el perfil.');
        }

        log::info("Datos del socio : " . $socio);

        // Retornamos la vista con el objeto cliente
        return view('frontend.client.profile_socios', compact('socio'));
    }

    public function show()
    {
        $clientId = Auth::guard('client')->id();
       $socio = FrontendClient::with('partner')->find($clientId);

        if (!$socio) {
            return redirect()->route('login')->with('error', 'No se encontró el perfil.');
        }

        log::info("Datos del socio : " . $socio);

        // Retornamos la vista con el objeto cliente
        return view('frontend.client.profile_socios', compact('socio'));
    }



    public function promociones()
    {
        $client = Auth::guard('client')->user();

        $mesActual = now()->month; // Supongamos que es 5
        $anioActual = now()->year;

        // Busca qué cupones ya imprimió este socio
        $cuponesImpresos = PrintCupon::where('client_id', $client->id_client)
            // CORRECCIÓN: Pasa la variable directamente
            ->where('mes', $mesActual)
            ->where('anio', $anioActual)
            ->pluck('cupon_id')
            ->toArray();

        return view('frontend.client.promociones', compact('client', 'cuponesImpresos'));
    }

    public function imprimirCupon(Request $request)
    {
        $client = Auth::guard('client')->user();

        $mesActual = now()->month;
        $anioActual = now()->year;

        // Verifica que no lo haya imprimido ya
        $yaImprimio = PrintCupon::where('client_id', $client->id_client)
            ->where('cupon_id', $request->cupon_id)
            // CORRECCIÓN: Pasa la variable directamente
            ->where('mes', $mesActual)
            ->where('anio', $anioActual)
            ->exists();

        if ($yaImprimio) {
            return response()->json(['icon' => 'error', 'message' => 'Ya imprimiste este cupón']);
        }

        // Guarda el registro
        PrintCupon::create([
            'client_id'   => $client->id_client,
            'cupon_id'    => $request->cupon_id,
            'fecha_print' => now()->toDateString(),
            'mes'         => $mesActual, // No olvides guardar el mes y año si tu tabla los pide
            'anio'        => $anioActual,
            'estado'      => 0,
        ]);

        return response()->json(['icon' => 'success', 'message' => 'Cupón registrado']);
    }

    public function create()
    {
        if (auth()->guard('client')->check()) {
            return redirect()->route('home.index');
        }
        return view('frontend.login.register');
    }

    public function update(Request $request)
    {
        \Log::info('ID recibido: ' . $request->editCodeHidden);
        \Log::info('ID recibido: ' . $request->editCodeHidden);
        \Log::info('Todos los datos: ', $request->all());


        if (!$request->editCodeHidden) {
            return response()->json(['icon' => 'error', 'message' => 'ID de cliente no proporcionado'], 400);
        }

        DB::beginTransaction();
        try {
            // ← FIX: usar Client en vez de FrontendClient
            $client = Client::find($request->editCodeHidden);

            if (!$client) {
                return response()->json(['icon' => 'error', 'message' => 'Cliente no encontrado'], 404);
            }

            $birthday = $request->editbirthdate
                ? \Carbon\Carbon::parse($request->editbirthdate)->format('Y-m-d')
                : null;

            $client->update([
                'lastname_pat'    => $request->editpattername,
                'lastname_mat'    => $request->editmattername,
                'names_client'    => $request->editnames,
                'number_doc'      => $request->editdoc,
                'birthday_client' => $birthday,
                'address_client' => $request->input('editaddress', ''),
                'phone_client'   => $request->input('editphone', ''),
                'email_client'    => $request->editmail,
            ]);

            $proxyId = null;
            if ($request->filled('editproxyDoc')) {
                $proxy = Proxy::updateOrCreate(
                    ['proxy_doc' => $request->editproxyDoc],
                    [
                        'proxy_pattername' => $request->editproxyPatter,
                        'proxy_mattername' => $request->editproxyMatter,
                        'proxy_names'      => $request->editproxyNames,
                    ]
                );
                $proxyId = $proxy->proxy_id;
            }

            // ← FIX: id_client en vez de id
            $client->partner()->updateOrCreate(
                ['client_id' => $client->id_client],
                [
                    'proxy_id'           => $proxyId,
                    'affiliation'        => $request->editaffiliation,
                    'phone_number'       => $request->editphone,
                    'confirmation_email' => $request->editmail,
                ]
            );

            DB::commit();
            return response()->json(['icon' => 'success', 'message' => 'Datos actualizados correctamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'icon'    => 'error',
                'message' => 'Error al procesar: ' . $e->getMessage()
            ], 500);
        }
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

    public function index()
    {
        if (auth()->guard('client')->check()) {
            return redirect()->route('home.index');
        }

        return view('frontend.login.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_doc' => 'required',
            'number_doc' => 'required|unique:clients,number_doc',
            'paternal' => 'required',
            'maternal' => 'required',
            'firstname' => 'required',
            'mail_user' => 'required|email|unique:clients,email_client',
            'phone_user' => 'required',
            'birthday_user' => 'required|date_format:d/m/Y',
            'district_user' => 'required',
            'current_password' => 'required|min:8',
        ], [
            'number_doc.unique' => 'El número de documento ya está registrado. Por favor, use otro número.',
            'mail_user.unique' => 'El correo electrónico ya está en uso. Por favor, use otro correo.',
            'current_password.min' => 'La contraseña debe tener al menos :min caracteres.',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $birthday = Carbon::createFromFormat('d/m/Y', $request->input('birthday_user'))->format('Y-m-d');

        $client = FrontendClient::create([
             'document_id'     => $request->type_doc ?? '00',
            'number_doc' => $request->input('number_doc'),
            'lastname_pat' => $request->input('paternal'),
            'lastname_mat' => $request->input('maternal'),
            'names_client' => $request->input('firstname'),
            'email_client' => $request->input('mail_user'),
            'phone_client' => $request->input('phone_user'),
            'birthday_client' => $birthday,
            'address_client' => $request->input('district_user'),
            'password_client' => Hash::make($request->input('current_password'))
        ]);

        $token = $client->createToken('ClientToken')->plainTextToken;

        Mail::to($client->email_client)->send(new VerifyClient(
            $token,                    // 1. Token
            $client->names_client,     // 2. Nombre (Ojo con la 's')
            'registro',                // 3. Tipo (Le ponemos 'registro' para identificar que es nuevo)
            $client->number_doc,       // 4. Documento
            $client->birthday_client,  // 5. Fecha
            'NO ASIGNADO'              // 6. Tarjeta (Como es nuevo, aún no tiene)
        ));

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info("DEBUG LOGIN:", [
            'input_number' => $request->input('number'),
            'type' => gettype($request->input('number'))
        ]);

        // Cargar la relación con SunatTypeDoc
        $client = FrontendClient::with('sunatTypedoc')->where('number_doc', $request->input('number'))->first();

        if (!$client) {
            return response()->json(['error' => 'Número de documento incorrecto'], 401);
        }

        if (!Hash::check($request->input('password'), $client->password_client)) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        // Verificar si la cuenta está verificada
        if (!$client->email_verified_at) {
            // Enviar correo de verificación
            $token = $client->createToken('ClientToken')->plainTextToken;
            Mail::to($client->email_client)->send(new VerifyClient(
                $token,
                $client->names_client,      // <-- Corregido: names_client (con s)
                'verificacion',             // Tipo: para que el email sepa qué plantilla usar
                $client->number_doc,
                $client->birthday_client,
                'NO ASIGNADO'               // Como es verificación de cuenta, aún no hay tarjeta
            ));

            return response()->json([
                'error' => 'Cuenta no verificada',
                'message' => 'Por favor, verifique su correo electrónico para activar su cuenta. Hemos enviado un nuevo correo de verificación.'
            ], 403);
        }

        // Autenticar al cliente
        Auth::guard('client')->login($client);

        // Generar token de autenticación
        $token = $client->createToken('ClientToken')->plainTextToken;

        // Obtener el primer nombre y la URL del avatar
        $firstName = explode(' ', trim($client->names_client))[0];
        $avatarUrl = asset('frontend/img/avatar/51.jpg');

        // Obtener el documento relacionado con SunatTypeDoc
        $sunatDoc = $client->sunatTypedoc ? $client->sunatTypedoc->id_doc : null;

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $firstName,
                'avatar' => $avatarUrl,
                'names' => $client->names_client,
                'pattername' => $client->lastname_pat,
                'mattername' => $client->lastname_mat,
                'email' => $client->email_client,
                'phone' => $client->phone_client,
                'document' => $client->number_doc,
                'sunatdoc' => $sunatDoc  // Incluyendo el dato adicional
            ]
        ], 200);
    }

    // public function logout(Request $request)
    // {
    //     // Elimina los tokens si estás usando tokens para autenticación
    //     if ($request->user()) {
    //         $request->user()->tokens()->delete();
    //     }

    //     // Cierra la sesión
    //     Auth::guard('client')->logout();

    //     // Invalidar la sesión
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return response()->json(['message' => 'Successfully logged out'], 200);
    // }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home.index');
    }

    public function verifyEmail(Request $request)
    {

        $tokenString = $request->input('token');

        // 1. Buscamos el MODELO del token primero
        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($tokenString);

        if (!$accessToken) {
            return view('verification-error')->with('error', 'El link es inválido o ya fue usado.');
            // return redirect('/')->with('error', 'El enlace de verificación es inválido o ha expirado.');
        }

        // 2. Ahora sí, sacamos el cliente y el nombre del caso
        $client = $accessToken->tokenable;
        $tipoCaso = $accessToken->name;

        if ($client) {
            // --- LOGICA DE CLIENTE ---
            if ($tipoCaso === 'nuevo_socio') {
                $client->email_verified_at = now();
                $client->socio = 1;
                $client->validacion = 1;
            } else {
                // $client->socio = 1;
                $client->validacion = 1;
                $client->email_verified_at = now();
            }

            $client->save(); // ¡IMPORTANTE! Si no, no se guarda nada.

            // --- LOGICA DE LA TABLA SOCIO ---
            // Buscamos su registro en la otra tabla para validarlo ahí también
            $socioData = ClientSocio::where('client_id', $client->id_client)
                                                ->where('validado', 0)
                                                ->first();
            if ($socioData) {
                $socioData->validado = 1;
                $socioData->save();
            }

            //$accessToken->delete();

            return view('frontend.login.verify')->with('status', '¡Verificación exitosa! Ya eres socio.');
        }

        return view('verification-error')->with('error', 'No se pudo encontrar al usuario.');
        // return redirect('/')->with('error', 'El enlace de verificación es inválido o ha expirado.');
    }

    public function recover(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $client = FrontendClient::where('email_client', $request->input('email'))->first();

        if (!$client) {
            return response()->json(['error' => 'Correo electrónico incorrecto'], 401);
        }

        $token = $client->createToken('ClientToken')->plainTextToken;

        Mail::to($client->email_client)->send(new RecoverClient($token, $client->name_client));

        return response()->json(['message' => 'Correo de recuperación enviado'], 200);
    }

    public function recoverPassword(Request $request)
    {
        $token = $request->input('token');

        $client = PersonalAccessToken::findToken($token)->tokenable;

        //view interface two textbox recover password

        if ($client) {
            return view('frontend.login.recover-password', compact('token'));
        }

        return view('frontend.login.recover');
    }

    public function reset(Request $request)
    {

        $client = PersonalAccessToken::findToken($request->input('token'))->tokenable;

        if ($client) {
            $client->password_client = Hash::make($request->input('password'));
            $client->save();

            return response()->json(['message' => 'Contraseña restablecida'], 200);
        }

        return response()->json(['error' => 'Token inválido'], 401);
    }

    public function renew(Request $request)
    {
        try {
            // 1. Validar que llegue el ID del cliente (lo que guardaste en hiddenCode)
            if (!$request->hiddenCode) {
                return response()->json(['icon' => 'error', 'message' => 'ID de cliente no recibido.'], 400);
            }

            // 2. Buscar al socio en la tabla correcta: ClientSocio
            $socio = ClientSocio::where('client_id', $request->hiddenCode)->first();

            if (!$socio) {
                return response()->json(['icon' => 'error', 'message' => 'El registro de socio no existe.'], 404);
            }

            // 3. Formatear fechas (asumiendo que vienen dd-mm-yyyy del front)
           try {
            // $dCaduDate = Carbon::parse($request->initdate)->addYear()->format('Y-m-d');

                \Log::info('Fechas recibidas:', [
                     'renewInitdate' => $request->renewInitdate,
                    'renewEnddate'  => $request->renewEnddate,
                ]);

                $dEmisDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->renewInitdate)->format('Y-m-d');
                $dCaduDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->renewEnddate)->format('Y-m-d');
            } catch (\Exception $e) {
                return response()->json(['icon' => 'error', 'message' => 'Formato de fecha inválido. Envía dd-mm-yyyy.'], 400);
            }

            // 4. Actualizar ClientSocio
            $socio->update([
                'dEmisDate'   => $dEmisDate,
                'dCaduDate'   => $dCaduDate,
                'affiliation' => $request->renewAffiliation,
                'status_magic'=> 0, // Reiniciamos status
                'user_renew'    => auth()->user()->name ?? 'ATC', // Usamos tu columna user_new
            ]);

            // 5. Registrar el Log usando tu modelo LogPartnet
            // Pasamos: (ID Cliente, Tipo 1 = Renovación, Afiliación)
            LogPartnet::registerLog(
                $socio->nTarjNumb,
                'RENOVACION',
                $request->renewAffiliation
            );

            return response()->json([
                'icon' => 'success',
                'message' => 'Socio renovado correctamente y log registrado.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'message' => 'Error al renovar: ' . $e->getMessage()
            ], 500);
        }
    }









}























