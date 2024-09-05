<?php

namespace App\Implementations;

use App\Interfaces\Model;
use App\Models\Reservation;

class ReservationImplementation implements Model
{
    private $reservation;

    public function __construct()
    {
        $this->reservation = new Reservation();
    }

    public function resolveCriteria($data = [])
    {

        $query = Reservation::Query();

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

        if (array_key_exists('dress_id', $data)) {
            $query = $query->where('dress_id', $data['dress_id']);
        }
        if (array_key_exists('id', $data)) {
            $query = $query->where('id', $data['id']);
        }

        if (array_key_exists('user_id', $data)) {
            $query = $query->where('user_id', $data['user_id']);
        }
        if (array_key_exists('rental_duration', $data)) {
            $query = $query->where('rental_duration', $data['rental_duration']);
        }

        if (array_key_exists('reservation_date', $data)) {
            $query = $query->where('reservation_date', $data['reservation_date']);
        }
        if (array_key_exists('created_at', $data)) {
            $query = $query->where('created_at', $data['created_at']);
        }
        if (array_key_exists('updated_at', $data)) {
            $query = $query->where('updated_at', $data['updated_at']);
        }

        if (array_key_exists('deleted_at', $data)) {
            $query = $query->where('deleted_at', $data['deleted_at']);
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
        $reservation = Reservation::findOrFail($id);
        return $reservation;
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
        $this->reservation = $this->getOne($id);

        $this->mapDataModel($data);

        $this->reservation->save();

        return $this->reservation;
    }

    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->reservation->save();

        return $this->reservation;
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
			,'user_id'
            ,'dress_id'
            ,'rental_duration'
            ,'reservation_date'
            ,'created_at'
			,'updated_at'
			,'deleted_at'
        ];

        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
                 {
                    $this->reservation->$val = $data[$val];
                }
            }
        }
    }
}
