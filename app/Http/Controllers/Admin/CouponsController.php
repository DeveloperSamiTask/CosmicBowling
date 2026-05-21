<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories;
use App\Models\Admin\Coupons;
// C:\laragon\www\cosmicbowling\app\Models\Admin\CuponesMes.php
use App\Models\Admin\CuponesMes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CouponsController extends Controller
{
    public function index()
    {
        $data['title'] = "Cupones";
        $data['categories'] = Categories::with('subcategories')->get();
        return view('admin.coupon.index', $data);
    }

    public function show()
    {
        $coupons = Coupons::show();
        return response()->json(['data' => $coupons]);
    }

    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $coupon = new Coupons();
            $coupon->code = $request->input('code');
            $coupon->description = $request->input('description');
            $coupon->type_coupon = $request->input('typeD');
            $coupon->usage_limit = $request->input('quantity');
            $coupon->discount_type = $request->input('typeC');
            $coupon->discount_amount = $request->input('discount');
            $coupon->valid_from = $request->input('startDate');
            $coupon->valid_until = $request->input('endDate');

            $coupon->save();
            $coupon->subcategories()->attach($request->input('subcategories'));

            DB::commit();

            return response()->json(['success' => true, 'icon' => 'success', 'message' => 'Cupón creado exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'icon' => 'error', 'message' => 'Error al crear el cupón: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $coupon = Coupons::where('code', $request->input('code'))->first();

            if (!$coupon) {
                return response()->json(['success' => false, 'icon' => 'error', 'message' => 'Cupón no encontrado']);
            }

            $coupon->description = $request->input('description');
            $coupon->type_coupon = $request->input('typeD');
            $coupon->usage_limit = $request->input('quantity');
            $coupon->discount_type = $request->input('typeC');
            $coupon->discount_amount = $request->input('discount');
            $coupon->valid_from = $request->input('startDate');
            $coupon->valid_until = $request->input('endDate');

            $coupon->save();
            $coupon->subcategories()->sync($request->input('subcategories'));

            DB::commit();

            return response()->json(['success' => true, 'icon' => 'success', 'message' => 'Cupón editado exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'icon' => 'error', 'message' => 'Error al editar el cupón: ' . $e->getMessage()]);
        }
    }

    // public function agregarCupon()
    // {
    //     Log::info("Entrando en el metodo correcto agregar cupon");

    //     $data['title'] = "Cupones";
    //     $data['categories'] = Categories::with('subcategories')->get();
    //     return view('admin.coupon.agregar', $data);
    // }

    public function agregarCupon()
{
    // 1. Buscamos los cupones de este mes
    // Usamos keyBy('subcategory_id') para que en el Blade
    // podamos hacer $cuponesActuales[$id] sin problemas.
    $cuponesActuales = \App\Models\Admin\CuponesMes::where('mes', now()->month)
        ->where('anio', now()->year)
        ->get()
        ->keyBy('subcategory_id');

    // 2. Preparamos el array de datos para la vista
    $data = [
        'title' => "Agregar Cupones",
        'cuponesActuales' => $cuponesActuales // <-- ESTA ES LA VARIABLE QUE TU BLADE PIDE
    ];

    return view('admin.coupon.agregar', $data);
}

    public function subirCupon(Request $request)
{
    // 1. Validamos que el array 'cupones' traiga las imágenes
    $request->validate([
        'cupones' => 'required|array',
        'cupones.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
    ], [
        'cupones.required' => 'Debes subir los cupones de todos los servicios.',
    ]);

    $mes = now()->month;
    $anio = now()->year;
    // $timestamp = time();

    // 2. Recorremos el array de archivos
    // $subId es el ID de la subcategoría (1, 2, 3, 4) y $file es la imagen
    foreach ($request->file('cupones') as $subId => $file) {

        $nombreOriginal = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $nombreLimpio = Str::slug($nombreOriginal);

        // Nombre único: cupon_sub_[ID]_[MES]_[AÑO]_[TIME]_[NOMBRE].ext
        $nombreFinal = "cupon_sub_{$subId}_{$mes}_{$anio}_{$nombreLimpio}." . $file->getClientOriginalExtension();

        // 3. Movemos el archivo físico
        $file->move(public_path('img/cupones'), $nombreFinal);
        $rutaDestino = 'img/cupones/' . $nombreFinal;

        // 4. Creamos el registro individual en la BD
        CuponesMes::create([
            'subcategory_id' => $subId,
            'mes'            => $mes,
            'anio'           => $anio,
            'path_cupon'     => $rutaDestino
        ]);
    }

    return back()->with('success', 'Se han generado los 4 registros de cupones correctamente.');
}

    public function codeCoupon()
    {
        $code = $this->generateUniqueCouponCode();

        return response()->json(['code' => $code]);
    }

    private function generateUniqueCouponCode($length = 12)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);

        do {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        } while (Coupons::where('code', $randomString)->exists());

        return $randomString;
    }
}
