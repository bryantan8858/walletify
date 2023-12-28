<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Record;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    /**
     *  Index page controller
     */
    public function index(Request $request)
    {
        if (auth()->check()) {
            $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
            $categories = Category::all();

            $data = [];
            foreach ($categories as $category) {
                $data[$category->name] = [
                    'income' => $this->getTotal('Income', $category->id, $currentMonth),
                    'expense' => $this->getTotal('Expense', $category->id, $currentMonth),
                ];
            }

            $totalIncome = array_sum(array_column($data, 'income'));
            $totalExpense = array_sum(array_column($data, 'expense'));

            $netAmount = $totalIncome - $totalExpense;

            if ($request->ajax()) {
                return response()->json([
                    'totalIncome' => $totalIncome,
                    'totalExpense' => $totalExpense,
                    'netAmount' => $netAmount,
                    'data' => $data,
                ]);
            } else {
                return view('statistic.index', compact('data', 'totalIncome', 'totalExpense', 'netAmount'));
            }
        } else {
            return redirect('/login');
        }
    }

    /**
     *  Calculate total expense and income
     */
    private function getTotal($type, $category, $month)
    {
        $user = Auth::user();
        return Record::where('user_id', $user->id)->where('type', $type)
            ->where('category_id', $category)
            ->whereYear('datetime', Carbon::createFromFormat('Y-m', $month)->year)
            ->whereMonth('datetime', Carbon::createFromFormat('Y-m', $month)->month)
            ->sum('amount');
    }

    /**
     *  Expense page controller
     */
    public function expense(Request $request)
    {
        if (auth()->check()) {
            $startDate = $request->input('startDate', Carbon::now()->startOfMonth()->toDateString());
            $endDate = $request->input('endDate', Carbon::now()->startOfMonth()->toDateString());

            $currentPeriodExpenses = $this->calculateDailyExpenses($startDate, $endDate);

            $previousPeriodStartDate = Carbon::parse($startDate)->subMonth()->startOfMonth()->toDateString();
            $previousPeriodEndDate = Carbon::parse($endDate)->subMonth()->endOfMonth()->toDateString();
            $previousPeriodExpenses = $this->calculateDailyExpenses($previousPeriodStartDate, $previousPeriodEndDate);

            if ($request->ajax()) {
                return response()->json([
                    'currentPeriod' => $currentPeriodExpenses,
                    'previousPeriod' => $previousPeriodExpenses,
                ]);
            } else {
                return view('statistic.expense', compact('currentPeriodExpenses', 'previousPeriodExpenses'));
            }
        } else {
            return redirect('/login');
        }
    }

    /**
     *  Calculate daily expenses
     */
    private function calculateDailyExpenses($startDate, $endDate)
    {
        $user = auth()->user();
        $dailyExpenses = [];
        $currentDate = Carbon::parse($startDate);

        while ($currentDate->lte(Carbon::parse($endDate))) {
            $dailyExpense = Record::where('user_id', $user->id)
                ->whereDate('datetime', $currentDate->toDateString())
                ->where('type', 'Expense')
                ->sum('amount');

            $dailyExpenses[$currentDate->format('d M')] = $dailyExpense;

            $currentDate->addDay();
        }

        return $dailyExpenses;
    }

    /**
     *  Income page controller
     */
    public function income(Request $request)
    {
        if (auth()->check()) {
            $startDate = $request->input('startDate', Carbon::now()->startOfMonth()->toDateString());
            $endDate = $request->input('endDate', Carbon::now()->startOfMonth()->toDateString());

            $currentPeriodIncomes = $this->calculateDailyIncomes($startDate, $endDate);

            $previousPeriodStartDate = Carbon::parse($startDate)->subMonth()->startOfMonth()->toDateString();
            $previousPeriodEndDate = Carbon::parse($endDate)->subMonth()->endOfMonth()->toDateString();
            $previousPeriodIncomes = $this->calculateDailyIncomes($previousPeriodStartDate, $previousPeriodEndDate);

            if ($request->ajax()) {
                return response()->json([
                    'currentPeriod' => $currentPeriodIncomes,
                    'previousPeriod' => $previousPeriodIncomes,
                ]);
            } else {
                return view('statistic.income', compact('currentPeriodIncomes', 'previousPeriodIncomes'));
            }
        } else {
            return redirect('/login');
        }
    }

    /**
     *  Calculate daily incomes
     */
    private function calculateDailyIncomes($startDate, $endDate)
    {
        $user = auth()->user();
        $dailyIncomes = [];
        $currentDate = Carbon::parse($startDate);

        while ($currentDate->lte(Carbon::parse($endDate))) {
            $dailyIncome = Record::where('user_id', $user->id)
                ->whereDate('datetime', $currentDate->toDateString())
                ->where('type', 'Income')
                ->sum('amount');

            $dailyIncomes[$currentDate->format('Y-m-d')] = $dailyIncome;

            $currentDate->addDay();
        }

        return $dailyIncomes;
    }
}
