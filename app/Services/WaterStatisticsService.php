<?php

namespace App\Services;

use App\Models\WaterCollectionPoint;

class WaterStatisticsService
{
    public function calculateStatistics()
    {
        $points = WaterCollectionPoint::all();
        
        $totalTreated = $points->sum('agua_tratada');
        $totalRecycled = $points->sum('agua_reciclada');
        $totalReused = $points->sum('agua_reutilizada');
        
        // Avoid division by zero
        if ($totalTreated == 0) {
            return [
                'recycled_percentage' => 0,
                'reused_percentage' => 0,
                'not_recycled_percentage' => 0,
                'total_percentage' => 100
            ];
        }

        $recycledPercentage = ($totalRecycled / $totalTreated) * 100;
        $reusedPercentage = ($totalReused / $totalTreated) * 100;
        $notRecycledPercentage = 100 - ($recycledPercentage + $reusedPercentage);

        return [
            'recycled_percentage' => round($recycledPercentage, 1),
            'reused_percentage' => round($reusedPercentage, 1),
            'not_recycled_percentage' => round($notRecycledPercentage, 1),
            'total_percentage' => 100
        ];
    }
}
