<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Client;
use Illuminate\Http\Request;

class LoyaltyPortalController extends Controller
{
    private const SESSION_KEY = 'loyalty_portal_client_id';

    public function login(Request $request)
    {
        if ($request->session()->has(self::SESSION_KEY)) {
            return redirect()->route('loyalty.portal.card');
        }

        return view('frontend.loyalty.login');
    }

    public function authenticate(Request $request)
    {
        $request->merge([
            'document' => strtoupper(trim((string) $request->input('document'))),
        ]);

        $data = $request->validate([
            'document' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9-]+$/'],
        ], [
            'document.required' => 'Ingresa tu número de documento.',
            'document.regex' => 'El documento solo puede contener letras, números y guiones.',
        ]);

        $client = Client::where('number_doc', $data['document'])->first();

        if (!$client) {
            return back()
                ->withInput()
                ->withErrors(['document' => 'No encontramos una tarjeta asociada a ese documento.']);
        }

        $request->session()->put(self::SESSION_KEY, $client->id_client);
        $request->session()->regenerate();

        return redirect()->route('loyalty.portal.card');
    }

    public function card(Request $request)
    {
        $clientId = $request->session()->get(self::SESSION_KEY);

        if (!$clientId) {
            return redirect()->route('loyalty.portal.login');
        }

        $client = Client::with([
            'sunatTypedoc',
            'loyaltyCard.rewards' => fn ($query) => $query->latest('earned_at'),
        ])->find($clientId);

        if (!$client) {
            $request->session()->forget(self::SESSION_KEY);

            return redirect()->route('loyalty.portal.login');
        }

        return view('frontend.loyalty.card', compact('client'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('loyalty.portal.login');
    }
}
