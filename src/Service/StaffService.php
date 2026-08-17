<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Model\Staff;
use Gh0stytopflo\PhpLib\Persistence\StaffHandle;

class StaffService
{
    public static function add(array $data): void
    {
        if (!isset($data['name'])) {
            return;

            // TODO: Throw exception
        }

        if (!isset($data['lname'])) {
            return;

            // TODO: Throw exception
        }

        if (!isset($data['email'])) {
            return;

            // TODO: Throw exception
        }

        if (!isset($data['position-title'])) {
            return;

            // TODO: Throw exception
        }

        if (!isset($data['shift-start'])) {
            return;

            // TODO: Throw exception
        }

        if (!isset($data['shift-end'])) {
            return;

            // TODO: Throw exception
        }

        StaffHandle::append(new Staff(
            name: $data['name'],
            lname: $data['lname'],
            positionTitle:  $data['position-title'],
            email:  $data['email'],
            shiftStart: $data['shift-start'],
            shiftEnd: $data['shift-end']
        ));
    }

    public static function remove(Staff $staff)
    {
        $csvRecords = StaffHandle::readAll();

        foreach ($csvRecords as $i => $record) {
            if ($record[0] == $staff->getStaffId()) {
                array_splice($csvRecords, $i);
                break;
            }
        }
        StaffHandle::writeAll($csvRecords);
    }

    public static function search(array $data): array
    {
        $records = StaffHandle::search(
            staffId: isset($data['staff-id']) ? $data['staff-id'] : null,
            name: isset($data['name']) ? $data['name'] : null,
            lname: isset($data['lname']) ? $data['lname'] : null,
            positionTitle:  isset($data['position-title']) ? $data['position-title'] : null,
            email:  isset($data['email']) ? $data['email'] : null,
            shiftStart: isset($data['shift-start']) ? $data['shift-start'] : null,
            shiftEnd: isset($data['shift-end']) ? $data['shift-end'] : null
        );
        $staff = [];

        foreach ($records as $record) {
            $staff[] = Staff::mapArrayToInstance($record);
        }

        return $staff;
    }
}
