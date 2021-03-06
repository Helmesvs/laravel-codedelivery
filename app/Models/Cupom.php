<?php

namespace CodeDelivery\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use CodeDelivery\Presenters\BasePresenters\CupomPresenter;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use QrCode;

class Cupom extends Model implements Transformable
{
    use TransformableTrait;

    use PresentableTrait;
    protected $presenter = CupomPresenter::class;

    protected $fillable = [
        'code',
        'value',
        'used'
    ];

    public function getQrcode($size = 160, $format = 'png', $margin = 0)
    {
        return QrCode::format($format)->size($size)->margin($margin)->generate($this->code);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isDeletable()
    {
        if (!empty($this->orders)) {
            return false;
        }

        if ($this->used < 1) {
            return true;
        }

        return false;
    }
}
