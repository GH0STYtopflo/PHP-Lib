<?php

namespace Gh0stytopflo\PhpLib\Model;

use Gh0stytopflo\PhpLib\Model\Person;
use Gh0stytopflo\PhpLib\Persistence\StaffHandle;
use Gh0stytopflo\PhpLib\Util\IdGenerator;

class Staff extends Person implements Model
{
    private int $staffId;
    private string $positionTitle;
    private string $email;
    private int $shiftStart;
    private int $shiftEnd;

    public function __construct(
        string $name,
        string $lname,
        string $positionTitle,
        string $email,
        int $shiftStart,
        int $shiftEnd,
        ?int $staffId = null
    ) {
        if (isset($staffId)) {
            $this->staffId = $staffId;
        } else {
            IdGenerator::generate(fopen(StaffHandle::PATH_TO_FILE, 'r'));
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
            staffId: $csvRecord[0],
            name: $csvRecord[1],
            lname: $csvRecord[2],
            positionTitle: $csvRecord[3],
            email: $csvRecord[4],
            shiftStart: $csvRecord[5],
            shiftEnd: $csvRecord[6]
        );
    }

    public function getPropertiesArray(): array
    {
        return array(
            $this->staffId,
            $this->name,
            $this->lname,
            $this->positionTitle,
            $this->email,
            $this->shiftStart,
            $this->shiftStart
        );
    }

    public function printProperties(): void
    {
    }
}
