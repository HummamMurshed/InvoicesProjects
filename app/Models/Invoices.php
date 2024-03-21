<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        "invoice_number" ,
        "inovices_date",
        "due_date" ,
        "product" ,
        "section_ID" ,
        "Amount_collection" ,
        "Amount_Commission",
        "discount",
        "total" ,
        "value_vat"  ,
        "rate_vat" ,
        "status" ,
        "value_status" ,
        "note" ,
        "Payment_Date",
    ];
    protected $dates = ['deleted_at'];
    public function section()
    {
        return  $this->belongsTo( Sections::class,"section_ID");
    }
}
