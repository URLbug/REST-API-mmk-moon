<?php

namespace App\Http\Controllers;

use App\Repository\OrganizationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/{id}/organization",
     *     tags={"Organizations"},
     *     summary="Получить ID организации",
     *     @OA\RequestBody(
     *         description="",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="int"),
     *         )
     *     ),
     *     @OA\Response(response=200, description=""),
     *     @OA\Response(response=400, description="")
     * )
     */
    public function index(int $id = 0): JsonResponse
    {
        if(request()->isMethod('POST')) {
            $result = [];

            if($title = request()->get('title')) {
                $result = $this->searchByTitle($title);
            }

            if($activity = request()->get('activity')) {
                $result = $this->searchByActivity($activity);
            }

            return response()->json($result);
        }

        if($id == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Id is required'
            ]);
        }

        $organization = OrganizationRepository::getById($id);

        return response()->json($this->toArray($organization));
    }

    private function searchByTitle(string $title)
    {
        $validator = Validator::make(\request()->all(), [
            'title' => 'required|max:255',
        ], [
            'title.required' => 'Title is required',
            'title.max' => 'Title is too long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $title = strtolower($title);
        $organizations = OrganizationRepository::searchByTitle($title);

        $result = [];
        foreach($organizations as $org) {
            $result[] = $this->toArray($org);
        }

        return $result;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/search",
     *     tags={"Organizations"},
     *     summary="Поиск по орагизации",
     *     @OA\RequestBody(
     *         description="",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="activity", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description=""),
     *     @OA\Response(response=400, description="")
     * )
     */
    private function searchByActivity(string $activity)
    {
        $validator = Validator::make(\request()->all(), [
            'activity' => 'required|max:255',
        ], [
            'activity.required' => 'Activity is required',
            'activity.max' => 'Activity is too long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $activity = strtolower($activity);
        $organizations = OrganizationRepository::searchByActivity($activity);

        $result = [];
        foreach($organizations as $org) {
            if(!$org) {
                continue;
            }

            $result[] = $this->toArray($org);
        }

        return $result;
    }
}
