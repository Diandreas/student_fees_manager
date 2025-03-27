@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 d-flex justify-content-between align-items-center">
            <h5 class="font-medium text-gray-700 text-lg">
                Rapport sur les frais des {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}
            </h5>
            
            @if(session('current_school') && session('current_school')->logo)
                <div class="logo-container">
                    <img src="{{ asset('storage/' . session('current_school')->logo) }}" 
                         alt="{{ session('current_school')->name }}" 
                         class="school-logo-report">
                </div>
            @endif
        </div>
        <div class="p-6">
            <form action="{{ route('reports.students') }}" method="GET" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="field_id" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ session('current_school') ? session('current_school')->term('field') : 'Filière' }}
                        </label>
                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" 
                            id="field_id" name="field_id">
                            <option value="">Toutes les {{ session('current_school') ? strtolower(session('current_school')->term('fields')) : 'filières' }}</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}" {{ request('field_id') == $field->id ? 'selected' : '' }}>
                                    {{ $field->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">État du paiement</label>
                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" 
                            id="status" name="status">
                            <option value="">Tous les états</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ session('current_school') ? session('current_school')->term('fully_paid', 'Payé intégralement') : 'Payé intégralement' }}</option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>{{ session('current_school') ? session('current_school')->term('partially_paid', 'Payé partiellement') : 'Payé partiellement' }}</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>{{ session('current_school') ? session('current_school')->term('no_payment', 'Non payé') : 'Non payé' }}</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Générer le rapport
                            </button>
                            <a href="{{ route('reports.students.pdf') }}?{{ http_build_query(request()->all()) }}"
                               class="px-4 py-2 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                Exporter en PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ session('current_school') ? session('current_school')->term('student') : 'Étudiant' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ session('current_school') ? session('current_school')->term('field') : 'Filière' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ session('current_school') ? session('current_school')->term('fee', 'Frais totaux') : 'Frais totaux' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ session('current_school') ? session('current_school')->term('paid_amount', 'Montant payé') : 'Montant payé' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ session('current_school') ? session('current_school')->term('remaining', 'Reste à payer') : 'Reste à payer' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ session('current_school') ? session('current_school')->term('status', 'État') : 'État' }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $student)
                        @php
                            $totalFees = $student->field->fees;
                            $paidAmount = $student->payments->sum('amount');
                            $outstanding = $totalFees - $paidAmount;
                            
                            if ($outstanding <= 0) {
                                $status = session('current_school') ? session('current_school')->term('paid', 'Payé') : 'Payé';
                            } elseif ($paidAmount > 0) {
                                $status = session('current_school') ? session('current_school')->term('partial', 'Partiel') : 'Partiel';
                            } else {
                                $status = session('current_school') ? session('current_school')->term('unpaid', 'Non payé') : 'Non payé';
                            }
                            
                            $statusClass = $outstanding <= 0 ? 'bg-primary-100 text-primary-800' :
                                         ($paidAmount > 0 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->fullName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->field->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($totalFees, 0, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($paidAmount, 0, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($outstanding, 0, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .school-logo-report {
        height: 60px;
        width: auto;
    }
    
    .logo-container {
        text-align: right;
    }
</style>
@endpush
