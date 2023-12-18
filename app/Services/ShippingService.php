<?php

namespace App\Services;

class ShippingService
{
    const SHIPPING_FEES = [
        "GMA" => [
            "small-pouch" => 80,
            "medium-pouch" => 100,
            "big-pouch" => 120,
            "box" => 200,
            "oversized" => 400
        ],
        "Luzon" => [
            "small-pouch" => 140,
            "medium-pouch" => 170,
            "big-pouch" => 220,
            "box" => 270,
            "oversized" => 500
        ],"Visayas" => [
            "small-pouch" => 160,
            "medium-pouch" => 190,
            "big-pouch" => 240,
            "box" => 390,
            "oversized" => 500
        ],
        "Mindanao" => [
            "small-pouch" => 180,
            "medium-pouch" => 210,
            "big-pouch" => 260,
            "box" => 410,
            "oversized" => 600
        ],
    ];

    public function getAreaByLocation(string $region, string $province, string $city)
    {
        $area = '';
        switch ($region) {
            case 'NCR':
                $area = 'GMA';
                break;
            case 'Region I':
            case 'Region II':
            case 'Region III':
                if ($province == 'Bulacan' && in_array($city, [
                    'Marilao', 'Meycauayan City', 'Obando', 'San Jose Del Monte City'
                ])) {
                    $area = 'GMA';
                    break;
                } else {
                    $area = 'Luzon';
                    break;
                }
            case 'Region IV-A':
                if ($province == 'Cavite') {
                    $area = 'GMA';
                    break;
                } else if ($province == 'Rizal' && in_array($city, [
                        'Antipolo City', 'Cainta', 'San Mateo', 'Taytay'
                    ])) {
                    $area = 'GMA';
                    break;
                } else if ($province == 'Laguna' && in_array($city, [
                        'Binan City', 'San Pedro City', 'Santa Rosa City'
                    ])) {
                    $area = 'GMA';
                    break;
                } else {
                    $area = 'Luzon';
                    break;
                }
            case 'Region IV-B':
            case 'Region V':
                $area = 'Luzon';
                break;
            case 'Region VI':
            case 'Region VII':
            case 'Region VIII':
                $area = 'Visayas';
                break;
            case 'ARMM':
            case 'NIR':
            case 'Davao de Oro':
            case 'Region IX':
            case 'Region X':
            case 'Region XI':
            case 'Region XII':
            case 'Region XIII':
                $area = 'Mindanao';
                break;
        }

        return $area;
    }

    public function getShippingFeeByPackagingAndArea(string $packaging, string $area)
    {
        return self::SHIPPING_FEES[$area][$packaging];
    }
}
