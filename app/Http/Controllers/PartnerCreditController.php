<?php

namespace App\Http\Controllers;

use App\Models\Partner\partner_credit;
use App\Models\Partner\res_partner;
use Illuminate\Http\Request;

class PartnerCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partner = res_partner::orderBy('partner_name', 'asc')->where('debit_limit','>',0)->paginate(10);
        return view('partner_dept.index', compact('partner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\partner_credit  $partner_credit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $partnerdebt = partner_credit::join('res_partners', 'partner_credit.partner_id', '=', 'res_partners.id')
                                        ->select('partner_credit.*', 'res_partners.partner_name')
                                        ->orderBy('purchase_date', 'asc')
                                        ->where([ ['status', 'UNPAID'],['partner_id',$id] ])->paginate(10);
        return view('partner_dept.show', compact('partnerdebt'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\partner_credit  $partner_credit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner_debt = partner_credit::join('res_partners', 'partner_credit.partner_id', '=', 'res_partners.id')
                                        ->select('partner_credit.*', 'res_partners.partner_name','res_partners.credit_limit')
                                        ->where('purchase_no', $id)->get();
        return view('partner_dept.edit', compact('partner_debt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\partner_credit  $partner_credit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, partner_credit $partner_credit)
    {
        try {
            $partner_debt = partner_credit::where('purchase_no',$request->purchase_no);
            $partner_debt->update([
                'payment' => $request->payment,
                'over' => $request->over,
                'status' => $request->status,
            ]);
            $credit="0";
            $partner = res_partner::where('id',$request->partner_id);
            $partner->update([
                'credit_limit' => $credit,
            ]);

            return redirect(route('PartnerDebt'))
                ->with(['success' => 'Payment Inoice No:<strong>' . $request->purchase_no . '</strong> created succesfully']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\partner_credit  $partner_credit
     * @return \Illuminate\Http\Response
     */
    public function destroy(partner_credit $partner_credit)
    {
        //
    }
}
