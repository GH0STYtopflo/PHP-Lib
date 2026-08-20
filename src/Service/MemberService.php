<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Exception\MemberWithBorrowedBookDelException;
use Gh0stytopflo\PhpLib\Exception\RequiredPropertyNotProvidedException;
use Gh0stytopflo\PhpLib\Exception\TypeMismatchException;
use Gh0stytopflo\PhpLib\Model\Member;
use Gh0stytopflo\PhpLib\Persistence\BookHandle;
use Gh0stytopflo\PhpLib\Persistence\MemberHandle;

class MemberService
{
    public static function add(array $data): void
    {
        if (!isset($data['name'])) {
            throw new RequiredPropertyNotProvidedException('name', line: __LINE__);
        }

        if (!isset($data['lname'])) {
            throw new RequiredPropertyNotProvidedException('lname', line: __LINE__);
        }

        if (!isset($data['phone'])) {
            throw new RequiredPropertyNotProvidedException('phone', line: __LINE__);
        }

        if (isset($data['membership-date']) && !strtotime($data['membership-date'])) {
            throw new TypeMismatchException(
                'membership-date',
                'temporal string (Y/m/d)',
                gettype($data['membership-date']) . " (" . ($data['membership-date']) . ")",
                line: __LINE__
            );
        }

        $member = new Member(
            name: $data['name'],
            lname: $data['lname'],
            phone: $data['phone'],
            email: isset($data['email']) ? $data['email'] : null,
            membershipStartDate: isset($data['membership-date']) ? date('Y/m/d', strtotime($data['membership-date'])): null,
        );

        MemberHandle::append($member);
    }

    public static function remove(Member $member)
    {
        if (!self::hasBorrowedBookHook($member->getMemberId())) {
            $csvRecords = MemberHandle::readAll();

            foreach ($csvRecords as $i => $record) {
                if ($member->getMemberId() == $record[0]) {
                    array_splice($csvRecords, $i, 1);
                    break;
                }
            }
        } else {
            throw new MemberWithBorrowedBookDelException($member, __LINE__);
        }

        MemberHandle::writeAll($csvRecords);
    }

    private static function hasBorrowedBookHook(int $memberId): bool
    {
        $csvRecords = BookHandle::readAll();

        foreach ($csvRecords as $record) {
            if ($record[6] == $memberId) {
                return true;
            }
        }

        return false;
    }

    public static function search(array $data): array
    {
        $csvRecords = MemberHandle::search(
            id: isset($data['member-id']) ? (int) $data['member-id'] : null,
            name: isset($data['name']) ? $data['name'] : null,
            lname: isset($data['lname']) ? $data['lname'] : null,
            phone: isset($data['phone']) ? $data['phone'] : null,
            email: isset($data['email']) ? $data['email'] : null,
        );

        $members = [];

        foreach ($csvRecords as $record) {
            $members[] = Member::mapArrayToInstance($record);
        }

        return $members;
    }

    public static function edit(
        Member $member,
        array $data
    ): void {
        $csvRecords = MemberHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $member->getMemberId()) {
                $record[1] = isset($data['name']) ? $data['name'] : $record[1];
                $record[2] = isset($data['lname']) ? $data['lname'] : $record[2];
                $record[3] = isset($data['phone']) ? $data['phone'] : $record[3];
                $record[4] = isset($data['email']) ? $data['email'] : $record[4];
                $record[5] = isset($data['membership-date']) ? $data['membership-date'] : $record[5];
            }
        }

        MemberHandle::writeAll($csvRecords);
    }
}
