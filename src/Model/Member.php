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
    private string $membershipStartDate;

    public function __construct(
        string $name,
        string $lname,
        string $phone,
        ?string $membershipStartDate = null,
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
            $this->membershipStartDate = date('Y/m/d', time());
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
            membershipStartDate: $csvRecord[5]
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

    public function getMembershipStartDate(): string
    {
        return $this->membershipStartDate;
    }

    public function getMemberId(): int
    {
        return $this->memberId;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
