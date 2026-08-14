<?php

namespace App\Models\Admin;

use App\Models\Frontend\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id_cart';

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategories::class, 'subcategory_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupons::class, 'coupon_id');
    }

    public static function getAllBooking($startDate, $endDate, $column)
    {
        $query = self::query();

        if ($column === 'entrance') {
            if ($startDate && $endDate) {
                $query->whereBetween('date_reserved', [$startDate, $endDate]);
            }
        } elseif ($column === 'shop') {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        $booking = $query->orderByDesc('created_at')->get();

        $data = [];

        foreach ($booking as $row) {
            // Obtener el nmbre completo del cliente
            $clientFullName = trim(
                optional($row->client)->lastname_pat . ' ' .
                    optional($row->client)->lastname_mat . ' ' .
                    optional($row->client)->names_client
            );

            $createdAtLocal = Carbon::parse($row->created_at)->timezone('America/Lima')->format('d-m-Y H:i:s');
            $dateReserved = Carbon::parse($row->date_reserved)->format('d-m-Y');
            $doctype = ($row->document_type == 'B') ? 'BOLETA' : 'FACTURA';
            $numberDoc = ($row->document_type == 'B') ? optional($row->client)->number_doc : $row->ruc;
            $rsocial = ($row->document_type == 'B') ? $clientFullName : $row->rsocial;

            $data[] = [
                'id' => $row->id_cart,
                'code' => $row->reservation_code,
                'client' => $clientFullName,
                'dni' => $clientFullName,
                'coupon' => optional($row->coupon)->code,
                'description' => $row->description,
                'guests' => $row->quantity_guests,
                'price' => $row->amount,
                'shop' => $createdAtLocal,
                'date' => $dateReserved,
                'hour' => $row->hour_init,
                'doctype' => $doctype,
                'ruc' =>  $numberDoc,
                'rsocial' => $rsocial,
                'dir' =>  $row->dir,
                'status' => $row->status,
                'invoice' => $row->invoice,
                'sure' => $row->insurance,
                'observation' => $row->observation_client,
            ];
        }

        return $data;
    }

    public static function getBooking($code)
    {
        // Si el c¨®digo tiene exactamente 7 caracteres, buscar solo por reservation_code
        if (strlen($code) === 7) {
            $data = self::where('reservation_code', $code)->first();
        } else {
            // Buscar por cart o order_id
            $data = self::where('id_cart', $code);
        }
        // Verificamos que exista un registro antes de continuar
        if (!$data) {
            return null; // O manejar el error seg¨²n sea necesario
        }

        // Obtener la fecha y hora en formato deseado
        $dateReserved = $data->date_reserved; // "2024-09-21"
        $hourInit = $data->hour_init;         // "20:00:00"
        $combinedDateTime = $dateReserved . ' ' . $hourInit;
        $formattedDate = date('d/m/Y h:i A', strtotime($combinedDateTime));

        // Preparar el resultado
        $result = [
            'id' => $data->id_cart,
            'code' => $data->reservation_code,
            'subcategory' => optional($data->subcategory)->name_subcategory,
            'client' => optional($data->client)->lastname_pat . " " . optional($data->client)->lastname_mat . " " . optional($data->client)->names_client,
            'typeDoc' => optional($data->client->sunatTypedoc)->name_doc, // Accedemos al tipo de documento desde sunat_typedoc
            'numberDoc' => optional($data->client)->number_doc,
            'coupon' => $data->coupon ? $data->coupon->code : null,
            'description' => $data->description,
            'quantity' => $data->quantity_lane,
            'guests' => $data->quantity_guests,
            'hours' => $data->quantity_hours,
            'price' => $data->amount,
            'shop' =>  $formattedDate,
            'observation' =>  $data->observation_client,
            'sure' => $data->insurance,
            'status' => $data->status,
        ];

        return $result;
    }
}
