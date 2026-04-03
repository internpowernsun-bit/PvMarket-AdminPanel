<?php

namespace App\Http\Controllers\Admin;

use App\Models\Incoterm;

class IncotermController extends ResourceController
{
    protected string $model   = Incoterm::class;
    protected string $view    = 'admin.setup.incoterms.incoterms';
    protected string $route   = 'admin.setup.incoterms';
    protected string $orderBy = 'name';
    protected string $orderDir = 'asc';

    protected array $rules = [
        'name' => 'required|string|max:255',
    ];

    protected array $fields = ['name'];
}