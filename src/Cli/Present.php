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
                if (count($data) > 0) {
                    foreach ($data as $i => $book) {
                        echo "#$i:\n";
                        echo self::printBook($book);
                    }
                } else {
                    echo "No book records found.\n" .
                        "Try adding some by '... -A --target=book ...'\n";
                }
                break;
            case Target::MEMBER:
                if (count($data) > 0) {
                    foreach ($data as $i => $member) {
                        echo "#$i:\n";
                        echo self::printMember($member);
                    }
                } else {
                    echo "No member records found.\n" .
                        "Try adding some by '... -A --target=member ...'\n";
                }
                break;
            case Target::STAFF:
                if (count($data) > 0) {
                    foreach ($data as $i => $staff) {
                        echo "#$i:\n";
                        echo self::printStaff($staff);
                    }
                } else {
                    echo "No staff records found.\n" .
                    "Try adding some by '... -A --target=staff ...'\n";
                }
                break;
            case Target::LIBRARY:
                if ($data == null) {
                    echo "No library info available yet.\n";
                } else {
                    echo self::printLibrary($data);
                }
        };
    }

    private static function printBook(Book $book): string
    {
        $memberId = $book->getMemberId();

        $str = "[" . self::SPACES . "Book" . self::SPACES . "]\n";
        $str .= self::SPACES . "book-id:" . self::SPACES . $book->getBookId() . "\n";
        $str .= self::SPACES . "book-title:" . self::SPACES . $book->getTitle() . "\n";
        $str .= self::SPACES . "author:" . self::SPACES . $book->getAuthor() . "\n";
        $str .= self::SPACES . "year:" . self::SPACES . $book->getYear() . "\n";
        $str .= self::SPACES . "printing:" . self::SPACES . $book->getPrinting() . "\n";
        $str .= self::SPACES . "genre:" . self::SPACES . $book->getGenre() . "\n";
        $str .= self::SPACES . "borrowed-by:" . self::SPACES . (isset($memberId) ?
           "\e[0;33m" . $book->getMemberId() . "\e[0m" : "\e[0;32mCurrently not borrowed by anyone\e[0m") . "\n";
        $str .= self::SPACES . "borrow-date:" . self::SPACES .  $book->getBorrowDate() .  "\n";
        $str .= self::SPACES . "return-date:" . self::SPACES . $book->getReturnDate(). "\n";
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
        $str .= self::SPACES . "membership-date:" . self::SPACES . $member->getMembershipStartDate() . "\n";
        $str .= self::SPACES . "----------------------------------------\n";

        return $str;
    }

    private static function printStaff(Staff $staff): string
    {
        $now = strtotime('now');
        $end = strtotime($staff->getShiftEnd(), time());
        $start = strtotime($staff->getShiftStart(), time());

        $available = (
            ($now < $end && $start < $now) ? "\e[0;32mYes\e[0m" : "\e[0;31mNo\e[0m"
        );

        $str = "[" . self::SPACES . "Staff" . self::SPACES . "]\n";
        $str .= self::SPACES . "staff-id:" . self::SPACES . $staff->getStaffId() . "\n";
        $str .= self::SPACES . "name:" . self::SPACES . $staff->getName() . "\n";
        $str .= self::SPACES . "lname:" . self::SPACES . $staff->getLastname() . "\n";
        $str .= self::SPACES . "position-title:" . self::SPACES . $staff->getPositionTitle() . "\n";
        $str .= self::SPACES . "email:" . self::SPACES . $staff->getEmail() . "\n";
        $str .= self::SPACES . "shift-start:" . self::SPACES . $staff->getShiftStart() . "\n";
        $str .= self::SPACES . "shift-end:" . self::SPACES . $staff->getShiftEnd() . "\n";
        $str .= self::SPACES . "available:" . self::SPACES . $available . "\n";
        $str .= self::SPACES . "----------------------------------------\n";

        return $str;
    }

    private static function printLibrary(?Library $library): string
    {
        $now = strtotime('now');
        $close = strtotime($library->getClose(), time());
        $open = strtotime($library->getOpen(), time());

        $available = ($now < $close && $now > $open) ? "\e[0;32mOpen\e[0m" : "\e[0;31mClose\e[0m";

        $str = "[" . self::SPACES . "Library" . self::SPACES . "]\n";
        $str .= self::SPACES . "name:" . self::SPACES . $library->getName() . "\n";
        $str .= self::SPACES . "address:" . self::SPACES .$library->getAddress() . "\n";
        $str .= self::SPACES . "open:" . self::SPACES . $library->getOpen() . "\n";
        $str .= self::SPACES . "close:" . self::SPACES . $library->getClose(). " ($available)" . "\n";

        return $str;
    }
}
