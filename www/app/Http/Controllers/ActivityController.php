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
