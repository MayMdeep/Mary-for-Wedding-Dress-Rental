<?php

namespace App\Implementations;

use App\Interfaces\Model;
use App\Models\Dress;

class DressImplementation implements Model
{
    private $dress;

    public function __construct()
    {
        $this->dress = new Dress();
    }

    public function resolveCriteria($data = [])
    {

        $query = Dress::Query();

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

        if (array_key_exists('image', $data)) {
            $query = $query->where('image', $data['image']);
        }
        if (array_key_exists('description', $data)) {
            $query = $query->where('description', $data['description']);
        }

        if (array_key_exists('availability', $data)) {
            $query = $query->where('availability', $data['availability']);
        }
        if (array_key_exists('quantity', $data)) {
            $query = $query->where('quantity', $data['quantity']);
        }
        if (array_key_exists('rental_price', $data)) {
            $query = $query->where('rental_price', $data['rental_price']);
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
        $dress = Dress::findOrFail($id);
        return $dress;
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
        $this->dress = $this->getOne($id);

        $this->mapDataModel($data);

        $this->dress->save();

        return $this->dress;
    }

    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->dress->save();

        return $this->dress;
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
            ,'quantity'
            ,'availability'
            ,'image'
            ,'rental_price'
            ,'created_at'
			,'updated_at'
			,'deleted_at'
        ];

        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
                 {
                    $this->dress->$val = $data[$val];
                }
            }
        }
    }
}
