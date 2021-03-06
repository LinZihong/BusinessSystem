<?php

namespace App\Http\Controllers;

use App\Events\AcceptLoan;
use App\Events\DeclineLoan;
use App\Events\NewLoan;
use App\Events\RedeemLoan;
use App\User;
use App\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    //

    public function displayCreateForm(Request $request)
    {
        return view('loans.grantLoan');
    }

    public function listLoans(Request $request)
    {
        $meOut = Loan::where('creditor_id', $request->user()->id)->get();
        $meIn = Loan::where('debtor_id', $request->user()->id)->get();
        return view('loans.list')->with('meOut', $meOut)->with('meIn', $meIn);
    }

    public function grantLoan(Request $request)
    {
        $debtor_id = $request->debtor_id;
        $amount = $request->amount;
        $interest = $request->interest;
        $creditor = $request->user();
        if(empty($debtor = User::find($debtor_id)))
        {
            return view('errors.custom')->with('message', '不支持给冥界放款哦 请检查对方ID是否正确');//@TODO 这页面丑死了 有空换一个吧
        }
        if(!($creditor->type == 2 && $debtor->type == 1))
        {
            return view('errors.custom')->with('message', '你们之间不允许接待操作');
        }
        if($creditor->resources()->resid(1)->first()->amount < $amount)
        {
            return view('errors.custom')->with('message', '不够钱还是先别放款了吧');
        }
        event(new NewLoan($debtor, $creditor, $amount, $interest));
        return view('success')->with('message', '放款成功 等待对方接受');
    }

    public function handleLoan(Request $request)
    {
        $loan_id = $request->loan_id;
        if(empty($loan = Loan::find($loan_id)))
        {
            return view('errors.custom')->with('message', '这笔款怕是鬼给放的？请检查贷款ID是否正确');
        }
        $creditor = $loan->creditor;
        if($request->confirm == 'true')
        {
            if($loan->amount > $creditor->resources()->resid(1)->first()->amount) //End-weight principle tells us to put the longer expression at the end hence '>' instead of '<'
            {
                return view('errors.custom')->with('message', '说好借你钱的人不够钱 带着小姨子跑了');
            }
            if($creditor->id == $request->user()->id)
            {
                return view('errors.custom')->with('message', '不能接受自己放出的贷款');
            }
            event(new AcceptLoan($loan));
            return view('success')->with('message', '贷款已到账');
        }
        else if($request->confirm == 'false')
        {
            if($creditor->id == $request->user()->id)
            {
                return view('errors.custom')->with('message', '不能拒绝自己放出的贷款');
            }
            event(new DeclineLoan($loan));
            return view('success')->with('message', '拒绝成功');
        }
        else
        {
            return view('errors.custom')->with('message', 'Ummmmm...Something went WRONG and 啥都没干');
        }
    }

    public function redeemLoan(Request $request)
    {
        $loan_id = $request->loan_id;
        if(empty($loan = Loan::find($loan_id)))
        {
            return view('errors.custom')->with('message', '给鬼还钱？请检查贷款ID是否正确');
        }
        $debtor = $loan->debtor;
        $creditor = $loan->creditor;
        if(round($loan->amount * $loan->interest) > $debtor->resources()->resid(1)->first()->amount)
        {
            return view('errors.custom')->with('message', '剩下的钱不够还了 快带上小姨子跑路吧');
        }
        if($creditor->id == $request->user()->id)
        {
            return view('errors.custom')->with('message', '不能通过这种方式催债哦');
        }
        event(new RedeemLoan($loan));
        return view('success')->with('message', '还款成功');
    }
}
