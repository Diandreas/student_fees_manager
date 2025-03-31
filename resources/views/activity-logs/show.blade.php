<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de l\'Activité') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $activityLog->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Utilisateur</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $activityLog->user ? $activityLog->user->name : 'Système' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Action</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($activityLog->action) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type de modèle</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ class_basename($activityLog->model_type) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <p class="text-sm text-gray-900">{{ $activityLog->description }}</p>
                        </div>

                        @if($activityLog->old_values || $activityLog->new_values)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Modifications</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if($activityLog->old_values)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500 mb-2">Anciennes valeurs</h4>
                                            <pre class="bg-gray-50 p-4 rounded-lg text-sm text-gray-900">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                    @if($activityLog->new_values)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500 mb-2">Nouvelles valeurs</h4>
                                            <pre class="bg-gray-50 p-4 rounded-lg text-sm text-gray-900">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations techniques</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Adresse IP</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $activityLog->ip_address }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Navigateur</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $activityLog->user_agent }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 