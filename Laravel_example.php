<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MidList;
use App\Transaction;
use Carbon\Carbon;

class AjaxController extends Controller
{
    public function saveTransInfo(Request $request)
    {
        $data = $request->all();

        $transaction = Transaction::find($data['id']);
        if ($transaction) {
            $transaction->received_amount = $data['received_amount'];
            $transaction->received_date = $data['received_date'];

            $transaction->variance_amount = $transaction->received_amount - $transaction->summ;

            $transaction->status = true;
            $transaction->expected_date = now();
            $transaction->save();
        }


        #create or update your data here

        return response()->json(['id' => $data['id'], 'result' => 'success']);
    }

    public function loadMoreDeposited(Request $request)
    {
    	$data = $request->all();
    	$corp = str_replace('_', ' ', $data['corp']);
        $old_date = $data['date']; 
    	$mids = MidList::active()->get()->groupBy('corp');


        $result_array = [];
        foreach ($mids[$corp] as $mid){
            $transactions = null;

            $transactions = $mid->transactions()->where('status', 1)->where('batch_date', '<', Carbon::parse($old_date))->where('batch_date', '>', Carbon::parse($old_date)->subDays(7))->orderBy('expected_date', 'desc')->get();
               
            if($transactions->isEmpty()){
                $result[$corp][0] = [];
            }

            if($transactions){
                $result[$corp]['date'] = Carbon::now()->subDays(7);
                foreach ($transactions as $key => $trans){
                    $less_15 = $trans->summ * 0.85;
                    $less_10 = $trans->summ * 0.90;
                    $less_7_5 = $trans->summ * 0.925;

                    $result_array[$trans->id]['id'] = $trans->id;
                    $result_array[$trans->id]['batch_date'] = $trans->batch_date;
                    $result_array[$trans->id]['mid_alias'] = $mid->mid_alias; 
                    $result_array[$trans->id]['mid'] = $mid->mid; 
                    $result_array[$trans->id]['batch_id'] = $trans->batch_id; 
                    $result_array[$trans->id]['charges_amount'] = number_format($trans->charges_amount ?? 0, 2, '.', ','); 
                    $result_array[$trans->id]['refunds_amount'] = number_format($trans->refunds_amount ?? 0, 2, '.', ','); 
                    $result_array[$trans->id]['summ'] = number_format($trans->summ, 2, '.', ','); 
                    $result_array[$trans->id]['received_amount'] = $trans->received_amount; 
                    $result_array[$trans->id]['received_date'] = $trans->received_date;                              
                    $result_array[$trans->id]['less_15'] = number_format($less_15, 2, '.', ','); 
                    $result_array[$trans->id]['less_10'] = number_format($less_10, 2, '.', ','); 
                    $result_array[$trans->id]['less_7_5'] = number_format($less_7_5, 2, '.', ',');
                    $result_array[$trans->id]['expected_date'] = $trans->expected_date; 
                }
            }
        }
        uasort($result_array, array($this, 'cmp'));
        $result  = '<div class="accordion-item">';
        $result .= '<a class="row-date toggle" href=#>'.Carbon::parse($old_date)->isoFormat('YYYY-M-D').' - '.Carbon::parse($old_date)->subDays(7)->isoFormat('YYYY-M-D').'</a>';
    	$result .= '<div class="class="col-md table-responsive inner show">';
    	$result .= '<table class="table table-hover table-sm">';
        $result .= '<thead>';
        $result .= '    <tr>';
        $result .= '        <th scope="col">#</th>';
        $result .= '        <th scope="col">expected date</th>';
        $result .= '        <th scope="col">Date</th>';
        $result .= '        <th scope="col">MID name</th>';
        $result .= '        <th scope="col">MID number</th>';
        $result .= '        <th scope="col">Batch ID</th>';
        $result .= '        <th scope="col">Charges amount</th>';
        $result .= '        <th scope="col">Refunds amount</th>';
        $result .= '        <th scope="col">Total amount</th>';
        $result .= '        <th scope="col">Received amount</th>';
        $result .= '        <th scope="col">Received date</th>';
        $result .= '        <th scope="col">Less 15% RR</th>';
        $result .= '        <th scope="col">Less 10% RR</th>';
        $result .= '        <th scope="col">Less 7.5% RR</th>';
        $result .= '        <th scope="col">Save</th>';
        $result .= '    </tr>';
        $result .= '</thead>';
        $result .= '<tbody>';

    	foreach ($result_array as $trans){

            $result .= '<tr scope="row" id="tr-' . $trans['id'] . '" data-type="deposited">';
            $result .= '<th>' . $trans['id'] . '</th>';
            $result .= '<th>' . $trans['expected_date']. '</th>';
            $result .= '<th>' . $trans['batch_date'] . '</th>';
            $result .= '<th>' . $trans['mid_alias'] . '</th>';
            $result .= '<th>' . $trans['mid'] . '</th>';
            $result .= '<th>' . $trans['batch_id'] . '</th>';
            $result .= '<th>' . $trans['charges_amount'] . '</th>';
            $result .= '<th>' . $trans['refunds_amount'] . '</th>';
            $result .= '<th>' . $trans['summ'] . '</th>';
            $result .= '<th><input type="number" class="form-control checkTwoDecimals" id="received_amount-' . $trans['id'] . '" value="' . $trans['received_amount'] . '" oninput="unlockButton(this.id, this.value), checkNum(this)"; step="0.01"></th>';
            $result .= '<th><input id="received_date-' . $trans['id'] . '" type="text" class="form-contro" value="' . $trans['received_date'] . '"/></th>';
            $result .= '<th>' . $trans['less_15'] . '</th>';
            $result .= '<th>' . $trans['less_10'] . '</th>';
            $result .= '<th>' . $trans['less_7_5'] . '</th>';
            $result .= '<th><button id="check-' . $trans['id'] . '" class="btn btn-outline-secondary" onclick="checkBatch(' . $trans['id'] . ');" role="button" disabled><i class="fa fa-check" aria-hidden="true"></i></button></th>';
            $result .= '</tr>';


        }
        $result .= '</div>';
        $result .= '</div>';

    	return response()->json(['data' => $result, 'new-date' => Carbon::parse($old_date)->subDays(7)->isoFormat('YYYY-M-D'),'result' => 'success']);
    }

    public function cmp($a, $b)
    {
        if(!empty($a) && !empty($b)){
            if ($a['expected_date'] == $b['expected_date']){
                return 0;
            }
            return ($a['expected_date'] > $b['expected_date'] || $b['expected_date'] === null) ? -1 : 1;
        }
    }
}