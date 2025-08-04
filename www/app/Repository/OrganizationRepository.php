<?php

namespace App\Repository;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\Phone;

final class OrganizationRepository implements Repository
{
    public static function getAll()
    {
        return null;
    }

    public static function getById(int $id)
    {
        return Organization::query()
            ->with('activity', function($query) {
                $query->select('activity.activityID', 'title', 'parentActivityID');
            })
            ->with('building', function($query) {
                $query->select('building.buildingID', 'address', 'latitude', 'longitude');
            })
            ->with('phone', function($query) {
                $query->select('phone.phoneID', 'phoneNumber');
            })
            ->findOrFail($id, ['organizationID', 'title', 'phoneID', 'buildingID']);
    }

    public static function getByTitle(string $title)
    {
        return Organization::query()
            ->with('activity', function($query) {
                $query->select('activity.activityID', 'title', 'parentActivityID');
            })
            ->with('building', function($query) {
                $query->select('building.buildingID', 'address', 'latitude', 'longitude');
            })
            ->with('phone', function($query) {
                $query->select('phone.phoneID', 'phoneNumber');
            })
            ->where('title', $title)
            ->firstOrFail(['organizationID', 'title', 'phoneID', 'buildingID']);
    }

    public static function getByBuilder(string $address)
    {
        $builder = Building::query()
            ->whereRaw("
            REGEXP_REPLACE(TRIM(\"address\"), '\s+', ' ', 'g') =
            REGEXP_REPLACE(TRIM(?), '\s+', ' ', 'g')", $address)
            ->with('organization')
            ->firstOrFail();

        return $builder->organization()
            ->with('activity', function($query) {
                $query->select('activity.activityID', 'title', 'parentActivityID');
            })
            ->with('building', function($query) {
                $query->select('building.buildingID', 'address', 'latitude', 'longitude');
            })
            ->with('phone', function($query) {
                $query->select('phone.phoneID', 'phoneNumber');
            })
            ->get(['organizationID', 'title', 'phoneID', 'buildingID']);
    }

    public static function getByActivity(string $title)
    {
        $activity = Activity::query()
            ->whereRaw("
            REGEXP_REPLACE(TRIM(\"title\"), '\s+', ' ', 'g') =
            REGEXP_REPLACE(TRIM(?), '\s+', ' ', 'g')",  $title)
            ->with('organization')
            ->firstOrFail();

        return $activity->organization()
            ->with('activity', function($query) {
                $query->select('activity.activityID', 'title', 'parentActivityID');
            })
            ->with('building', function($query) {
                $query->select('building.buildingID', 'address', 'latitude', 'longitude');
            })
            ->with('phone', function($query) {
                $query->select('phone.phoneID', 'phoneNumber');
            })
            ->get(['organization.organizationID', 'title', 'phoneID', 'buildingID']);
    }

    public static function getByLocation(float $latitude = .0, float $longitude = .0)
    {
        $buildings = Building::query()
            ->with('organization')
            ->where('latitude', '>=', $latitude)
            ->where('longitude', '<=', $longitude)
            ->get();

        $result = [];
        foreach($buildings as $building) {
            $result[] = $building->organization()
                ->with('activity', function($query) {
                    $query->select('activity.activityID', 'title', 'parentActivityID');
                })
                ->with('building', function($query) {
                    $query->select('building.buildingID', 'address', 'latitude', 'longitude');
                })
                ->with('phone', function($query) {
                    $query->select('phone.phoneID', 'phoneNumber');
                })
                ->first(['organizationID', 'title', 'phoneID', 'buildingID']);
        }

        return $result;
    }

    public static function searchByActivity(string $activity)
    {
        $activity = '%' . $activity . '%';

        $activitys = Activity::query()
            ->whereLike('activity.title', $activity)
            ->with('organization')
            ->get();

        $result = [];
        foreach($activitys as $activity) {
            $result[] = $activity->organization()
                ->with('activity', function($query) {
                    $query->select('activity.activityID', 'title', 'parentActivityID');
                })
                ->with('building', function($query) {
                    $query->select('building.buildingID', 'address', 'latitude', 'longitude');
                })
                ->with('phone', function($query) {
                    $query->select('phone.phoneID', 'phoneNumber');
                })
                ->first(['organization.organizationID', 'title', 'phoneID', 'buildingID']);
        }

        return $result;
    }

    public static function searchByTitle(string $title)
    {
        $title = '%' . $title . '%';

        return Organization::query()
            ->whereLike('title', $title)
            ->with('activity', function($query) {
                $query->select('activity.activityID', 'title', 'parentActivityID');
            })
            ->with('building', function($query) {
                $query->select('building.buildingID', 'address', 'latitude', 'longitude');
            })
            ->with('phone', function($query) {
                $query->select('phone.phoneID', 'phoneNumber');
            })
            ->get(['organizationID', 'title', 'phoneID', 'buildingID']);
    }
}
