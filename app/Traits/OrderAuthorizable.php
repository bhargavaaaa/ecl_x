<?php
namespace App\Traits;

trait OrderAuthorizable
{
    public function __construct() {
        $this->middleware('permission:order-view')->only(["index", "show"]);
        $this->middleware('permission:order-create')->only("create");
        $this->middleware('permission:order-edit')->only("edit");
        $this->middleware('permission:order-delete')->only("destroy");
    }
}
