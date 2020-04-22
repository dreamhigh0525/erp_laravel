<?php

namespace App\Http\Controllers;

use App\Models\Partner\partner_credit;
use Illuminate\Http\Request;
use PDF;

class PayableController extends Controller
{
    public function index()
    {
        $Payable = partner_credit::join('account_journals', 'partner_credit.journal', '=', 'account_journals.id')
                                    ->join('res_partners', 'partner_credit.partner_id', '=', 'res_partners.id')
                                    ->select('partner_credit.*', 'account_journals.code','account_journals.default_credit_account_id','res_partners.partner_name')
                                    ->orderBy('purchase_no','asc')
                                    ->where('status','UNPAID')->paginate('10');
        return view('payable.index', compact('Payable'));
    }
    public function print()
    {
        $Payable = partner_credit::join('account_journals', 'partner_credit.journal', '=', 'account_journals.id')
                                    ->join('res_partners', 'partner_credit.partner_id', '=', 'res_partners.id')
                                    ->select('partner_credit.*', 'account_journals.code','account_journals.default_credit_account_id','res_partners.partner_name')
                                    ->orderBy('purchase_no','asc')
                                    ->where('status','UNPAID')->paginate('10');
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadview('reports.accounting.payable_pdf', compact('Payable'));
        return $pdf->stream();
    }
}
