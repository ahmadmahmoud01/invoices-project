<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function section() {
        return $this->belongsTo(Section::class);
    }

    public function details() {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function attachments() {
        return $this->hasMany(InvoiceAttachment::class);
    }

}
