<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices_details extends Model
{
    use HasFactory;
    protected $fillable = [
        "invoice_ID",
        "invoice_number",
        "product",
        "section",
        "statuse",
        "value_status",
        "user",
        "note"
    ];
}
