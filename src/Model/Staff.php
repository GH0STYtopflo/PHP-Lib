<?php

namespace Gh0stytopflo\PhpLib\Model;

use Gh0stytopflo\PhpLib\Model\Person;
use Gh0stytopflo\PhpLib\Persistence\StaffHandle;
use Gh0stytopflo\PhpLib\Util\IdGenerator;

class Staff extends Person
{
    private int $staffId;
    private string $positionTitle;
    private string $email;
    private string $shiftStart;
    private string $shiftEnd;

    public function __construct(
        string $name,
        string $lname,
        string $positionTitle,
        string $email,
        string $shiftStart,
        string $shiftEnd,
        ?int $staffId = null
    ) {
        if (isset($staffId)) {
            $this->staffId = $staffId;
        } else {
            $this->staffId = IdGenerator::generate(fopen(StaffHandle::PATH_TO_FILE, 'r'));
        }
        $this->name = $name;
        $this->lname = $lname;
        $this->email = $email;
        $this->positionTitle = $positionTitle;
        $this->shiftStart = $shiftStart;
        $this->shiftEnd = $shiftEnd;
    }

    public static function mapArrayToInstance(array $csvRecord): self
    {
        return new self(
            staffId: (int) $csvRecord[0],
            name: $csvRecord[1],
            lname: $csvRecord[2],
            positionTitle: $csvRecord[3],
            email: $csvRecord[4],
            shiftStart: $csvRecord[5],
            shiftEnd: $csvRecord[6]
        );
    }

    public function getStaffId(): int
    {
        return $this->staffId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lname;
    }

    public function getPositionTitle(): string
    {
        return $this->positionTitle;
    }

    public function setPositionTitle(string $positionTitle): self
    {
        $this->positionTitle = $positionTitle;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getShiftStart(): string
    {
        return $this->shiftStart;
    }

    public function setShiftStart(string $shiftStart): self
    {
        $this->shiftStart = $shiftStart;

        return $this;
    }

    public function getShiftEnd(): string
    {
        return $this->shiftEnd;
    }

    public function setShiftEnd(string $shiftEnd): self
    {
        $this->shiftEnd = $shiftEnd;

        return $this;
    }
}
