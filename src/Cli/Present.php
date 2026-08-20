<?php

namespace Gh0stytopflo\PhpLib\Cli;

use Gh0stytopflo\PhpLib\Model\Book;
use Gh0stytopflo\PhpLib\Model\Enums\Target;
use Gh0stytopflo\PhpLib\Model\Library;
use Gh0stytopflo\PhpLib\Model\Member;
use Gh0stytopflo\PhpLib\Model\Staff;

class Present
{
    private const string SPACES = '   ';

    public static function printPrettiy(null|array|Library $data, Target $target): void
    {
        switch ($target) {
            case Target::BOOK:
                foreach ($data as $i => $book) {
                    echo "#$i:\n";
                    echo self::printBook($book);
                }
                break;
            case Target::MEMBER:
                foreach ($data as $i => $member) {
                    echo "#$i:\n";
                    echo self::printMember($member);
                }
                break;
            case Target::STAFF:
                foreach ($data as $i => $staff) {
                    echo "#$i:\n";
                    echo self::printStaff($staff);
                }
                break;
            case Target::LIBRARY:
                $jsonStr = json_encode($data, JSON_PRETTY_PRINT);
                echo $jsonStr;
        };
    }

    private static function printBook(Book $book): string
    {
        $str = "[" . self::SPACES . "Book" . self::SPACES . "]\n";
        $str .= self::SPACES . "book-id:" . self::SPACES . $book->getBookId() . "\n";
        $str .= self::SPACES . "book-title:" . self::SPACES . $book->getTitle() . "\n";
        $str .= self::SPACES . "author:" . self::SPACES . $book->getAuthor() . "\n";
        $str .= self::SPACES . "year:" . self::SPACES . $book->getYear() . "\n";
        $str .= self::SPACES . "printing:" . self::SPACES . $book->getPrinting() . "\n";
        $str .= self::SPACES . "genre:" . self::SPACES . $book->getGenre() . "\n";
        $str .= self::SPACES . "borrowed-by:" . self::SPACES . $book->getMemberId() . "\n";
        $str .= self::SPACES . "borrow-date   COMPLETE THIS\n";
        $str .= self::SPACES . "return-date   COMPLETE THIS\n"; //TODO: Do sum about dates
        $str .= self::SPACES . "----------------------------------------\n";

        return $str;
    }

    private static function printMember(Member $member): string
    {
        $str = "[" . self::SPACES . "Member" . self::SPACES . "]\n";
        $str .= self::SPACES . "member-id:" . self::SPACES . $member->getMemberId() . "\n";
        $str .= self::SPACES . "name:" . self::SPACES . $member->getName() . "\n";
        $str .= self::SPACES . "lname:" . self::SPACES . $member->getLastname() . "\n";
        $str .= self::SPACES . "email:" . self::SPACES . $member->getEmail() . "\n";
        $str .= self::SPACES . "phone:" . self::SPACES . $member->getPhone() . "\n";
        $str .= self::SPACES . "membership-date:" . self::SPACES . $member->getMembershipStartDate() . "\n"; //DO SUM ABOUT DATES
        $str .= self::SPACES . "----------------------------------------\n";

        return $str;
    }

    private static function printStaff(Staff $staff): string
    {
        $str = "[" . self::SPACES . "Staff" . self::SPACES . "]\n";
        $str .= self::SPACES . "staff-id:" . self::SPACES . $staff->getStaffId() . "\n";
        $str .= self::SPACES . "name:" . self::SPACES . $staff->getName() . "\n";
        $str .= self::SPACES . "lname:" . self::SPACES . $staff->getLastname() . "\n";
        $str .= self::SPACES . "position-title:" . self::SPACES . $staff->getPositionTitle() . "\n";
        $str .= self::SPACES . "email:" . self::SPACES . $staff->getEmail() . "\n";
        $str .= self::SPACES . "shift-start:" . self::SPACES . $staff->getShiftStart() . "\n"; // TIMES AND DATES
        $str .= self::SPACES . "shift-end:" . self::SPACES . $staff->getShiftEnd() . "\n";
        $str .= self::SPACES . "----------------------------------------\n";

        return $str;
    }
}
