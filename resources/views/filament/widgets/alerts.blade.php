<x-filament-widgets::widget>
    <x-filament::section heading="Alertas">
        <div class="space-y-3">
            @if($this->getCropsReadyForHarvest() > 0)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200/60 dark:from-amber-500/10 dark:to-orange-500/10 dark:border-amber-500/20 transition-all hover:shadow-sm">
                    <div class="flex items-center justify-center w-10 h-10 bg-amber-100 dark:bg-amber-500/20 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->getCropsReadyForHarvest() }} cultivos listos para cosecha</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Se requiere acción inmediata</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 flex-shrink-0">
                        Urgente
                    </span>
                </div>
            @endif

            @if($this->getPendingEvaluations() > 0)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/60 dark:from-blue-500/10 dark:to-indigo-500/10 dark:border-blue-500/20 transition-all hover:shadow-sm">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->getPendingEvaluations() }} cosecha con evaluación pendiente</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Esperando calificación de calidad</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 flex-shrink-0">
                        Pendiente
                    </span>
                </div>
            @endif

            @if($this->getInactiveFarms() > 0)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border border-red-200/60 dark:from-red-500/10 dark:to-rose-500/10 dark:border-red-500/20 transition-all hover:shadow-sm">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-500/20 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->getInactiveFarms() }} finca inactiva</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sin cultivos asociados</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 flex-shrink-0">
                        Alerta
                    </span>
                </div>
            @endif

            @if($this->getUpcomingFertilizations() > 0)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200/60 dark:from-green-500/10 dark:to-emerald-500/10 dark:border-green-500/20 transition-all hover:shadow-sm">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-500/20 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Fertilización programada en 3 días</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mantenimiento preventivo</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 flex-shrink-0">
                        Programado
                    </span>
                </div>
            @endif

            @if($this->getCropsReadyForHarvest() === 0 && $this->getPendingEvaluations() === 0 && $this->getInactiveFarms() === 0 && $this->getUpcomingFertilizations() === 0)
                <div class="flex items-center gap-4 p-4 bg-green-50 dark:bg-green-500/10 rounded-xl border border-green-200/60 dark:border-green-500/20">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-500/20 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Todo funciona correctamente</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">No hay alertas pendientes</p>
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
