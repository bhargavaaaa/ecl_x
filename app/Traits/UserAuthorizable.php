<?php
namespace App\Traits;

trait UserAuthorizable
{
    public function __construct() {
        $this->middleware('permission:user-view')->only(["index", "show"]);
        $this->middleware('permission:user-create')->only("create");
        $this->middleware('permission:user-edit')->only("edit");
        $this->middleware('permission:user-delete')->only("destroy");
    }
}
