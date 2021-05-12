<?php

namespace App\Traits;

use Carbon\Carbon;
use DB;

trait FieldUpdateTrait
{
    public function increase($column, $amount)
    {
        $updated_at = '';
        if ($this->updated_at) {
            $updated_at = ", updated_at = '".Carbon::now()->toDateTimeString()."'";
        }

        $sql = 'UPDATE '.$this->getTable()." SET $column = $column + $amount $updated_at WHERE ".$this->getKeyName().' = ?';

        DB::update($sql, [$this->getKey()]);
    }

    public function decrease($column, $amount)
    {
        $updated_at = '';
        if ($this->updated_at) {
            $updated_at = ", updated_at = '".Carbon::now()->toDateTimeString()."'";
        }

        $sql = 'UPDATE '.$this->getTable()." SET $column = IF(CAST($column as SIGNED)-$amount >= 0,$column-$amount,0) $updated_at WHERE ".$this->getKeyName().' = ?';
        DB::update($sql, [$this->getKey()]);
    }

    public function getField($field)
    {
        $result = DB::table($this->getTable())->select($field)->where($this->getKeyName(), $this->getKey())->first();

        if ($result) {
            return $result->$field;
        }

        return false;
    }

    public function setField($field, $value)
    {
        $data = [$field => $value];
        if ($this->updated_at) {
            $data['updated_at'] = Carbon::now();
        }
        DB::table($this->getTable())
            ->where($this->getKeyName(), $this->getKey())
            ->update($data);
    }
}
