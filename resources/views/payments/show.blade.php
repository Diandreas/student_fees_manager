@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h5 class="font-bold text-primary-600">
                    <i class="fas fa-receipt mr-2"></i>{{ $school->term('payment_details', 'Détails du paiement') }}
                </h5>
                <div class="flex space-x-2">
                    <a href="{{ route('payments.index') }}" class="btn-outline">
                        <i class="fas fa-arrow-left mr-1"></i>{{ $school->term('back', 'Retour') }}
                    </a>
                    <a href="{{ route('students.show', $payment->student_id) }}" class="btn-secondary">
                        <i class="fas fa-user-graduate mr-1"></i>{{ $school->term('student_profile', 'Profil étudiant') }}
                    </a>
                    <a href="{{ route('payments.print', $payment->id) }}" class="btn-success" target="_blank">
                        <i class="fas fa-print mr-1"></i>{{ $school->term('print_receipt', 'Imprimer reçu') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">
                                {{ $school->term('payment_info', "Ce reçu a été généré le") }} 
                                {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h6 class="font-semibold text-gray-800">{{ $school->term('payment_information', 'Informations du paiement') }}</h6>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('receipt_number', 'Numéro de reçu') }}</span>
                                    <span class="font-semibold text-gray-800">{{ $payment->receipt_number }}</span>
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('amount', 'Montant') }}</span>
                                    <span class="font-semibold text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</span>
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('payment_date', 'Date de paiement') }}</span>
                                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('payment_method', 'Méthode de paiement') }}</span>
                                    <span class="font-semibold text-gray-800">
                                        @if($payment->payment_method == 'cash')
                                            <i class="fas fa-money-bill-wave mr-1 text-green-500"></i>
                                        @elseif($payment->payment_method == 'bank')
                                            <i class="fas fa-university mr-1 text-blue-500"></i>
                                        @elseif($payment->payment_method == 'mobile')
                                            <i class="fas fa-mobile-alt mr-1 text-orange-500"></i>
                                        @else
                                            <i class="fas fa-credit-card mr-1 text-gray-500"></i>
                                        @endif
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('description', 'Description') }}</span>
                                    <span class="font-semibold text-gray-800">{{ $payment->description }}</span>
                                </div>
                                @if($payment->notes)
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('notes', 'Notes') }}</span>
                                    <span class="font-semibold text-gray-800">{{ $payment->notes }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h6 class="font-semibold text-gray-800">{{ $school->term('student_information', 'Informations de l\'étudiant') }}</h6>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-lg font-bold mr-3">
                                        {{ strtoupper(substr($payment->student->fullName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="font-semibold text-gray-800">{{ $payment->student->fullName }}</h6>
                                        <p class="text-sm text-gray-500">{{ $payment->student->registration_number }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('field', 'Filière') }}</span>
                                    <span class="font-semibold text-gray-800">{{ $payment->student->field->name }}</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('campus', 'Campus') }}</span>
                                    <span class="font-semibold text-gray-800">{{ $payment->student->field->campus->name }}</span>
                                </div>
                                
                                @php
                                $paymentInfo = app('App\Http\Controllers\PaymentController')->getStudentPaymentInfo($payment->student_id);
                                @endphp
                                
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('total_fees', 'Frais de scolarité') }}</span>
                                    <span class="font-semibold text-gray-800">{{ number_format($paymentInfo['totalFees'], 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('total_paid', 'Total payé') }}</span>
                                    <span class="font-semibold text-green-600">{{ number_format($paymentInfo['totalPaid'], 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-1">
                                    <span class="text-sm text-gray-500">{{ $school->term('remaining_amount', 'Reste à payer') }}</span>
                                    <span class="font-semibold text-{{ $paymentInfo['remainingAmount'] > 0 ? 'red' : 'green' }}-600">
                                        {{ number_format($paymentInfo['remainingAmount'], 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection 