<?php

namespace Gh0stytopflo\PhpLib\Exception;

use Gh0stytopflo\PhpLib\Model\Member;
use RuntimeException;

class MemberWithBorrowedBookDelException extends RuntimeException
{
    public function __construct(Member $member, ?int $line = null, ?int $code = null)
    {
        $this->line = isset($line) ? $line : -1;
        $this->code = isset($code) ? $code : 0;

        $this->message = "You cannot delete \e[0;33m"
        . $member->getName()
        . "\e[0m {id: "
        . $member->getMemberId()
        . '}'
        . '. They currently have a book in their possession';
    }
}
