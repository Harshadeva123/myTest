<?php

namespace App\Http\Controllers;

use App\Office;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Render add payment interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offices = Office::where('status', '1')->get();
        return view('payment.add_payment', ['title' => __('Add Payment'), 'offices' => $offices]);

    }

    /**
     * Return office month rental by office id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRentalByOffice(Request $request)
    {
        $id = $request['id'];
        $office = Office::find(intval($id));
        if ($office != null) {
            return response()->json(['success' => ['rental' => $office->total_payment, 'rental_formatted' => number_format($office->total_payment)]]);

        } else {
            return response()->json(['errors' => ['error' => 'Office Invalid!']]);

        }
    }

    /**
     * Save monthly payment in database.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation start
        $validator = \Validator::make($request->all(), [
            'office' => 'required|exists:office,idoffice',
            'discount' => 'nullable|numeric',
            'payment' => 'required|numeric',
            'paymentDate' => 'required|date',

        ], [
            'office.required' => 'Office should be provided!',
            'office.exists' => 'Office invalid!',
            'discount.numeric' => 'Discount can only contains numbers!',
            'payment.numeric' => 'Payment can only contains numbers!',
            'payment.required' => 'Payment should be provided!',
            'paymentDate.date' => 'Payment date format invalid!',
            'paymentDate.required' => 'Payment date should be provided!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }


        //validation end


        //save in payment table
        $user = new Payment();
        $user->idoffice = $request['office'];
        $user->discount = round($request['discount'], 2);
        $user->payment = round($request['payment'], 2);
        $user->total_with_discount = round($request['payment'] + $request['discount'], 2);
        $user->paid_date = date('Y-m-d', strtotime($request['paymentDate']));
        $user->status = 1; // default value for active user
        $user->save();
        //save in payment table  end

        return response()->json(['success' => 'User Registered Successfully!']);
    }

    /**
     * View all payments
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        $office = $request['office'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = Payment::query();
        if (!empty($office)) {
            $query = $query->where('idoffice', $office);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $payments = $query->where('status', 1)->latest()->paginate(10);

        $offices = Office::where('status', '1')->get();
        return view('payment.view_payments', ['title' => __('View Payments'), 'offices' => $offices,'payments'=>$payments]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
