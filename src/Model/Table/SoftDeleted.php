<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use SoftDelete\Model\Table\SoftDeleteTrait;

class SoftDeleted extends Table
{
    use SoftDeleteTrait;

    protected $softDeleteField = 'deleted_at';
}
