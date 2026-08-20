<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Exception\InvalidOpenAndCloseException;
use Gh0stytopflo\PhpLib\Exception\InvalidShiftStartAndEndException;
use Gh0stytopflo\PhpLib\Exception\RequiredPropertyNotProvidedException;
use Gh0stytopflo\PhpLib\Exception\TypeMismatchException;
use Gh0stytopflo\PhpLib\Model\Staff;
use Gh0stytopflo\PhpLib\Persistence\StaffHandle;

class StaffService
{
    public static function add(array $data): void
    {
        self::addCheckHook($data);

        StaffHandle::append(new Staff(
            name: $data['name'],
            lname: $data['lname'],
            positionTitle:  $data['position-title'],
            email:  $data['email'],
            shiftStart: $data['shift-start'],
            shiftEnd: $data['shift-end']
        ));
    }

    private static function addCheckHook(array $data): void
    {
        if (!isset($data['name'])) {
            throw new RequiredPropertyNotProvidedException('name', line: __LINE__);
        }

        if (!isset($data['lname'])) {
            throw new RequiredPropertyNotProvidedException('lname', line: __LINE__);
        }

        if (!isset($data['email'])) {
            throw new RequiredPropertyNotProvidedException('email', line: __LINE__);
        }

        if (!isset($data['position-title'])) {
            throw new RequiredPropertyNotProvidedException('position-title', line: __LINE__);
        }

        if (!strtotime($data['shift-start']) || !str_contains($data['shift-start'], ':')) {
            throw new TypeMismatchException(
                'shift-start',
                'temporal string [HH:mm]',
                gettype($data['shift-start']) . " [" . ($data['shift-start']) . "]",
                line: __LINE__
            );
        }

        if (!strtotime($data['shift-end']) || !str_contains($data['shift-end'], ':')) {
            throw new TypeMismatchException(
                'shift-end',
                'temporal string [HH:mm]',
                gettype($data['shift-end']) . " [" . ($data['shift-end']) . "]",
                line: __LINE__
            );
        }

        if ((strtotime($data['shift-end']) < strtotime($data['shift-start']))) {
            throw new InvalidShiftStartAndEndException($data['shift-end'],$data['shift-start'], line: __LINE__);
        }
    }

    public static function remove(Staff $staff)
    {
        $csvRecords = StaffHandle::readAll();

        foreach ($csvRecords as $i => $record) {
            if ($record[0] == $staff->getStaffId()) {
                array_splice($csvRecords, $i, 1);
                break;
            }
        }
        StaffHandle::writeAll($csvRecords);
    }

    public static function search(array $data): array
    {
        if (count($data) == 0) {
            $records = StaffHandle::readAll();
        } else {
            $records = StaffHandle::search(
                staffId: isset($data['staff-id']) ? $data['staff-id'] : null,
                name: isset($data['name']) ? $data['name'] : null,
                lname: isset($data['lname']) ? $data['lname'] : null,
                positionTitle: isset($data['position-title']) ? $data['position-title'] : null,
                email: isset($data['email']) ? $data['email'] : null,
                shiftStart: isset($data['shift-start']) ? strtotime($data['shift-start']) : null,
                shiftEnd: isset($data['shift-end']) ? strtotime($data['shift-start']) : null
            );
        }

        $staff = [];

        foreach ($records as $record) {
            $staff[] = Staff::mapArrayToInstance($record);
        }

        return $staff;
    }

    public static function edit(
        Staff $staff,
        array $data
    ): void {
        self::editCheckHook($data);

        $csvRecords = StaffHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $staff->getStaffId()) {
                $record[1] = isset($data['name']) ? $data['name'] : $staff->getName();
                $record[2] = isset($data['lname']) ? $data['lname'] : $staff->getLastname();
                $record[3] = isset($data['position-title']) ? $data['position-title'] : $staff->getPositionTitle();
                $record[4] = isset($data['email']) ? $data['email'] : $staff->getEmail();
                $record[5] = isset($data['shift-start']) ? $data['shift-start'] : $staff->getShiftStart();
                $record[6] = isset($data['shift-end']) ? $data['shift-end'] : $staff->getShiftEnd();

                break;
            }
        }

        StaffHandle::writeAll($csvRecords);
    }

    private static function editCheckHook(array $data): void
    {
        if (isset($data['shift-start']) && (!strtotime($data['shift-start']) || !str_contains($data['shift-start'], ':'))) {
            throw new TypeMismatchException(
                'shift-start',
                'temporal string [HH:mm]',
                gettype($data['shift-start']) . " [" . ($data['shift-start']) . "]",
                line: __LINE__
            );
        }

        if (isset($data['shift-end']) && (!strtotime($data['shift-end']) || !str_contains($data['shift-end'], ':'))) {
            throw new TypeMismatchException(
                'shift-end',
                'temporal string [HH:mm]',
                gettype($data['shift-end']) . " [" . ($data['shift-end']) . "]",
                line: __LINE__
            );
        }

        if ((isset($data['shift-end']) && isset($data['shift-start']))  && ((strtotime($data['shift-end']) < strtotime($data['shift-start'])))) {
            throw new InvalidShiftStartAndEndException($data['shift-end'],$data['shift-start'], line: __LINE__);
        }
    }
}
