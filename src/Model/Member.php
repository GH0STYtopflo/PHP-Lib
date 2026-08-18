<?php

namespace Gh0stytopflo\PhpLib\Model;

use Gh0stytopflo\PhpLib\Model\Person;
use Gh0stytopflo\PhpLib\Persistence\MemberHandle;
use Gh0stytopflo\PhpLib\Util\IdGenerator;

class Member extends Person
{
    private int $memberId;
    private string $phone;
    private ?string $email;
    private int $membershipStartDate;

    public function __construct(
        string $name,
        string $lname,
        string $phone,
        ?int $membershipStartDate = null,
        ?string $email = null,
        ?int $memberId = null
    ) {
        if (isset($memberId)) {
            $this->memberId = $memberId;
        } else {
            $this->memberId = IdGenerator::generate(fopen(MemberHandle::PATH_TO_FILE, 'r'));
        }
        $this->name = $name;
        $this->lname = $lname;
        $this->phone = $phone;
        $this->email = $email;
        if (isset($membershipStartDate)) {
            $this->membershipStartDate = $membershipStartDate;
        } else {
            $this->membershipStartDate = time();
        }
    }

    public static function mapArrayToInstance(array $csvRecord): self
    {
        return new self(
            memberId: (int) $csvRecord[0],
            name: $csvRecord[1],
            lname: $csvRecord[2],
            phone: $csvRecord[3],
            email: !empty($csvRecord[4]) ? $csvRecord[4] : null,
            membershipStartDate: (int) $csvRecord[5]
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lname;
    }

    public function getMembershipStartDate(): int
    {
        return $this->membershipStartDate;
    }

    public function getMemberId(): int
    {
        return $this->memberId;
    }
}
