<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\StatsOverviewWidget;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Processing Orders', Order::query()->where('status', 'processing')->count()),
            Stat::make('Shipped Orders', Order::query()->where('status', 'shipped')->count()),
            Stat::make('Delivered Orders', Order::query()->where('status', 'delivered')->count()),
            Stat::make('Canceled Orders', Order::query()->where('status', 'canceled')->count()),
            Stat::make('Average Price', Number::currency(Order::query()->average('grand_total') ?? 0), 'USD'),

        ];
    }
}