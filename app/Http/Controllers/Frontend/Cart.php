<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\calendarIntervals;
use App\Models\Admin\Cart as AdminCart;
use App\Models\Admin\Holidays;
use App\Models\Admin\SubCategories;
use App\Models\Admin\Order;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\VisaNetService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Cart extends Controller
{
    const SHOES_PRICE = 8.00;

    protected $visanetService;

    public function __construct(VisaNetService $visanetService)
    {
        $this->visanetService = $visanetService;
    }

    public function index($formattedName)
    {
        session()->forget('billing');
        session()->forget('cart');
        session()->forget('summary');
        $title = "Reserva";

        try {
            $tomorrow = Carbon::tomorrow()->toDateString();

            // Convert the formatted name back to its original format for searching
            $formattedName = str_replace('_', ' ', ucwords(strtolower($formattedName)));

            // Find the subcategory corresponding to the provided formatted name
            $subcategory = SubCategories::where('name_subcategory', $formattedName)->firstOrFail();
            session(['limit' => $subcategory->limit_subcategory]);
            session(['subcategory' => $subcategory->id_subcategory]);

            // Get enabled items grouped by product and date for the selected subcategory
            $hours = calendarIntervals::filterBySubcategoryAndDate($subcategory->id_subcategory, $tomorrow);

            $isHoliday = $this->verifyHoliday($tomorrow);

            // Return the view with the subcategory, associated products, enabled product, and hours data
            return view('frontend.cart.cart', compact('subcategory', 'hours', 'title', 'isHoliday'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function guests(Request $request)
    {
        $date = $request->input('date'); // Fecha proporcionada en la solicitud
        $hours = [
            $request->input('one'),   // Primera hora
            $request->input('two'),   // Segunda hora
            $request->input('three'), // Tercera hora
            $request->input('four')   // Cuarta hora
        ];

        // Obtener el límite de la sesión
        $limit = session('limit');

        // Consultar todos los intervalos de tiempo a la vez
        $intervals = CalendarIntervals::where('date_citem', $date)
            ->where('subcategory_id', session('subcategory'))
            ->whereIn('time_interval', array_filter($hours)) // Filtrar nulls
            ->get();

        // Filtrar los intervalos nulos
        $intervals = $intervals->filter();

        // Encontrar el intervalo con la menor cantidad disponible
        $minAvailable = $intervals->min('available_quantity');

        // Calcular el resultado
        $result = $minAvailable * $limit;


        return response()->json([
            'min_available' => $minAvailable,
            'limit' =>    $limit,
            'calculated' => $result,
        ]);
    }

    public function show($subcategory, $date)
    {
        $hours = calendarIntervals::filterBySubcategoryAndDate($subcategory, $date);
        $isHoliday = $this->verifyHoliday($date); // Obtiene el dato directamente del JSON

        // Combina ambos valores en un array
        return response()->json([
            'hours' => $hours,
            'isHoliday' => $isHoliday
        ]);
    }

    public function cartData(Request $request)
    {

        $sessionData = $request->json('sessionArray', []);

        $formattedData = [];
        foreach ($sessionData as $item) {
            if (isset($item['key']) && isset($item['value'])) {
                $formattedData[$item['key']] = $item['value'];
            }
        }
        session(['cart' => $formattedData]);

        Log::alert('Datos del carrito guardados en la sesión', ['cart' => $formattedData]);

        return response()->json(['success' => true, 'message' => 'Datos guardados en la sesión']);
    }

    public function billingData(Request $request)
    {
        $validated = $request->validate([
            'observation' => ['nullable', 'string', 'max:1000'],
        ]);

        $observation = $validated['observation'] ?? null;
        $sessionData = $request->json()->all();
        session()->forget('billing');
        $client = Auth::guard('client')->user();
        $registrationDate = $client->created_at;
        $daysRegistered = Carbon::parse($registrationDate)->diffInDays(Carbon::now());

        $reservationCount = AdminCart::countReservationsByClient($client->id_client);
        $frequency = $reservationCount > 0 ? 1 : 0;

        // Obtener la descripción del documento
        $sunatDescription = $client->sunatTypedoc ? $client->sunatTypedoc->description_doc : null;

        if (isset($sessionData['type']) && $sessionData['type'] === 'Boleta') {

            // Preparar los datos del cliente
            $clientData = [
                'id_client' => $client->id_client,
                'document_id' => $client->document_id,
                'number_client' => $client->number_doc,
                'number_doc' => $client->number_doc,
                'lastname_pat' => $client->lastname_pat,
                'lastname_mat' => $client->lastname_mat,
                'names' => $client->names_client,
                'email' => $client->email_client,
                'phone' => $client->phone_client,
                'address' => $client->address_client,
                'document' => $sunatDescription,
                'days_registered' => $daysRegistered,
                'frequency' => $frequency,
                'observation' => $observation
            ];

            // Combinar los datos de la sesión con los datos del cliente
            $combinedData = array_merge($sessionData, $clientData);

            // Guardar los datos combinados en la sesión
            session(['billing' => $combinedData]);
        } else {
            $clientData = [
                'id_client' => $client->id_client,
                'lastname_pat' => $client->lastname_pat,
                'lastname_mat' => $client->lastname_mat,
                'number_client' => $client->number_doc,
                'names' => $client->names_client,
                'email' => $client->email_client,
                'phone' => $client->phone_client,
                'document' => $sunatDescription,
                'days_registered' => $daysRegistered,
                'frequency' => $frequency,
                'observation' => $observation
            ];

            $combinedData = array_merge($sessionData, $clientData);

            session(['billing' => $combinedData]);
        }

        $billingData = session('billing');
        $cartData = session('cart');

        return response()->json([
            'billing' => $billingData,
            'cart' => $cartData,
        ]);
    }

    public function updateInsurance(Request $request)
    {
        $insurance = $request->input('insurance', 0);
        $cart = session('cart', []);
        $cart['insurance'] = $insurance;
        session(['cart' => $cart]);

        Log::alert('Seguro actualizado', ['insurance' => $insurance, 'cart' => $cart]);

        return response()->json(['success' => true]);
    }

    public function paymentData($couponCode = null)
    {
        $cart = session('cart');
        $totalGuests = $cart['guests'];
        $couponCode = isset($cart['coupon']) ? $cart['coupon'] : null;
        $hasInsurance = isset($cart['insurance']) && floatval($cart['insurance']) > 0; // Verificar si tiene seguro
        $insuranceAmount = $hasInsurance ? 10.00 : 0;
        $discount = 0;
        $coupon = null;

        // Generar un código de reserva único
        $reservationCode = strtoupper(Str::random(7));

        // Obtén otros datos como el precio y la subcategoría
        $calendar = calendarIntervals::filterPayment($cart['product'], $cart['date'], $cart['time']);
        $subcategory = SubCategories::filterSubcategory($cart['product']);
        $limitPerTrack = $subcategory['limit'];

        $numberOfTracksNeeded = ceil($totalGuests / $limitPerTrack);

        // Calcular los intervalos de 30 minutos
        $halfHourIntervals = $this->getHalfHourIntervals($cart['time'], $cart['hours']);

        // Verificar disponibilidad de todos los intervalos de pistas
        $isAvailable = $this->checkAvailability($halfHourIntervals, $cart['product'], $cart['date'], $numberOfTracksNeeded);

        if (!$isAvailable) {
            return response()->json(['status' => false, 'message' => 'Ya no se encuentra disponible su horario. Por favor Verificar nuevamente.']);
        }

        $amount = ($numberOfTracksNeeded * $calendar['price']) * $cart['hours'];

        if ($couponCode) {
            $coupon = DB::table('coupons')
                ->where('code', $couponCode)
                ->where('is_active', 1)
                ->first();

            if ($coupon) {
                if ($coupon->discount_type === 'percentage') {
                    $discount = ($amount * $coupon->discount_amount) / 100;
                } elseif ($coupon->discount_type === 'fixed') {
                    $discount = $coupon->discount_amount;
                }

                $amount -= $discount;
            }
        }

        if ($cart['product'] != '4') {
            $shoesPrice = $this->verifyHoliday($cart['date']);
            $amount += $totalGuests * $shoesPrice;
        }

        // SUMAR EL SEGURO AL MONTO TOTAL
        $amount += $insuranceAmount;

        // Guardar los detalles del pedido en la base de datos
        $order = new Order();
        $order->client_id = session('billing.id_client');
        $order->subcategory_id = $cart['product'];
        $order->coupon_id = $coupon ? $coupon->id : null;
        $order->reservation_code = $reservationCode;
        $order->document_type = session('billing.type') === 'Factura' ? 'F' : 'B';

        if ($order->document_type === 'F') {
            $order->rsocial = session('billing.rsocial');
            $order->ruc = session('billing.ruc');
            $order->dir = session('billing.dir');
        }

        $order->date_reserved = $cart['date'];
        $order->hour_init = $cart['time'];
        $order->quantity_lane = $numberOfTracksNeeded;
        $order->quantity_hours = $cart['hours'];
        $order->quantity_guests = $cart['guests'];
        $order->amount_discount = $discount;
        $order->amount = $amount;
        $order->status = 'pending';
        $order->payment_type = '2';
        $order->insurance = $hasInsurance ? 1 : 0;
        $order->save();

        $arrSummary = [
            'amount' => $amount,
            'description' => $subcategory['name'],
            'quantity' => $numberOfTracksNeeded,
            'purchaseNumber' => $order->id_order,
            'discount' => $discount,
            'insurance' => $insuranceAmount, // AGREGAR AL RESUMEN
            'code' => $reservationCode
        ];

        session(['summary' => $arrSummary]);

        $data = $this->showFormPayment($amount, $order->id_order);

        $sessionKey = $data['sessionKey'];

        return response()->json([
            'sessionKey' => $sessionKey,
            'amount' => $amount,
            'purchaseNumber' => $order->id_order,
            'merchantId' => config('visanet.merchant_id'),
            'logoUrl' => 'https://cosmicbowling.com.pe/new/images/logo.png',
            'timeoutUrl' => url('/'),
            'action' => url('/Reserva'),
            'jsUrl' => config('visanet.url_js'),
            'status' => true
        ]);
    }

    public function getHalfHourIntervals($startTime, $hoursToAdd)
    {
        // Convertir la hora de inicio a un objeto DateTime
        $startDateTime = new DateTime($startTime);

        // Calcular la hora de finalización
        $endDateTime = clone $startDateTime;
        $endDateTime->modify("+{$hoursToAdd} hours");

        // Inicializar el array de intervalos
        $intervals = [];

        // Generar los intervalos de 30 minutos
        while ($startDateTime < $endDateTime) {
            $intervals[] = $startDateTime->format('H:i:s');
            $startDateTime->modify('+30 minutes');
        }

        return $intervals;
    }

    public function checkAvailability($intervals, $subcategory, $date, $requiredHours)
    {
        // Obtener los registros de los intervalos en una sola consulta
        $availability = DB::table('calendar_intervals')
            ->where('subcategory_id', $subcategory)
            ->whereDate('date_citem', $date)
            ->whereIn('time_interval', $intervals)
            ->get(['id_citem', 'time_interval', 'available_quantity'])
            ->keyBy('time_interval'); // Clave por time_interval para acceso rápido

        // Crear un array para guardar los IDs de los intervalos disponibles
        $intervalsAvailability = [];

        // Recorrer los intervalos de manera indexada
        for ($i = 0; $i < count($intervals); $i++) {
            $intervalTime = $intervals[$i];

            // Verificar si el intervalo actual tiene disponibilidad
            if (isset($availability[$intervalTime]) && $availability[$intervalTime]->available_quantity >= $requiredHours) {
                $intervalsAvailability[] = $availability[$intervalTime]->id_citem;
            } else {
                // Si un intervalo no tiene disponibilidad, la reserva no es posible
                return false;
            }
        }

        // Si no se encontró ningún intervalo disponible, retornar false
        if (empty($intervalsAvailability)) {
            return false;
        }

        // Guardar los IDs de los intervalos en la sesión
        session(['intervals_availability' => $intervalsAvailability]);

        // Retornar los IDs de los intervalos disponibles
        return $intervalsAvailability;
    } 

    public function showFormPayment($amount, $purchaseNumber)
    {

        $token =  $this->visanetService->generateToken();
        $sessionKey = $this->visanetService->generateSession($amount, $token, session('billing.email'), session('billing.frequency'), session('billing.number_doc'), session('billing.days_registered'));
        return [
            'token' => $token,
            'sessionKey' => $sessionKey,
        ];
    }

    public function showSession()
    {
        $client = session('billing');
        $cart = session('cart');

        return response()->json(session()->all());
    }

    public function verifyHoliday($date)
    {
        // Convierte la fecha a un objeto DateTime
        $date = new DateTime($date);
        $dateIn = $date->format('Y-m-d');

        // Verifica si es un día feriado activo
        $isHoliday = Holidays::where('date_holiday', $dateIn)
            ->where('status_holiday', 1)
            ->exists();

        // Verifica si es sábado (6) o domingo (0)
        $dayOfWeek = $date->format('w'); // 0 para domingo, 6 para sábado
        $isWeekend = in_array($dayOfWeek, [0, 5, 6]);

        // Retorna 10 si es feriado o fin de semana, 8 en caso contrario
        $result = ($isHoliday || $isWeekend) ? 12 : 8;
        session(['holiday_result' => $result]);

        return $result;
    }
}
