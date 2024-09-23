<?php
namespace App\Traits;

use Hashids\Hashids;

trait HasCustomRouteId
{
    public function getRouteKey()
    {
        $hashids = new Hashids('nigel_nikhil', 16); // You can configure the length and salt
        return $hashids->encode($this->id);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $hashids = new Hashids('nigel_nikhil', 16);
        $decoded = $hashids->decode($value);

        return $this->where('id', $decoded[0] ?? null)->firstOrFail();
    }

    // public function getRouteKey()
    // {
    //     return base64_encode($this->id);
    // }

    // public function resolveRouteBinding($value, $field = null)
    // {
    //     $id = base64_decode($value);
    //     return $this->where('id', $id)->firstOrFail();
    // }
}
