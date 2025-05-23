<?php

namespace App\Helpers;

class VictoryPoints
{
    public static function calculate(array $stamps): int
    {
        $vp = 0;
        $baseAnimals = ['panda', 'orca', 'raven', 'blobfish', 'pallas cat'];

        // Parse stamps into base animals and premium stamps
        $base = [];
        $premium = [];

        foreach ($stamps as $stamp) {
            // Check if this is a base animal (no metal prefix)
            if (in_array($stamp, $baseAnimals)) {
                $base[] = $stamp;
            } else {
                // This should be a metal + animal combination
                $parts = explode(' ', $stamp);
                if (count($parts) >= 2) {
                    $metal = $parts[0];
                    $animal = implode(' ', array_slice($parts, 1)); // Join remaining parts for multi-word animals
                    $premium[] = ['metal' => $metal, 'animal' => $animal];
                } else {
                    // Fallback - treat as base animal
                    $base[] = $stamp;
                }
            }
        }

        // Step 1: Score complete animal sets (20 VP each)
        $allAnimals = array_merge($base, array_column($premium, 'animal'));
        $animalCounts = array_count_values($allAnimals);
        
        // Check how many complete sets we can make
        $completeSets = PHP_INT_MAX;
        foreach ($baseAnimals as $animal) {
            $count = $animalCounts[$animal] ?? 0;
            $completeSets = min($completeSets, $count);
        }
        
        if ($completeSets > 0) {
            $vp += $completeSets * 20;
            // Remove the animals used for complete sets
            foreach ($baseAnimals as $animal) {
                $animalCounts[$animal] -= $completeSets;
                if ($animalCounts[$animal] === 0) {
                    unset($animalCounts[$animal]);
                }
            }
        }

        // Step 2: Score complete metal sets (25 VP each)
        $metals = array_column($premium, 'metal');
        $metalCounts = array_count_values($metals);
        
        $completeMetalSets = min(
            $metalCounts['gold'] ?? 0,
            $metalCounts['silver'] ?? 0,
            $metalCounts['platinum'] ?? 0
        );
        
        $vp += $completeMetalSets * 25;

        // Step 3: Score remaining animals by distinct groups
        // Group animals by taking one of each type at a time
        while (!empty($animalCounts)) {
            $groupSize = count($animalCounts); // Number of distinct animal types
            
            // Apply VP based on group size
            if ($groupSize >= 4) $vp += 10;
            elseif ($groupSize === 3) $vp += 6;
            elseif ($groupSize === 2) $vp += 3;
            elseif ($groupSize === 1) $vp += 1;
            
            // Remove one of each animal type from the counts
            foreach ($animalCounts as $animal => $count) {
                $animalCounts[$animal]--;
                if ($animalCounts[$animal] === 0) {
                    unset($animalCounts[$animal]);
                }
            }
        }

        return $vp;
    }
}