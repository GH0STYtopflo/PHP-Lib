# Library Management CLI

A command-line interface tool for managing a library system. This application allows users to manage books, members, staff, and general library information.

## Project Structure

The project follows an MVC-inspired architecture divided into three main layers:

| Layer | Purpose |
|-------|---------|
| Model | Represents the data structures used by the application |
| Service | Contains the main application logic and handles operations |
| CLI | Handles user commands and communicates with the rest of the application |

## Screenshot
![Screenshot](https://github.com/GH0STYtopflo/PHP-Lib/blob/main/Screenshot.png)

## Getting Started

### Prerequisites

- PHP CLI
- Composer

### Installation

1. Clone the repository:

```bash
git clone https://github.com/GH0STYtopflo/PHP-Lib
cd PHP-Lib
```

2. Create autoload files:

```bash
composer install
```
or alternatively:

```bash
composer dump-autoload
```

3. Make the execution script executable:

```bash
chmod +x exec.sh
```

4. Run the application:

```bash
./exec.sh ...
```

The `exec.sh` file is a wrapper script that simplifies running the PHP CLI application.

## Command Structure

The CLI design is inspired by Arch Linux's pacman package manager. Every command requires an operation and a target.

### General Format

```
./exec.sh -{operation} --target={value} [--on "--property-name=value"] --option=value
```

### Example

```bash
./exec.sh -A --target=book --title="Metro 2033" --author="Dmitry Glukhovsky" --year=2005
```

## Operations

| Operation | Symbol | Purpose |
|-----------|--------|---------|
| Add | -A | Adds a book, member, staff member, or library information |
| Delete | -D | Deletes a book, member, staff member, or library information |
| List | -L | Lists books, members, staff members, or library information |
| Edit | -E | Edits existing records |
| Borrow | -B | Records when a member borrows a book |
| Return | -R | Records when a borrowed book is returned |
| Search | -S | Searches for a book, member, or staff member |

### The --on Option

Some operations require an additional `--on` option to identify the exact record to affect:

- Edit
- Delete
- Borrow
- Return

The `--on` option accepts one or more property-value pairs to locate the target record. 
If the program can't pinpoint the record, It will prompt you to choose between a narrowed down list.

```bash
--on "--book-id=5"
```

## Targets

| Target | Purpose |
|--------|---------|
| book | Manage book records |
| member | Manage member records |
| staff | Manage staff records |
| library | Manage library information |

## Data Models

### Book

Contains information about books in the library.

| Property    | Required | Description                                                                  |
|-------------|----------|------------------------------------------------------------------------------|
| book-id     | No       | Unique identifier (Generated autoatically)                                   |
| title       | Yes      | Book title                                                                   |
| author      | No       | Author name                                                                  |
| year        | No       | Publication year                                                             |
| printing    | No       | Edition or printing number                                                   |
| genre       | No       | Book genre                                                                   |
| member-id   | No       | ID of member who borrowed the book                                           |
| borrow-date | No       | Date when the book was borrowed                                              |
| return-date | No       | Date when the book should be returned or when it was returned to the library |

### Member

Contains information about library members.

| Property | Required | Description |
|----------|----------|-------------|
| member-id | No | Unique identifier |
| name | Yes | First name |
| lname | Yes | Last name |
| phone | Yes | Phone number |
| email | No | Email address |
| membership-date | No | Date when the member joined |

### Staff

Contains information about library staff members.

| Property | Required | Description |
|----------|----------|-------------|
| staff-id | No | Unique identifier |
| name | Yes | First name |
| lname | Yes | Last name |
| position-title | Yes | Job position |
| shift-start | Yes | Start time of work shift |
| shift-end | Yes | End time of work shift |

### Library

Contains general information about the library.

| Property | Required | Description |
|----------|----------|-------------|
| name | Yes | Library name |
| address | Yes | Library address |
| open | Yes | Opening time |
| close | Yes | Closing time |

### Date and Time Formats

Dates must be in the format: `Y/m/d`

Example: `2026/08/20`

Times must be in the format: `HH:mm`

Examples: `08:30`, `14:45`, `20:00`

## Examples

### Add Operations

Add a book:

```bash
./exec.sh -A --target=book --title="Metro 2035" --author="Dmitry Glukhovsky" --year=2015 --genre="Science Fiction"
```

Add a book with minimal information:

```bash
./exec.sh -A --target=book --title="Metro 2033"
```

Add a member:

```bash
./exec.sh -A --target=member --name="Jeffery" --lname="Williams" --phone="5551234" --email="slimelifeysl@example.com"
```

Add a member with minimal information:

```bash
./exec.sh -A --target=member --name="John" --lname="Doe" --phone="5551234"
```

Add a staff member:

```bash
./exec.sh -A --target=staff --name="Jane" --lname="Smith" --position-title="Librarian" --shift-start="08:00" --shift-end="16:00"
```

Add library information:

```bash
./exec.sh -A --target=library --name="Central Library" --address="Main Street 12" --open="08:00" --close="20:00"
```

### List Operations

List all books:

```bash
./exec.sh -L --target=book
```

List all members:

```bash
./exec.sh -L --target=member
```

List all staff members:

```bash
./exec.sh -L --target=staff
```

List library information:

```bash
./exec.sh -L --target=library
```

### Search Operations

Search for a book by title:

```bash
./exec.sh -S --target=book --title="Metro 2033"
```

Search for a member by name:

```bash
./exec.sh -S --target=member --name="John" --lname="Cuddi"
```

### Edit Operations

Change the title of a book:

```bash
./exec.sh -E --target=book --on "--book-id=5" --title="Metro 2035"
```

Update a member's email:

```bash
./exec.sh -E --target=member --on "--member-id=3" --email="john@example.com"
```

### Delete Operations

Delete a book:

```bash
./exec.sh -D --target=book --on "--book-id=5"
```

Delete a member:

```bash
./exec.sh -D --target=member --on "--member-id=3"
```

### Borrow Operations

Record a book borrow:

```bash
./exec.sh -B --target=book --on "--book-id=5 --member-id=3" --borrow-date="2026/08/20" --return-date="2026/09/03"
```

### Return Operations

Record a book return:

```bash
./exec.sh -R --target=book --on "--book-id=5"
```

## Program Flow

The program processes commands through the following steps:

1. User enters a command in the terminal

2. Parser processes the command and checks for basic errors:
    - More than one operation provided
    - More than one target provided

3. If valid, the parser creates a command object containing:
    - Operation
    - Target
    - Options
    - --on information

4. Command object is passed to the validator, which checks for:
    - Invalid operation-target combinations
    - Missing required options
    - Incorrect command structure

5. If validation succeeds, the program executes the requested operation

## Repository Layer

The service layer does not directly interact with data files. Instead, it uses repository classes called "handles".

The name is inspired by file handles in Linux, where a handle provides a way to interact with a resource without managing its internal details.

The repository layer handles:
- File operations
- Data storage

The service layer focuses on:
- Application logic

## File Modification Safety

The project includes a mechanism to reduce the risk of concurrent file modifications.

Before modifying a file:

1. The program changes the file permissions to read-only
2. This prevents other processes from modifying the file during the operation
3. After the modification is complete, the program releases the file and restores normal access

Although simultaneous modifications are unlikely in this type of application, this system provides additional protection against accidental concurrent writes.
> On apps with bigger scale, the better solution would be buffering the changes to prevent a bottleneck. 
> However, for an app like this, the solution provided above is just about enough.

# Project Structure
```
PHP-Lib
   ├── composer.json
   ├── exec.sh
   ├── library.json
   ├── README.md
   ├── sandbox.php
   ├── Screenshot.png
   ├── src
   │   ├── Cli
   │   │   ├── Bootstrap.php
   │   │   ├── Ledger.php
   │   │   ├── Parser.php
   │   │   ├── Present.php
   │   │   ├── Start.php
   │   │   └── Validate.php
   │   ├── Exception
   │   │   ├── BorrowedBookDeletionException.php
   │   │   ├── BorrowingBorrowedBookException.php
   │   │   ├── InvalidOpenAndCloseException.php
   │   │   ├── InvalidShiftStartAndEndException.php
   │   │   ├── LockedFileAccessException.php
   │   │   ├── MemberWithBorrowedBookDelException.php
   │   │   ├── MultipleOperationsSpecifiedException.php
   │   │   ├── MultipleTargetsSpecifiedException.php
   │   │   ├── MutatingNonExistentLibraryInfoException.php
   │   │   ├── RequiredPropertyNotProvidedException.php
   │   │   ├── ReturningNotBorrowedBookException.php
   │   │   └── TypeMismatchException.php
   │   ├── Model
   │   │   ├── Book.php
   │   │   ├── Command.php
   │   │   ├── Enums
   │   │   │   ├── Operation.php
   │   │   │   └── Target.php
   │   │   ├── Library.php
   │   │   ├── Member.php
   │   │   ├── Model.php
   │   │   ├── Person.php
   │   │   └── Staff.php
   │   ├── Persistence
   │   │   ├── BookHandle.php
   │   │   ├── Handle.php
   │   │   ├── HandleTrait.php
   │   │   ├── LibraryHandle.php
   │   │   ├── MemberHandle.php
   │   │   └── StaffHandle.php
   │   ├── Service
   │   │   ├── BookService.php
   │   │   ├── LibraryService.php
   │   │   ├── MemberService.php
   │   │   └── StaffService.php
   │   └── Util
   │       ├── FilePermissionChecker.php
   │       ├── IdGenerator.php
   │       └── LockFile.php
   └── tables
       ├── book.csv
       ├── member.csv
       └── staff.csv
   
   10 directories, 49 files
```
