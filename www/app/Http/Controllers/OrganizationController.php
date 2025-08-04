<?php

namespace App\Http\Controllers;

use App\Repository\OrganizationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
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
