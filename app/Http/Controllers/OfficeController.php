<?php

namespace App\Http\Controllers;

use App\District;
use App\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *render 'add office' interface
     */
    public function index()
    {
        $districts = District::where('status', '1')->get();
        return view('office.add_office', ['title' =>  __('Add Office'), 'districts'=>$districts]);
    }


    /**
     *save office
     */
    public function store(Request $request)
    {
        //validation start
        $validator = \Validator::make($request->all(), [
            'district' => 'required|numeric|exists:district,iddistrict',
            'officeName' => 'required|regex:/^[a-zA-Z ]*$/|max:100|unique:office,office_name',
            'payment' => 'nullable|numeric',
            'paymentDate' => 'nullable|date'

        ], [
            'district.required' => 'District should be provided!',
            'district.numeric' => 'District invalid!',
            'district.exists' => 'District invalid!',
            'officeName.required' => 'Office name should be provided!',
            'officeName.regex' => 'Office name should be only contains characters!',
            'officeName.max' => 'Office name should be less than 10 characters long!',
            'officeName.unique' => 'Office name already exist!',
            'payment.numeric' => 'Payment should be only contains numbers!',
            'paymentDate.date' => 'Payment date format invalid!'

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $subTotal = floatval($request['payment']);
        if($request['analysis'] == 'on'){
            $analysis = 1 ;
            $subTotal += 5000;
        }
        else{
            $analysis = 0 ;
        }

        if($request['attendance'] == 'on'){
            $attendance = 1 ;
            $subTotal += 5000;
        }
        else{
            $attendance = 0 ;
        }

        $netTotal = round($subTotal - $request['discount'],2);
        if($netTotal < 0){
            return response()->json(['errors' => ['error'=>'Total monthly payment must be grater than zero(0)']]);

        }

        //validation end

        //save in user table
        $office = new Office();
        $office->iddistrict = $request['district'];
        $office->office_name = $request['officeName'];
        $office->sub_total = round($subTotal,2);
        $office->discount = round($request['discount'],2);
        $office->monthly_payment = $netTotal;
        $office->payment_date =  date('Y-m-d', strtotime($request['paymentDate']));
        $office->analysis_available = $analysis;
        $office->attendence_available = $attendance;
        $office->status = 1; // default value for active office
        $office->save();

        //save in user table  end

        return response()->json(['success' => 'Office Registered Successfully!']);
    }


    /**
     *view all saved offices
     */
    public function view(Request $request)
    {
        $officeName = $request['officeName'];
        $district = $request['district'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = Office::query();
        if (!empty($officeName)) {
            $query = $query->where('office_name', 'like', '%' . $officeName . '%');
        }
        if ($district != null) {
            $query = $query->where('iddistrict', $district);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']. ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $offices = $query->where('status', 1)->latest()->paginate(10);
        $districts = District::where('status', '1')->get();

        return view('office.view_offices', ['title' =>  __('View Office'), 'offices' => $offices, 'districts' => $districts]);
    }


}
