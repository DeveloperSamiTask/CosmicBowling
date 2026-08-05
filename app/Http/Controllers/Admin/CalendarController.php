<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SubCategories;
use App\Models\Admin\Products;
use App\Models\Admin\Calendar;
use App\Models\Admin\calendarIntervals;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        $data = [
            'title' => "Productos",
            'subcategories' => SubCategories::all()
        ];
        return view('admin.calendar.index', $data);
    }

    public function show()
    {
        $products = Calendar::show();
        return response()->json(['data' => $products]);
    }

    public function store(Request $request, Products $products)
    {
        try {
            // Obtener la cantidad de productos disponibles (pistas activas)
            $availableQuantity = $products->where('subcategory_id', $request->input('data-id'))
                ->where('status_product', 1)
                ->count();

            // Verificar la cantidad enviada por el request
            $quantity = $request->input('quantity');

            // Validar que la cantidad no sea mayor a la disponible
            if ($quantity > $availableQuantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La cantidad ingresada excede el número de pistas activas disponibles. Máximo disponible: ' . $availableQuantity,
                ], 400);
            }

            // Si la cantidad es menor a 1, usar la cantidad disponible
            if ($quantity < 1) {
                $quantity = $availableQuantity;
            }


            $calendar = new Calendar();
            $calendar->subcategory_id = $request->input('data-id');
            $calendar->name_calendar = sprintf('%s (%d)', $this->cleanTitle($request->input('eventTitle')), $quantity);
            $calendar->price_calendar = $request->input('eventPrice');
            $calendar->extent_calendar = $request->input('eventLabel');
            $calendar->start_calendar = $request->input('eventStartDate');
            $calendar->end_calendar = $request->input('eventEndDate');
            $calendar->quantity_calendar = $request->input('quantity');
            $calendar->quantity_calendar = $quantity;
            $calendar->user_id = Auth::id();
            $calendar->save();

            $calendarId = $calendar->id_calendar;

            $timeIntervals = $this->getTimeIntervals($request->input('eventStartDate'), $request->input('eventEndDate'));

            foreach ($timeIntervals as $time) {
                calendarIntervals::create([
                    'subcategory_id' => $request->input('data-id'),
                    'calendar_id' => $calendarId,
                    'date_citem' => (new DateTime($request->input('eventStartDate')))->format('Y-m-d'),
                    'time_interval' => $time,
                    'available_quantity' => $quantity,
                    'price_citem' => $request->input('eventPrice'),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'calendar_id' => $calendarId,
                'time_intervals' => $timeIntervals,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Calendar $calendar, Products $products)
    {
        try {
            $usedIntervals = calendarIntervals::where('calendar_id', $calendar->id_calendar)
                ->where('available_quantity', '<', (int) $calendar->quantity_calendar)
                ->exists();

            if ($usedIntervals) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se puede actualizar este horario porque ya tiene reservas pagadas.',
                ], 409);
            }

            $subcategoryId = $request->input('data-id');
            $availableQuantity = $products->where('subcategory_id', $subcategoryId)
                ->where('status_product', 1)
                ->count();
            $quantity = (int) $request->input('quantity');

            if ($quantity < 1) {
                $quantity = $availableQuantity;
            }

            if ($quantity > $availableQuantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La cantidad ingresada excede el número de pistas activas disponibles. Máximo disponible: ' . $availableQuantity,
                ], 400);
            }

            $start = new DateTime($request->input('eventStartDate'));
            $end = new DateTime($request->input('eventEndDate'));

            if ($start >= $end || $start->format('Y-m-d') !== $end->format('Y-m-d')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La hora final debe ser posterior a la inicial y pertenecer al mismo día.',
                ], 422);
            }

            $timeIntervals = $this->getTimeIntervals($request->input('eventStartDate'), $request->input('eventEndDate'));

            DB::transaction(function () use ($request, $calendar, $subcategoryId, $quantity, $start, $timeIntervals) {
                $calendar->subcategory_id = $subcategoryId;
                $calendar->name_calendar = sprintf('%s (%d)', $this->cleanTitle($request->input('eventTitle')), $quantity);
                $calendar->price_calendar = $request->input('eventPrice');
                $calendar->extent_calendar = $request->input('eventLabel');
                $calendar->start_calendar = $request->input('eventStartDate');
                $calendar->end_calendar = $request->input('eventEndDate');
                $calendar->quantity_calendar = $quantity;
                $calendar->user_id = Auth::id();
                $calendar->save();

                calendarIntervals::where('calendar_id', $calendar->id_calendar)->delete();

                foreach ($timeIntervals as $time) {
                    calendarIntervals::create([
                        'subcategory_id' => $subcategoryId,
                        'calendar_id' => $calendar->id_calendar,
                        'date_citem' => $start->format('Y-m-d'),
                        'time_interval' => $time,
                        'available_quantity' => $quantity,
                        'price_citem' => $request->input('eventPrice'),
                    ]);
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Horario actualizado correctamente.',
                'calendar_id' => $calendar->id_calendar,
                'time_intervals' => $timeIntervals,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Calendar $calendar)
    {
        try {
            $usedIntervals = calendarIntervals::where('calendar_id', $calendar->id_calendar)
                ->where('available_quantity', '<', (int) $calendar->quantity_calendar)
                ->exists();

            if ($usedIntervals) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se puede borrar este horario porque ya tiene reservas pagadas.',
                ], 409);
            }

            $calendar->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Horario eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function cleanTitle($title)
    {
        return trim(preg_replace('/\s*\(\d+\)\s*$/', '', (string) $title));
    }

    private function getTimeIntervals($start, $end, $interval = '30 minutes')
    {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $intervalParts = explode(' ', $interval);
        $intervalValue = $intervalParts[0];
        $intervalUnit = strtoupper(substr($intervalParts[1], 0, 1));

        $interval = new DateInterval("PT{$intervalValue}{$intervalUnit}");
        $period = new DatePeriod($start, $interval, $end);

        $times = [];
        foreach ($period as $time) {
            $times[] = $time->format('H:i');
        }
        if ($end->format('H:i') != end($times)) {
            $times[] = $end->format('H:i');
        }

        // "Hora fin" representa el último turno que el cliente puede elegir.
        // Se agrega otro bloque de 30 minutos para que ese turno pueda
        // completar una reserva de una hora sin mostrarse como nuevo inicio.
        $supportTime = clone $end;
        $supportTime->add($interval);
        $times[] = $supportTime->format('H:i');

        return $times;
    }
}
