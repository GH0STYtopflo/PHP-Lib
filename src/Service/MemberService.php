<?php

namespace Gh0stytopflo\PhpLib\Service;

use Gh0stytopflo\PhpLib\Model\Member;
use Gh0stytopflo\PhpLib\Persistence\BookHandle;
use Gh0stytopflo\PhpLib\Persistence\MemberHandle;

class MemberService
{
    public static function add(
        string $name,
        string $lname,
        string $phone,
        ?int $membershipStartDate = null,
        ?string $email = null,
    ): void {
        $member = new Member(
            name: $name,
            lname: $lname,
            phone: $phone,
            email: $email,
            membershipStartDate: $membershipStartDate
        );

        MemberHandle::append($member);
    }

    public static function remove(Member $member)
    {
        if (!self::hasBorrowedBookHook($member->getMemberId())) {
            $csvRecords = MemberHandle::readAll();

            foreach ($csvRecords as $i => &$record) {
                if ($record[6] == $member->getMemberId()) {
                    array_splice($csvRecords, $i, 1);
                    break;
                }
            }
        } else {
            return;
            // TODO: Throw an exception
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

    public static function search(
        ?int $id = null,
        ?string $name = null,
        ?string $lname = null,
        ?string $phone = null,
        ?string $email = null,
        ?int $date = null
    ): array {
        $csvRecords = MemberHandle::search(
            $id,
            $name,
            $lname,
            $phone,
            $email,
            $date
        );

        $members = [];

        foreach ($csvRecords as $record) {
            $members[] = Member::mapArrayToInstance($record);
        }

        return $members;
    }

    public static function edit(
        Member $member,
        ?string $name = null,
        ?string $lname = null,
        ?string $phone = null,
        ?string $email = null,
        ?int $date = null
    ): void {
        $csvRecords = MemberHandle::readAll();

        foreach ($csvRecords as &$record) {
            if ($record[0] == $member->getMemberId()) {
                $record[1] = isset($name) ? $name : $record[1];
                $record[2] = isset($lname) ? $lname : $record[2];
                $record[3] = isset($phone) ? $phone : $record[3];
                $record[4] = isset($email) ? $email : $record[4];
                $record[5] = isset($date) ? $date : $record[5];
            }
        }

        MemberHandle::writeAll($csvRecords);
    }
}
