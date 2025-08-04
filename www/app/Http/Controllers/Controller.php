<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function toArray(Organization $organization): array
    {
        $result = [];
        foreach($organization->toArray() as $key => $item) {
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

        return $result;
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
