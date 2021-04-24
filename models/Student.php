<?php
class Student {

    /**
     * Constructor Student
     * @param int $id
     * @param string $email
     * @param string $name
     * @param string $license
     * @param string $age
     * @param string $course
     * @param string $photo
     */
    public function __construct(
        public $id = 0, public $email = "", public $name = "", public $license = "", public $age = "", public $course = "", public $photo = ""
    ) {}

    /**
     * Destructor Student
     */
    function __destruct() {}

    public static function set_student($student, $item): void {
        $student->id = $item['id'];
        $student->email = $item['email'];
        $student->name = $item['name'];
        $student->license = $item['license'];
        $student->age= $item['age'];
        $student->photo = $item['photo'];
    }

}