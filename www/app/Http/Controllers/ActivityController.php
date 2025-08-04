<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Repository\OrganizationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ActivityController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/activity",
     *     tags={"Activity"},
     *     summary="Получить по дееятельности организации",
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
    public function index(Request $request)
    {
        if(request()->isMethod('POST')) {
            $title = \request()->get('title');

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

            $organizations = OrganizationRepository::getByActivity($title);

            $result = [];
            foreach($organizations as $org) {
                $result[] = $this->toArray($org);
            }

            return response()->json($result);
        }
    }
}
