<?php
/**
 *
 * Model.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/13 15:40
 * Editor: created by PhpStorm
 */

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public $columns = [];

    /**
     * 构造函数
     *
     * Model constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = [];
        foreach ($this->columns as $column) {
            if ($column != $this->primaryKey) {
                $this->fillable[] = $column;
            }
        }

        parent::__construct($attributes);
    }

}