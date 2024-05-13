<?php

namespace App\Models;

use PDO;

use RuntimeException;

class SubCategoriesModel
{
    private $pdo;

    private const SUB_CATEGORY_TABLE = 'products_sub_categories_tb';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieve all subcategories from the database.
     *
     * @throws RuntimeException when an error occurs during the database operation
     * @return array An array of subcategories as associative arrays
     */
    public function getAllSubCategories()
    {
        $query = "SELECT * FROM " . self::SUB_CATEGORY_TABLE;
        $statement = $this->pdo->prepare($query);

        try {
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Retrieves a subcategory from the database by its ID.
     *
     * @param int $subCategoryID The ID of the subcategory to retrieve.
     * @throws RuntimeException If an error occurs during the database operation.
     * @return array An associative array representing the subcategory.
     */
    public function getSubCategoryById(int $subCategoryID): array
    {
        $query = "SELECT * FROM " . self::SUB_CATEGORY_TABLE . " WHERE id = :subCategoryID";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':subCategoryID', $subCategoryID, PDO::PARAM_STR);

        try {
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Retrieves a subcategory by name from the database.
     *
     * @param string $subCategoryName The name of the subcategory to retrieve.
     * @throws RuntimeException when an error occurs during the database operation
     * @return array An associative array representing the subcategory
     */
    public function getSubCategoryByName(string $subCategoryName): array
    {
        $query = "SELECT * FROM " . self::SUB_CATEGORY_TABLE . " WHERE name = :subCategoryName";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':subCategoryName', $subCategoryName, PDO::PARAM_STR);

        try {
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    public function getSubCategoryByCategoryId(int $categoryID): array
    /**
     * Retrieves a subcategory from the database based on the given category ID.
     *
     * @param int $categoryID The ID of the category to retrieve the subcategory from.
     * @throws RuntimeException If an error occurs during the database operation.
     * @return array An array of associative arrays representing the subcategory.
     */
    {
        $query = "SELECT * FROM " . self::SUB_CATEGORY_TABLE . " WHERE categoryID = :categoryID";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_STR);

        try {
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Retrieves the ID column for a subcategory from the database based on its name.
     *
     * @param string $subCategoryName The name of the subcategory to retrieve the ID column for.
     * @throws RuntimeException when an error occurs during the database operation
     * @return array An associative array containing the ID column for the subcategory
     */
    public function getSubCategoryIDColumn(string $subCategoryName): array
    {
        $query = "SELECT id FROM " . self::SUB_CATEGORY_TABLE . " WHERE subCategoryName = :subCategoryName";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':subCategoryName', $subCategoryName, PDO::PARAM_STR);

        try {
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Adds a new subcategory to the database.
     *
     * @param array $payload An associative array containing the category ID, subcategory name, and subcategory description.
     *                       The keys of the array should be 'categoryID', 'subCategoryName', and 'subCategoryDescription', respectively.
     * @throws RuntimeException If an error occurs during the database operation.
     * @return bool Returns true if the subcategory was successfully added, false otherwise.
     */
    public function addNewSubCategory(array $payload): bool
    {
        $categoryID = (int) $payload['categoryID'];
        $subCategoryName = $payload['subCategoryName'];
        $subCategoryDescription = $payload['subCategoryDescription'];

        $query = "INSERT INTO " . self::SUB_CATEGORY_TABLE . " (categoryID, subCategoryName, subCategoryDescription) VALUES (:categoryID, :subCategoryName, :subCategoryDescription)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $statement->bindValue(':subCategoryName', $subCategoryName, PDO::PARAM_STR);
        $statement->bindValue(':subCategoryDescription', $subCategoryDescription, PDO::PARAM_STR);

        try {
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Edits a subcategory in the database.
     *
     * @param int $subCategoryID The ID of the subcategory to edit.
     * @param array $payload An associative array containing the category ID, subcategory name, and subcategory description.
     *                       The keys of the array should be 'categoryID', 'subCategoryName', and 'subCategoryDescription', respectively.
     * @throws RuntimeException If an error occurs during the database operation.
     * @return bool Returns true if the subcategory was successfully edited, false otherwise.
     */
    public function editSubCategory(int $subCategoryID, array $payload): bool
    {
        $categoryID = (int) $payload['categoryID'];
        $subCategoryName = $payload['subCategoryName'];
        $subCategoryDescription = $payload['subCategoryDescription'];

        $query = "UPDATE " . self::SUB_CATEGORY_TABLE . " SET categoryID = :categoryID, subCategoryName = :subCategoryName, subCategoryDescription = :subCategoryDescription WHERE id = :id";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':id', $subCategoryID, PDO::PARAM_INT);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        $statement->bindValue(':subCategoryName', $subCategoryName, PDO::PARAM_STR);
        $statement->bindValue(':subCategoryDescription', $subCategoryDescription, PDO::PARAM_STR);

        try {
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Deletes a subcategory from the database based on the provided subcategory ID.
     *
     * @param int $subCategoryID The ID of the subcategory to delete.
     * @throws RuntimeException If an error occurs during the database operation.
     * @return bool Returns true if the subcategory was successfully deleted, false otherwise.
     */
    public function deleteSubCategory(int $subCategoryID): bool
    {
        $query = "DELETE FROM " . self::SUB_CATEGORY_TABLE . " WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $subCategoryID, PDO::PARAM_INT);

        try {
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}