<?php
namespace App\Contracts;

/**
 * Interface CategoryContract
 * @package App\Contracts
 */
interface BusinessTypeCategoryContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listBusinessTypeCategories(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findBusinessTypeCategoryById(int $id);

    /**
     * @param array $params
     * @return mixed
     */
    public function createBusinessTypeCategory(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBusinessTypeCategory(array $params);

    /**
     * @param $id
     * @return bool
     */
    public function deleteBusinessTypeCategory($id);
}

