<?php
class Car {

    public function __construct(
        private $id = 0, private $license = "", private $model = "", private $brand = "", private $description = "", private $photo = ""
    ) {}

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
    public function get_license(): string {return $this->license;}

    /**
     * @param string $license
     */
    public function set_license(string $license): void {$this->license = $license;}

    /**
     * @return string
     */
    public function get_model(): string {return $this->model;}

    /**
     * @param string $model
     */
    public function set_model(string $model): void {$this->model = $model;}

    /**
     * @return string
     */
    public function get_brand(): string {return $this->brand;}

    /**
     * @param string $brand
     */
    public function set_brand(string $brand): void {$this->brand = $brand;}

    /**
     * @return string
     */
    public function get_description(): string {return $this->description;}

    /**
     * @param string $description
     */
    public function set_description(string $description): void {$this->description = $description;}

    /**
     * @return string
     */
    public function get_photo(): string {return $this->photo;}

    /**
     * @param string $photo
     */
    public function set_photo(string $photo): void {$this->photo = $photo;}
}