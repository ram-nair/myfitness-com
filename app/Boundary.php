<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Boundary extends Model
{
    use Uuids, SoftDeletes;

    protected $table = 'store_boundary';

    protected $guarded = ['id'];

    protected $geometry = ['positions'];

    protected $geometryAsText = true;

    public function newQuery($excludeDeleted = true)
    {
        if (!empty($this->geometry) && $this->geometryAsText === true) {
            $raw = '';
            foreach ($this->geometry as $column) {
                $raw .= 'AsText(`' . $this->table . '`.`' . $column . '`) as `' . $column . '`, ';
            }
            $raw = substr($raw, 0, -2);

            return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
        }
    }

}
