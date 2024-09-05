<?php

namespace App\Implementations;

use App\Interfaces\Model;
use App\Models\Specification;

class SpecificationImplementation implements Model
{
    private $specification;

    public function __construct()
    {
        $this->specification = new Specification();
    }

    public function resolveCriteria($data = [])
    {

        $query = Specification::Query();

        if (array_key_exists('columns', $data)) {
            $query = $query->select($data['columns']);
        } elseif (array_key_exists('raw_columns', $data)) {
            $query = $query->selectRaw($data['raw_columns']);
        } else {
            $query = $query->select("*");
        }
        if (array_key_exists('id', $data)) {
            $query = $query->where('id', $data['id']);
        }
        if (array_key_exists('keywords', $data)) {
            $query = $query->where('name', 'like', '%' . $data['keywords'] . '%');
        }

        if (array_key_exists('name', $data)) {
            $query = $query->where('name', $data['name']);
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
        $specification = Specification::findOrFail($id);
        return $specification;
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
        $this->specification = $this->getOne($id);

        $this->mapDataModel($data);

        $this->specification->save();

        return $this->specification;
    }

    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->specification->save();

        return $this->specification;
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
        ];

        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
               {
                    $this->specification->$val = $data[$val];
                }
            }
        }
    }
}
