<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SubCategories;
use App\Models\Admin\SunatTypeDoc;
use App\Models\Frontend\Client;
use App\Models\LoyaltyReward;
use App\Services\LoyaltyService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class LoyaltyController extends Controller
{
    public function index()
    {
        $subcategories = SubCategories::where('category_id', 1)
            ->where('status_subcategory', 1)
            ->orderBy('id_subcategory')
            ->get(['id_subcategory', 'name_subcategory']);
        $documentTypes = SunatTypeDoc::orderBy('id_doc')
            ->get(['id_doc', 'name_doc']);

        return view('admin.loyalty.index', [
            'title' => 'Fidelización',
            'subcategories' => $subcategories,
            'documentTypes' => $documentTypes,
        ]);
    }

    public function clientsIndex()
    {
        return view('admin.loyalty.clients', ['title' => 'Clientes y premios']);
    }

    public function clientsList()
    {
        $clients = Client::query()
            ->whereHas('loyaltyCard')
            ->with([
                'sunatTypedoc',
                'loyaltyCard' => function ($query) {
                    $query->withCount([
                        'rewards as rewards_5_pending_count' => fn ($reward) => $reward->where('milestone', 5)->where('status', LoyaltyReward::STATUS_PENDING),
                        'rewards as rewards_5_used_count' => fn ($reward) => $reward->where('milestone', 5)->where('status', LoyaltyReward::STATUS_USED),
                        'rewards as rewards_10_pending_count' => fn ($reward) => $reward->where('milestone', 10)->where('status', LoyaltyReward::STATUS_PENDING),
                        'rewards as rewards_10_used_count' => fn ($reward) => $reward->where('milestone', 10)->where('status', LoyaltyReward::STATUS_USED),
                    ]);
                },
            ])
            ->orderBy('lastname_pat')
            ->orderBy('lastname_mat')
            ->orderBy('names_client')
            ->get()
            ->map(function (Client $client) {
                $card = $client->loyaltyCard;

                return [
                    'id' => $client->id_client,
                    'full_name' => trim("{$client->lastname_pat} {$client->lastname_mat} {$client->names_client}"),
                    'document_number' => $client->number_doc,
                    'document_type' => $client->sunatTypedoc?->name_doc ?? $client->document_id,
                    'registration_source' => $client->registration_source,
                    'card_number' => $card->card_number,
                    'current_checks' => $card->current_checks,
                    'total_checks' => $card->total_checks,
                    'current_cycle' => $card->current_cycle,
                    'rewards_5_pending' => $card->rewards_5_pending_count,
                    'rewards_5_used' => $card->rewards_5_used_count,
                    'rewards_10_pending' => $card->rewards_10_pending_count,
                    'rewards_10_used' => $card->rewards_10_used_count,
                ];
            });

        return response()->json(['data' => $clients]);
    }

    public function clientDetail(Client $client)
    {
        $client->load([
            'loyaltyCard.movements' => fn ($query) => $query->with(['manualPurchase.subcategory', 'registeredBy'])->latest('id'),
            'loyaltyCard.rewards' => fn ($query) => $query->latest('earned_at'),
        ]);

        if (!$client->loyaltyCard) {
            return response()->json(['message' => 'El cliente todavía no tiene tarjeta de fidelización.'], 404);
        }

        return response()->json([
            'client' => $this->clientData($client),
            'movements' => $client->loyaltyCard->movements->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'type' => $movement->movement_type,
                    'receipt_number' => $movement->manualPurchase?->receipt_number,
                    'game' => $movement->manualPurchase?->subcategory?->name_subcategory,
                    'quantity_lane' => $movement->manualPurchase?->quantity_lane,
                    'quantity_hours' => $movement->manualPurchase?->quantity_hours,
                    'checks' => $movement->checks,
                    'checks_before' => $movement->checks_before,
                    'checks_after' => $movement->checks_after,
                    'cycle_before' => $movement->cycle_before,
                    'cycle_after' => $movement->cycle_after,
                    'description' => $movement->description,
                    'registered_by' => $movement->registeredBy?->name ?? $movement->registeredBy?->username,
                    'created_at' => $movement->created_at?->format('d/m/Y H:i'),
                ];
            }),
            'rewards' => $client->loyaltyCard->rewards->map(function ($reward) {
                return [
                    'id' => $reward->id,
                    'milestone' => $reward->milestone,
                    'name' => $reward->reward_name,
                    'description' => $reward->reward_description,
                    'status' => $reward->status,
                    'cycle' => $reward->cycle_number,
                    'earned_at' => $reward->earned_at?->format('d/m/Y H:i'),
                    'redeemed_at' => $reward->redeemed_at?->format('d/m/Y H:i'),
                ];
            }),
        ]);
    }

    public function searchClient(Request $request, string $document)
    {
        $documentNumber = strtoupper(trim($document));
        $documentType = $request->query('document_id');
        $validator = Validator::make([
            'document_id' => $documentType,
            'number_doc' => $documentNumber,
        ], [
            'document_id' => ['required', Rule::exists('sunat_typedoc', 'id_doc')],
            'number_doc' => $this->documentNumberRules($documentType),
        ], [
            'number_doc.digits' => $documentType === '06'
                ? 'El RUC debe tener exactamente 11 dígitos.'
                : 'El DNI debe tener exactamente 8 dígitos.',
            'number_doc.regex' => 'El documento solo puede contener letras, números y guiones.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $client = Client::with([
            'loyaltyCard.rewards' => fn ($query) => $query
                ->where('status', LoyaltyReward::STATUS_PENDING)
                ->orderBy('earned_at')
                ->orderBy('id'),
        ])->where('document_id', $documentType)
            ->where('number_doc', $documentNumber)
            ->first();

        if (!$client) {
            return response()->json([
                'found' => false,
                'document_id' => $documentType,
                'document_number' => $documentNumber,
            ]);
        }

        return response()->json(['found' => true, 'client' => $this->clientData($client)]);
    }

    public function storeManualPurchase(Request $request, LoyaltyService $loyaltyService)
    {
        $request->merge([
            'number_doc' => strtoupper(trim((string) $request->input('number_doc'))),
        ]);
        $documentType = $request->input('document_id');
        $clientExists = Client::where('document_id', $documentType)
            ->where('number_doc', $request->input('number_doc'))
            ->exists();

        $data = $request->validate([
            'document_id' => ['required', Rule::exists('sunat_typedoc', 'id_doc')],
            'number_doc' => $this->documentNumberRules($documentType),
            'receipt_number' => ['nullable', 'string', 'max:50', Rule::unique('loyalty_manual_purchases', 'receipt_number')],
            'subcategory_id' => [
                'required',
                Rule::exists('subcategories', 'id_subcategory')->where(function ($query) {
                    $query->where('category_id', 1)->where('status_subcategory', 1);
                }),
            ],
            'quantity_lane' => ['required', 'integer', 'min:1', 'max:10'],
            'quantity_hours' => ['required', 'integer', 'min:1', 'max:12'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'lastname_pat' => [$clientExists ? 'nullable' : 'required', 'string', 'max:255'],
            'lastname_mat' => [$clientExists ? 'nullable' : 'required', 'string', 'max:255'],
            'names_client' => [$clientExists ? 'nullable' : 'required', 'string', 'max:255'],
            'phone_client' => ['nullable', 'string', 'max:20'],
            'birthday_client' => [$clientExists ? 'nullable' : 'required', 'date', 'before_or_equal:today'],
            'address_client' => ['nullable', 'string', 'max:255'],
        ], [
            'receipt_number.unique' => 'Ese número de boleta ya fue registrado.',
            'number_doc.digits' => $documentType === '06'
                ? 'El RUC debe tener exactamente 11 dígitos.'
                : 'El DNI debe tener exactamente 8 dígitos.',
            'number_doc.regex' => 'El documento solo puede contener letras, números y guiones.',
        ]);

        $data['total_hours'] = $data['quantity_hours'];
        $data['purchased_at'] = now();
        $data['amount'] = null;

        try {
            $result = $loyaltyService->registerManualPurchase($data, (int) auth()->id());
            $redeemedReward = $result['redeemed_reward'];

            return response()->json([
                'message' => $redeemedReward
                    ? "Compra registrada. Se utilizó automáticamente el premio: {$redeemedReward->reward_name}."
                    : 'Compra registrada y checks agregados correctamente.',
                'client' => $this->clientData($result['client']),
                'redeemed_reward' => $redeemedReward ? [
                    'id' => $redeemedReward->id,
                    'milestone' => $redeemedReward->milestone,
                    'name' => $redeemedReward->reward_name,
                ] : null,
            ], 201);
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                return response()->json([
                    'message' => 'El cliente o la compra ya fueron registrados. Actualiza la búsqueda antes de continuar.',
                ], 422);
            }

            Log::error('Error de base de datos al registrar compra de fidelización.', [
                'document' => $request->input('number_doc'),
                'exception' => $exception,
            ]);

            return response()->json(['message' => 'No se pudo registrar la compra.'], 500);
        } catch (Throwable $exception) {
            Log::error('Error al registrar compra de fidelización.', [
                'document' => $request->input('number_doc'),
                'exception' => $exception,
            ]);

            return response()->json(['message' => 'No se pudo registrar la compra.'], 500);
        }
    }

    private function clientData(Client $client): array
    {
        $client->loadMissing('sunatTypedoc');
        $card = $client->loyaltyCard;
        $nextReward = $card?->rewards
            ->where('status', LoyaltyReward::STATUS_PENDING)
            ->sortBy(fn ($reward) => sprintf('%s-%010d', $reward->earned_at, $reward->id))
            ->first();

        return [
            'id' => $client->id_client,
            'document_id' => $client->document_id,
            'document_number' => $client->number_doc,
            'document_type' => $client->sunatTypedoc?->name_doc ?? $client->document_id,
            'full_name' => trim("{$client->lastname_pat} {$client->lastname_mat} {$client->names_client}"),
            'phone' => $client->phone_client,
            'registration_source' => $client->registration_source,
            'card_number' => $card?->card_number,
            'current_checks' => $card?->current_checks ?? 0,
            'total_checks' => $card?->total_checks ?? 0,
            'current_cycle' => $card?->current_cycle ?? 1,
            'pending_rewards' => $card?->rewards
                ->where('status', LoyaltyReward::STATUS_PENDING)
                ->count() ?? 0,
            'next_reward' => $nextReward ? [
                'id' => $nextReward->id,
                'milestone' => $nextReward->milestone,
                'name' => $nextReward->reward_name,
                'description' => $nextReward->reward_description,
            ] : null,
        ];
    }

    private function documentNumberRules(?string $documentType): array
    {
        if ($documentType === '01') {
            return ['required', 'digits:8'];
        }

        if ($documentType === '06') {
            return ['required', 'digits:11'];
        }

        return ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9-]+$/'];
    }
}
