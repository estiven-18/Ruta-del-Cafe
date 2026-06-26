<x-filament-widgets::widget>
    <x-filament::section heading="Actividad Reciente">
        @php
            $activities = $this->getActivities();
        @endphp

        @if(count($activities) > 0)
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($activities as $index => $activity)
                        <li>
                            <div class="relative pb-8 @if($loop->last) pb-0 @endif">
                                @if(!$loop->last)
                                    <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                @endif

                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full ring-8 ring-white dark:ring-gray-900
                                            @if($activity['color'] === 'success') bg-green-100 dark:bg-green-500/20
                                            @elseif($activity['color'] === 'warning') bg-amber-100 dark:bg-amber-500/20
                                            @elseif($activity['color'] === 'danger') bg-red-100 dark:bg-red-500/20
                                            @else bg-primary-100 dark:bg-primary-500/20 @endif">

                                            @if($activity['icon'] === 'heroicon-o-check-circle')
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @elseif($activity['icon'] === 'heroicon-o-currency-dollar')
                                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @elseif($activity['icon'] === 'heroicon-o-star')
                                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <span class="font-semibold text-gray-900 dark:text-white">{{ $activity['text'] }}</span>
                                            </div>
                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $activity['time'] }}
                                            </p>
                                        </div>
                                        <div class="mt-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                {{ $activity['detail'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No hay actividad reciente</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Las acciones aparecerán aquí automáticamente</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
