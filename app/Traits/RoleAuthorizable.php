<?php
namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait RoleAuthorizable
{
    public function __construct() {
        $this->middleware('permission:role-view')->only("index","show");
        $this->middleware('permission:role-create')->only("create");
        $this->middleware('permission:role-edit')->only("edit");
        $this->middleware('permission:role-delete')->only("destroy");
    }
}
