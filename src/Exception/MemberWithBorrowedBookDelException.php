<?php

namespace Gh0stytopflo\PhpLib\Exception;

use Gh0stytopflo\PhpLib\Model\Member;
use RuntimeException;

class MemberWithBorrowedBookDelException extends RuntimeException
{
    public function __construct(Member $member, int $line = -1, int $code = 0)
    {
        $this->line = $line;
        $this->code = $code;

        $this->message = "You cannot delete \e[0;33m"
        . $member->getName()
        . "\e[0m {id: "
        . $member->getMemberId()
        . '}'
        . '. They currently have a book in their possession';
    }
}
