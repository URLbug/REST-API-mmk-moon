<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Repository\OrganizationRepository;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->isMethod('GET'))
        {
            if($request->has('id')) {
                $id = $request->get('id');

                $organization = OrganizationRepository::getById($id);

                return $this->show($organization);
            }

            if($request->has('title')) {
                $title = $request->get('title');

                $organization = OrganizationRepository::getByTitle($title);

                return $this->show($organization);
            }

            if($request->has('builder')) {
                $builder = $request->get('builder');

                $organization = OrganizationRepository::getByBuilder($builder);

                return $this->show($organization);
            }
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        $organization = $organization->toArray();

        $result = [];
        foreach($organization as $key => $item) {
            if(in_array($key, ['phoneID', 'buildingID',])) {
                continue;
            }

            if($key == 'activity') {
                $activityArray = [];

                foreach($item as $activity) {
                    if(!$activity['parentActivityID']) {
                        $activityArray[] = [
                            'activityID' => $activity['activityID'],
                            'title' => $activity['title'],
                        ];
                        continue;
                    }

                    $activityParent = [];
                    $level = 0;
                    while($level <= 2) {
                        if(!$activity['parentActivityID']) {
                            break;
                        }

                        $activityParent = Activity::query()
                            ->where('activityID', $activity['parentActivityID'])
                            ->firstOrFail(['activityID', 'title', 'parentActivityID'])
                            ->toArray();

                        if(!$activityParent) {
                            break;
                        }

                        $activityParent['children'] = [
                            'activityID' => $activity['activityID'],
                            'title' => $activity['title'],
                        ];

                        if(isset($activity['children'])) {
                            $activityParent['children']['children'] = $activity['children'];
                        }

                        $activity = $activityParent;
                        unset($activityParent['parentActivityID']);
                        $level++;
                    }

                    $activityArray[] = $activityParent;
                }

                // Фильтрация: удаляем элементы, которые являются поддеревьями других
                $filtered = [];
                foreach ($activityArray as $current) {
                    $keep = true;
                    foreach ($activityArray as $other) {
                        if ($current === $other) continue;
                        if ($this->isSubtree($other, $current)) {
                            $keep = false;
                            break;
                        }
                    }
                    if ($keep) $filtered[] = $current;
                }

                $item = $filtered;
            }

            $result[$key] = $item;
        }

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }

    private function isSubtree(array $tree, array $sub): bool
    {
        if ($tree['activityID'] !== $sub['activityID']) {
            return false;
        }

        if (!isset($sub['children'])) {
            return true;
        }

        if (!isset($tree['children'])) {
            return false;
        }

        return $this->isSubtree($tree['children'], $sub['children']);
    }
}
