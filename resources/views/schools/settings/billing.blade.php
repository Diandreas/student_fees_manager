@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Paramètres de facturation
                </h1>
                <a href="{{ route('schools.settings.index', $school) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux paramètres
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-cog mr-2"></i>Paramètres de facturation
            </h5>
        </div>
        <div class="p-5">
            <form action="{{ route('schools.settings.billing', $school) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="payment_prefix" class="block text-sm font-medium text-gray-700 mb-1">Préfixe des numéros de paiement</label>
                        <input type="text" id="payment_prefix" name="payment_prefix" value="{{ old('payment_prefix', $school->payment_prefix) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Exemple: "PAY-" générera des numéros comme PAY-00001</p>
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
                        <select id="currency" name="currency" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="XAF" {{ old('currency', $school->currency) == 'XAF' ? 'selected' : '' }}>Franc CFA (XAF)</option>
                            <option value="USD" {{ old('currency', $school->currency) == 'USD' ? 'selected' : '' }}>Dollar américain (USD)</option>
                            <option value="EUR" {{ old('currency', $school->currency) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="receipt_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes de bas de reçu</label>
                    <textarea id="receipt_notes" name="billing_settings[receipt_notes]" rows="3"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('billing_settings.receipt_notes', $school->billing_settings['receipt_notes'] ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Ces notes apparaîtront en bas de chaque reçu de paiement</p>
                </div>

                <div class="mt-4">
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input id="allow_partial_payments" name="billing_settings[allow_partial_payments]" type="checkbox" 
                                value="1" {{ old('billing_settings.allow_partial_payments', $school->billing_settings['allow_partial_payments'] ?? true) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="allow_partial_payments" class="font-medium text-gray-700">Autoriser les paiements partiels</label>
                            <p class="text-gray-500">Permet aux étudiants de payer leurs frais en plusieurs versements</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input id="generate_receipt" name="billing_settings[generate_receipt]" type="checkbox" 
                                value="1" {{ old('billing_settings.generate_receipt', $school->billing_settings['generate_receipt'] ?? true) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="generate_receipt" class="font-medium text-gray-700">Générer automatiquement les reçus</label>
                            <p class="text-gray-500">Génère un reçu PDF à chaque paiement</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Enregistrer les paramètres de facturation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 