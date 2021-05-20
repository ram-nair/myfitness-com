<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;

use App\BusinessTypeCategory;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use App\Contracts\BusinessTypeCategoryContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class CategoryRepository
 *
 * @package \App\Repositories
 */
class BusinessTypeCategoryRepository extends BaseRepository implements BusinessTypeCategoryContract
{
    use UploadAble;

    /**
     * BusinessTypeCategoryRepository constructor.
     * @param BusinessTypeCategory $model
     */
    public function __construct(BusinessTypeCategory $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listBusinessTypeCategories(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findBusinessTypeCategoryById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }
    }

    /**
     * @param array $params
     * @return BusinessTypeCategory|mixed
     */
    public function createBusinessTypeCategory(array $params)
    {
        try {
            $collection = collect($params);
            $is_service = $collection->has('is_service') ? 1 : 0;
            $show_disclaimer = $collection->has('show_disclaimer') ? 1 : 0;
            $merge = $collection->merge(compact('is_service', 'show_disclaimer'));
            $business_type_category = new BusinessTypeCategory($merge->all());
            $business_type_category->save();
            return $business_type_category;
        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBusinessTypeCategory(array $params)
    {
        $category = $this->findBusinessTypeCategoryById($params['id']);

        $collection = collect($params)->except('_token');

        $is_service = $collection->has('is_service') ? 1 : 0;

        $show_disclaimer = $collection->has('show_disclaimer') ? 1 : 0;

        $merge = $collection->merge(compact('show_disclaimer'));

        $category->update($merge->all());

        return $category;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteBusinessTypeCategory($id)
    {
        $business_type_category = $this->findBusinessTypeCategoryById($id);
        $business_type_category->delete();
        return $business_type_category;
    }

}
