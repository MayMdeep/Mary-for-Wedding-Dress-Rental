<?php

namespace App\Implementations;

use App\Interfaces\Model;
use App\Models\SpecificationOption;

class SpecificationOptionImplementation implements Model
{
    private $specificationOption;

    public function __construct()
    {
        $this->specificationOption = new SpecificationOption();
    }

    public function resolveCriteria($data = [])
    {

        $query = SpecificationOption::Query();

        if (array_key_exists('columns', $data)) {
            $query = $query->select($data['columns']);
        } elseif (array_key_exists('raw_columns', $data)) {
            $query = $query->selectRaw($data['raw_columns']);
        } else {
            $query = $query->select("*");
        }

        if (array_key_exists('keywords', $data)) {
            $query = $query->where('name', 'like', '%' . $data['keywords'] . '%');
        }

        if (array_key_exists('name', $data)) {
            $query = $query->where('name', $data['name']);
        }
        if (array_key_exists('id', $data)) {
            $query = $query->where('id', $data['id']);
        }

        if (array_key_exists('specification_id', $data)) {
            $query = $query->where('specification_id', $data['specification_id']);
        }

        if (array_key_exists('orderBy', $data)) {
            $query = $query->orderBy($data['orderBy'], 'DESC');
        } else {
            $query = $query->orderBy('id', 'DESC');
        }


        return $query;
    }

    public function getOne($id)
    {
        $specificationOption = SpecificationOption::findOrFail($id);
        return $specificationOption;
    }

    public function getList($data)
    {
        $list = $this->resolveCriteria($data)->get();
        return $list;
    }
    public function getCount($data) {
        $list = $this->resolveCriteria($data)->count();
        return $list;
    }

    public function getPaginatedList($data, $perPage)
    {
        $list = $this->resolveCriteria($data)->paginate($perPage);
        return $list;
    }

    public function Update($data = [], $id)
    {
        $this->specificationOption = $this->getOne($id);

        $this->mapDataModel($data);

        $this->specificationOption->save();

        return $this->specificationOption;
    }

    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->specificationOption->save();

        return $this->specificationOption;
    }
    public function Delete($id)
    {
        $record = $this->getOne($id);

        $record->delete();
    }

    public function mapDataModel($data)
    {
        $attribute = [	
			'id'
			,'name'
            ,'description'
            ,'specification_id'
            ,'option_id'
            ,'quantity'
            ,'image'
            ,'rental_price'
			
        ];

        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
                 {
                    $model->$val = $data[$val];
                }
            }
        }
    }
}
