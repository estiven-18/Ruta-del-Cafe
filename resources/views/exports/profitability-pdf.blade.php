<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Rentabilidad</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #15803d; padding-bottom: 10px; }
        .header h1 { color: #15803d; margin: 0; font-size: 22px; }
        .header p { color: #666; margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #15803d; color: white; padding: 8px 6px; text-align: left; font-size: 11px; }
        td { padding: 7px 6px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .profit { color: #15803d; font-weight: bold; }
        .loss { color: #dc2626; font-weight: bold; }
        .summary { margin-top: 20px; padding: 12px; background: #f3f4f6; border-radius: 6px; }
        .summary h3 { margin: 0 0 10px; color: #374151; font-size: 14px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 8px 12px; text-align: center; border: 1px solid #e5e7eb; }
        .summary-table .label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-table .value { font-size: 18px; font-weight: bold; color: #111827; padding-top: 4px; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Rentabilidad</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <h3>Resumen General</h3>
        <table class="summary-table">
            <tr>
                <td>
                    <div class="label">Total Ingresos</div>
                    <div class="value">${{ number_format($totalIncome, 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="label">Total Costos</div>
                    <div class="value">${{ number_format($totalCosts, 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="label">Ganancia Neta</div>
                    <div class="value {{ $netProfit >= 0 ? 'profit' : 'loss' }}">${{ number_format($netProfit, 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="label">Margen Promedio</div>
                    <div class="value {{ $avgMargin >= 0 ? 'profit' : 'loss' }}">{{ number_format($avgMargin, 1) }}%</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cosecha</th>
                <th>Finca</th>
                <th>Variedad</th>
                <th>Ingresos</th>
                <th>Costos</th>
                <th>Ganancia</th>
                <th>Margen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                @php
                    $harvest = $report->harvest;
                    $crop = $harvest?->crop;
                    $farm = $crop?->farm;
                    $variety = $crop?->coffeeVariety;
                @endphp
                <tr>
                    <td>{{ $report->getKey() }}</td>
                    <td>#{{ str_pad($report->harvest_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $farm?->name ?? '—' }}</td>
                    <td>{{ $variety?->name ?? '—' }}</td>
                    <td>${{ number_format($report->total_income, 0, ',', '.') }}</td>
                    <td>${{ number_format($report->total_costs, 0, ',', '.') }}</td>
                    <td class="{{ $report->net_profit >= 0 ? 'profit' : 'loss' }}">
                        ${{ number_format($report->net_profit, 0, ',', '.') }}
                    </td>
                    <td class="{{ $report->profitability_percentage >= 0 ? 'profit' : 'loss' }}">
                        {{ number_format($report->profitability_percentage, 1) }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Sin reportes disponibles</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Ruta del Café — Sistema de Gestión de Producción Cafetera
    </div>
</body>
</html>
