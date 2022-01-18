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
        private $id = 0, private $email = "", private $name = "", private $license = "", private $age = 0, private $course = "", private $photo = ""
    ) {}

    /**
     * Destructor Student
     */
    function __destruct() {}

    /**
     * @return int
     */
    public function get_id(): int {return $this->id;}

    /**
     * @param int $id
     */
    public function set_id(int $id): void {$this->id = $id;}

    /**
     * @return string
     */
    public function get_email(): string {return $this->email;}

    /**
     * @param string $email
     */
    public function set_email(string $email): void {$this->email = $email;}

    /**
     * @return string
     */
    public function get_name(): string {return $this->name;}

    /**
     * @param string $name
     */
    public function set_name(string $name): void {$this->name = $name;}

    /**
     * @return string
     */
    public function get_license(): string {return $this->license;}

    /**
     * @param string $license
     */
    public function set_license(string $license): void {$this->license = $license;}

    /**
     * @return int
     */
    public function get_age(): int {return $this->age;}

    /**
     * @param int $age
     */
    public function set_age(int $age): void {$this->age = $age;}

    /**
     * @return string
     */
    public function get_course(): string {return $this->course;}

    /**
     * @param string $course
     */
    public function set_course(string $course): void {$this->course = $course;}

    /**
     * @return string
     */
    public function get_photo(): string {return $this->photo;}

    /**
     * @param string $photo
     */
    public function set_photo(string $photo): void {$this->photo = $photo;}

}