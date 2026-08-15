<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Model\Staff;
use Gh0stytopflo\PhpLib\Persistence\StaffHandle;

class StaffService
{
    public static function add(
        string $name,
        string $lname,
        string $positionTitle,
        string $email,
        int $shiftStart,
        int $shiftEnd
    ): void {
        StaffHandle::append(new Staff($name, $lname, $positionTitle, $email, $shiftStart, $shiftEnd));
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

    public static function search(
        ?int $staffId = null,
        ?string $name = null,
        ?string $lname = null,
        ?string $positionTitle = null,
        ?string $email = null,
        ?int $shiftStart = null,
        ?int $shiftEnd = null
    ): array {
        $records = StaffHandle::search(
            $staffId,
            $name,
            $lname,
            $positionTitle,
            $email,
            $shiftStart,
            $shiftEnd
        );
        $staff = [];

        foreach ($records as $record) {
            $staff[] = Staff::mapArrayToInstance($record);
        }

        return $staff;
    }
}
