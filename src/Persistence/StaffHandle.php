<?php

namespace Gh0stytopflo\PhpLib\Persistence;

use Gh0stytopflo\PhpLib\Model\Model;
use Gh0stytopflo\PhpLib\Model\Staff;

class StaffHandle implements Handle
{
    public const PATH_TO_FILE =  __DIR__ . '/../../tables/staff.csv';

    public static function append(Model $data)
    {
        $file = fopen(self::PATH_TO_FILE, 'a+');

        fputcsv($file, $data->getPropertiesArray());
    }

    public static function readAll(): array
    {
        $file = fopen(self::PATH_TO_FILE, 'r');
        $records = [];

        while (!feof($file)) {
            $record = fgetcsv($file, 0, ',');

            if (!is_bool($record)) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public static function writeAll(array $records): void
    {
        $file = fopen(self::PATH_TO_FILE, 'w+');

        foreach ($records as $record) {
            fputcsv($file, $record, ',');
        }
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
        $file = fopen(self::PATH_TO_FILE, 'r+');
        $csvRecords = [];

        while (feof($file)) {
            $record = fgetcsv($file, 0, ',');

            if (!is_bool($record)) {
                if (
                    (!isset($id) || $staffId == $record[0])
                    && (!isset($name) || str_contains(strtolower($record[1]), strtolower($name)))
                    && (!isset($lname) || str_contains(strtolower($record[2]), strtolower($lname)))
                    && (!isset($positionTitle) || str_contains(strtolower($record[3]), strtolower($positionTitle)))
                    && (!isset($email) || str_contains(strtolower($record[4]), strtolower($email)))
                    && (!isset($shiftStart) || $shiftStart == $record[5])
                    && (!isset($shiftEnd) || $shiftEnd == $record[6])
                ) {
                    $csvRecords[] = $record;
                }
            }
        }

        return $csvRecords;
    }

    public static function findById(int $id): Model|false
    {
        $file = fopen(self::PATH_TO_FILE, 'r');

        while (!feof($file)) {
            $csvRecord = fgetcsv($file);

            if ($csvRecord[0] == $id) {
                return Staff::mapArrayToInstance($csvRecord);
            }
        }

        return false;
    }
}
