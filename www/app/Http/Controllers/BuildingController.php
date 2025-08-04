<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Repository\OrganizationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Post(
 *     path="/api/v1/building",
 *     tags={"Buildings"},
 *     summary="Получить орагнизации по адресу",
 *     @OA\RequestBody(
 *         description="",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="address", type="string"),
 *         )
 *     ),
 *     @OA\Response(response=200, description=""),
 *     @OA\Response(response=400, description="")
 * )
 */
class BuildingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/building",
     *     tags={"Buildings"},
     *     summary="Получить организации по координатам здания",
     *     @OA\RequestBody(
     *         description="",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="latitude", type="float"),
     *             @OA\Property(property="longitude", type="float"),
     *         )
     *     ),
     *     @OA\Response(response=200, description=""),
     *     @OA\Response(response=400, description="")
     * )
     */
    public function index()
    {
        if(\request()->isMethod('POST')) {
            $validator = Validator::make(\request()->all(), [
                'address' => 'required|max:255',
            ], [
                'address.required' => 'Address is required',
                'address.max' => 'Address is too long',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $organizations = OrganizationRepository::getByBuilder(request()->get('address'));

            $result = [];
            foreach($organizations as $org) {
                $result[] = $this->toArray($org);
            }

            return response()->json($result);
        }

        $validator = Validator::make(request()->all(), [
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90' // NOTE: Широта должна быть в диапазоне от -90 до 90
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180' // NOTE: Долгота должна быть в диапазоне от -180 до 180
            ]
        ], [
            'latitude.required' => 'Latitude is required',
            'latitude.numeric' => 'Latitude must be a number',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees',
            'longitude.required' => 'Longitude is required',
            'longitude.numeric' => 'Longitude must be a number',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $organizations = OrganizationRepository::getByLocation(request()->get('latitude'), request()->get('longitude'));

        $result = [];
        foreach($organizations as $org) {
            $result[] = $this->toArray($org);
        }

        return response()->json($result);
    }
}
