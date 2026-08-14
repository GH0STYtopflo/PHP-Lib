<?php

namespace Gh0stytopflo\PhpLib\Model;

use Gh0stytopflo\PhpLib\Model\Person;

class Member extends Person implements Model
{
    private int $membershipStartDate;
    private int $memberId;

    public function __construct(
        string $name,
        string $lname,
        int $membershipStartDate,
        ?int $memberId = null
    ) {
        //TODO: IdGen
        if (isset($memberId)) {
            $this->memberId = $memberId;
        }
        $this->name = $name;
        $this->lname = $lname;
        $this->membershipStartDate = $membershipStartDate;
    }

    public static function mapArrayToInstance(array $csvRecord): self
    {
        return new self(
            memberId: $csvRecord[0],
            name: $csvRecord[1],
            lname: $csvRecord[2],
            membershipStartDate: $csvRecord[3]
        );
    }

    public function getPropertiesArray(): array
    {
        return array(
            $this->memberId,
            $this->name,
            $this->lname,
            $this->membershipStartDate
        );
    }

    public function printProperties(): void
    {
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
