<?php

namespace App\Repository;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;

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
        var_dump($address);
        $builder = Building::query()
            ->where('address', '<=', $address)  // Используем оператор <= так как нету возможности искать
            ->firstOrFail();

        return Organization::query()
            ->where('buildingID', $builder->buildingID)
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

    public static function getByActivity(Activity $activity)
    {
        return Organization::query()
            ->with(['activity','building', 'phone'])
            ->whereHas('activity', function ($query) use ($activity) {
                $query->where('activity', $activity->activityID);
            })
            ->get();
    }
}
